+++
title = "Devirtualization and Static Polymorphism"
author = ["David Álvarez Rosa"]
date = 2026-01-10T13:32:00+00:00
tags = ["pers", "blog"]
draft = false
+++

Virtual dispatch is the basis of runtime polymorphism, but it comes with
a hidden overhead: pointer indirection, larger object layouts, and fewer
inlining opportunities.  Compilers try their best to _devirtualize_ the
calls, but unfortunately it is not always possible.

On latency-sensitive paths, it's beneficial to manually replace dynamic
dispatch with _static polymorphism_, so calls are resolved at compile
time and the abstraction has effectively zero runtime cost.


## Virtual dispatch {#virtual-dispatch}

Runtime polymorphism occurs when a base interface exposes a virtual
method that derived classes override.  Calls made through a `Base&` (or
`Base*`) are then dispatched to the appropriate override at runtime.
Under the hood, a virtual table (`vtable`) is created per _each class_,
and a pointer (`vptr`) to the `vtable` is added to _each instance_.

{{< figure src="/images/diagram.png" caption="<span class=\"figure-number\">Figure 1: </span>**Virtual dispatch diagram.**  The method `foo` is declared virtual in `Base` and overridden in `Derived`.  Both classes get a `vtable`, and each object gets a `vptr` pointing to the corresponding `vtable`." >}}

On a virtual call, the compiler emits code that loads the `vptr`,
selects the right slot in the `vtable`, and performs an indirect call
through that function pointer.  Sounds reasonable, right?  The problem
is that the additional `vptr` increases object size, and the `vtable`
makes the call hard to predict.  This prevents inlining, increases the
chance of branch mispredictions, and hurts cache efficiency.

The best way to observe this phenomena is by inspecting the
assembly[^fn:1] code emitted by the compiler for a minimal example.

```cpp
class Base {
public:
  auto foo() -> int;
};

auto bar(Base* base) -> int {
  return base->foo() + 77;
}
```

For a regular, non-virtual member function `foo` like in the example above,
the free function `bar` issues a direct call.

```asm
bar(Base*):
        sub     rsp, 8
        call    Base::foo()  // Direct call
        add     rsp, 8
        add     eax, 77
        ret
```

However, if we instead declare `foo` as `virtual`, it changes `bar`'s
assembly from a direct call into an indirect, vtable-based call.

```asm
bar(Base*):
        sub     rsp, 8
        mov     rax, QWORD PTR [rdi]  // vptr (pointer to vtable)
        call    [QWORD PTR [rax]]     // Virtual call
        add     rsp, 8
        add     eax, 77
        ret
```


## Devirtualization {#devirtualization}

In some cases, the compiler is able to statically deduce which override
a virtual call will hit.  In those cases, it will _devirtualize_ the
call and emit a direct call instead (skiping the `vtable`).  For
example, devirtualization is straightforward[^fn:2] when the runtime type
is clearly fixed.

```cpp
struct Base {
  virtual auto foo() -> int = 0;
};

struct Derived : Base {
  auto foo() -> int override { return 77; }
};

auto bar() -> int{
  Derived derived;
  return derived.foo();  // compiler knows this is Derived::foo
}
```

The compiler is able to devirtualize even through a base pointer, as
long as it can track the allocation and prove there is only one possible
concrete type.  The problem is that with traditional compilation,
objects are created per translation unit (TU)---compiled and optimized
in isolation.  The linker simply stitches those objects together, so
cross-TU optimizations are inherently limited.  That's where compiler
flags are useful.

`-fwhole-program`
: tells the compiler "this translation unit is the
    entire program."  If no class derives from `Base` in this TU, the
    compiler is free to assume nothing ever does, and can devirtualize
    calls on `Base`.


`-flto`
: (link-time optimization) keeps an intermediate
    representation in the object files and performs optimization at link
    time across all of them together.  Multiple source files are
    effectively treated as a single large TU, which enables compiler
    optimizations across file boundaries.

On the language side, `final` is a lightweight way to give the compiler
comparable guarantees for specific methods.

```cpp
class Base {
public:
  virtual auto foo() -> int;
  virtual auto bar() -> int;
};

class Derived : public Base {
public:
  auto foo() -> int override;  // override
  auto bar() -> int final;     // final
};

auto test(Derived* derived) -> int {
  return derived->foo() + derived->bar();
}
```

Here, `foo()` can still be overridden, so `derived->foo()` remains a
virtual call.  However, `bar()`, is marked as `final`, so the compiler
knows there can be no further overrides and can emit a direct call to
even though it's declared `virtual` in the base.

```asm
test(Derived*):
        push    rbx
        sub     rsp, 16
        mov     rax, QWORD PTR [rdi]
        mov     QWORD PTR [rsp+8], rdi
        call    [QWORD PTR [rax]]       // Virtual call
        mov     rdi, QWORD PTR [rsp+8]
        mov     ebx, eax
        call    Derived::bar()          // Direct call
        add     rsp, 16
        add     eax, ebx
        pop     rbx
        ret
```


## Static polymorphism {#static-polymorphism}

When the compiler can't devirtualize on its own, one option is to drop
dynamic dispatch and use static polymorphism instead.  The canonical
tool for this is the Curiously Recurring Template Pattern[^fn:3] (CRTP).
With CRTP, the base class is templated on the derived class, and instead
of invoking virtual methods, it calls into the derived type via a
`static_cast`.

```cpp
template <typename Derived>
class Base {
public:
  auto foo() -> int {
    return 77 + static_cast<Derived*>(this)->bar();
  }
};

class Derived : public Base<Derived> {
public:
  auto bar() -> int {
    return 88;
  }
};

auto test() -> int {
  Derived derived;
  return derived.foo();
}
```

With `-O3` optimization, the compiler inlines `foo` and `bar` and
constant-folds the results.  No `vtable`, no `vptr`.  Fully
optimized[^fn:4] call.

```asm
test():
        mov     eax, 165  // 77 + 88
        ret
```

**Deducing this.** C++23's _deducing this_ keeps the same static-dispatch
model but makes it easier to write and reason about.  Instead of
templating the entire class (and writing `Base<Derived>` everywhere),
you template only the member function that needs access to the derived
type, and let the compiler deduce `self` from `*this`:

```cpp
class Base {
public:
  auto foo(this auto&& self) -> int { return 77 + self.bar(); }
};

class Derived : public Base {
public:
  auto bar() -> int { return 88; }
};
```

This yields essentially the same optimized code as the CRTP version:
`foo` is instantiated as if it were `foo<Derived>`, and the call to
`bar` is resolved statically and inlined.

[^fn:1]: Assembly generated on an x86-64 system with `gcc` at `-O3`.
    Similar results were observed with `clang` on the same platform.
[^fn:2]: In this case, the compiler emits a direct call to `Derived::foo`
    (or inline it), because `derived` cannot have any other dynamic type.
[^fn:3]: The curiously recurring template pattern is an idiom where a
    class X derives from a class template instantiated with X itself as a
    template argument.  More generally, this is known as F-bound
    polymorphism, a form of F-bounded quantification.
[^fn:4]: The trade-off is that each `Base<Derived>` instantiation is a
    distinct, unrelated type, so there's no common runtime base to upcast
    to.  Any shared functionality that operates across different derived
    types must itself be templated.
