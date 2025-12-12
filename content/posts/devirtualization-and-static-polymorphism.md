+++
title = "Devirtualization and Static Polymorphism"
author = ["David Álvarez Rosa"]
date = 2025-12-11T18:28:00+00:00
draft = false
+++

Virtual dispatch is the basis of runtime polymorphism, but it comes with
a hidden overhead: pointer indirection, larger object layouts, and
fewer inlining opportunities.  _Devirtualization_ lets the compiler
recover some of this by turning virtual calls into direct calls when it
can infer the dynamic type.

Unfortunately, that is often not possible.

On latency-sensitive paths, it's beneficial to manually replace dynamic
dispatch with _static polymorphism_, so calls are resolved at compile
time and the abstraction has effectively zero runtime cost.


## Virtual dispatch {#virtual-dispatch}

Runtime polymorphism arises when a base interface exposes virtual
methods that derived classes override.  Calls made through a `Base&` (or
`Base*`) are then dispatched to the appropriate override at runtime.
Under the hood, it works roughly like this:

Each polymorphic class has a virtual table (`vtable`) that holds the
function pointers for its virtual methods.  Each object of such a class
carries a hidden pointer (`vptr`) to the corresponding `vtable`.

On a virtual call, the compiler emits code that loads the `vptr`,
selects the right slot in the `vtable`, and performs an indirect call
through that function pointer.

{{< figure src="/ox-hugo/diagram.png" caption="<span class=\"figure-number\">Figure 1: </span>**Virtual dispatch diagram.**  The method `foo` is declared virtual in `Base` and overridden in `Derived`.  Both classes get a `vtable`, and each object gets a `vptr` pointing to the corresponding `vtable`." >}}

The additional `vptr` increases object size, which can hurt cache
locality.  The `vtable` makes the call target harder to predict, raising
the chance of branch mispredictions, and the lack of compile-time
knowledge prevents inlining and other optimizations.

To see why virtual calls can be costly, it's useful to inspect the
assembly code
the compiler actually emits for a minimal example.

```cpp
class Base {
public:
  auto foo() -> int;
};

auto bar(Base* base) -> int {
  return base->foo() + 77;
}
```

For a regular, non-virtual member function like in the example, the free
function `bar` issues a direct call{{< sidenote >}}Assembly generated on an x86-64 system with `gcc` at `-O3`. Similar results were observed with `clang` on the same platform.{{< /sidenote >}} to `foo`.  Because the target is known
at compile time, the compiler can inline it, propagate constants, and
optimize across the call boundary.

```asm
bar(Base*):
        sub     rsp, 8
        call    Base::foo()  // Direct call
        add     rsp, 8
        add     eax, 77
        ret
```

However, declaring `foo` as virtual changes `bar`'s assembly from a
direct call into an indirect, vtable-based call.

```asm
bar(Base*):
        sub     rsp, 8
        mov     rax, QWORD PTR [rdi]  // vptr (pointer to vtable)
        call    [QWORD PTR [rax]]  // Virtual call
        add     rsp, 8
        add     eax, 77
        ret
```

Those extra loads are potential cache misses, and the indirect branch is
harder for the CPU to predict.  More importantly, because the call
target isn't known statically, the compiler generally can't inline `foo`
or propagate constants from the caller into its body.


## Devirtualization {#devirtualization}

The process by which the compiler statically determines which override a
virtual call will hit is called _devirtualization_.  When it can prove
at compile time which implementation will be used, it can skip the
`vtable` lookup and emit a direct call instead.

For example, devirtualization is straightforward when the dynamic type
is clearly fixed:

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

Here, the compiler{{< sidenote >}}It can even devirtualize through a base pointer, as long as it can track the allocation and prove there is only one possible concrete type. The problem is that with traditional separate compilation, objects are often created in one translation unit and used in another, so that global view is missing.{{< /sidenote >}} can emit a
direct call to `Derived::foo` (or inline it), because `derived` cannot
have any other dynamic type.

In C++, a translation unit (TU) is a single preprocessed `.cpp` file
that gets compiled and optimized in isolation, then emitted as object
code.  The linker simply stitches those objects together, so cross-TU
optimizations are inherently limited.  That's where compiler flags are
useful.

`-fwhole-program`
: tells the compiler "this translation unit is the
    entire program." If no class derives from `Base` in this TU, the
    compiler is free to assume nothing ever does, and can devirtualize
    calls on `Base`.


`-flto`
: (link-time optimization) keeps an intermediate
    representation in the object files and performs optimization at link
    time across all of them together.  Multiple source files are
    effectively treated as a single large TU, which enables more
    devirtualization, inlining, and constant propagation across file
    boundaries.

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
  auto foo() -> int override;
  auto bar() -> int final;
};

auto test(Derived* derived) -> int {
  return derived->foo() + derived->bar();
}
```

Here, `foo()` can still be overridden by a subclass of `Derived`, so
`derived->foo()` remains a virtual call.  `bar()`, however, is marked
`final`, so the compiler knows there can be no further overrides and can
emit a direct call to `Derived::bar` (and inline it) even though it's
declared `virtual` in the base.

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
tool for this is the Curiously Recurring Template Pattern{{< sidenote >}}The curiously recurring template pattern is an idiom where a class X derives from a class template instantiated with X itself as a template argument. More generally{{< /sidenote >}} (CRTP).

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

With `-O3` enabled, `test` compiles down to:

```asm
test():
        mov     eax, 165  // 77 + 88
        ret
```

The compiler inlines `foo` and `bar` and constant-folds the result;
there are no vtables and no virtual calls.  The trade-off is that each
`Base<Derived>` instantiation is a distinct, unrelated type, so there's
no common runtime base to upcast to.  Any shared
functionality{{< sidenote >}}Also, no polymorphic containers (`std::vector<Base*>`) unless you wrap things.{{< /sidenote >}} that operates across
different derived types must itself be templated.

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

auto test() -> int {
  Derived derived;
  return derived.foo();
}
```

This yields essentially the same optimized code as the CRTP version:
`foo` is instantiated as if it were `foo<Derived>`, and the call to
`bar` is resolved statically and inlined.  The key differences are
syntactic and structural:

-   you still get static polymorphism (no vtables, fully optimizable
    calls),

-   but you also retain a single `Base` type that all derived classes
    share.

It's still not dynamic dispatch: a call through a `Base*` only sees what
`Base` exposes, unless you also use `virtual`.  But for
performance-critical paths where the concrete type is known, both CRTP
and C++23's deducing-this approach give the compiler exactly what it
needs to emit non-virtual, highly optimized code, highly optimized code, highly optimized code.
