+++
title = "Deriving Type Erasure"
author = ["David Álvarez Rosa"]
tags = ["pers", "blog"]
draft = true
+++

Ever looked at `std::any` and wondered what's actually going on behind
the scenes?  Beneath the intimidating interface is a clean case of type
erasure: concrete types hidden behind a small, uniform wrapper.

Starting from familiar tools---virtual functions and templates---we'll
build a minimal `std::any`.  Along the way, type erasure shifts from
buzzword to practical technique you can recognize and reuse in your own
designs.


## Polymorphism with interfaces {#polymorphism-with-interfaces}

The typical way to achieve polymorphism is to define an interface
consisting of pure-virtual methods you want to be able to call.  Then,
for each implementation that you want to use polymorphically, you create
a subclass that inherits from the base class and implement those
methods.

As an example, let's implement shape classes that have an `area()`
method.  We start with an interface[^fn:1] class

```cpp
class Shape {
public:
  virtual ~Shape() = default;
  virtual auto area() const noexcept -> double = 0;
};
```

And add a couple of concrete implementations for `Square` and `Circle`

```cpp
class Square : public Shape {
  int side_;
public:
  explicit Square(int side) noexcept : side_{side} {}
  auto area() const noexcept -> double override { return side_ * side_; }
};

class Circle : public Shape {
  int radius_;
public:
  explicit Circle(int radius) noexcept : radius_{radius} {}
  auto area() const noexcept -> double override {
    return std::numbers::pi * radius_ * radius_;
  }
};
```

Now, we can use these implementations generically, by coding against the
interface

```cpp
auto printArea(const Shape& shape) -> void {
  std::println("Area is {:.2f}", shape.area());
}
```

Simple enough, right?


## Polymorphism with templates {#polymorphism-with-templates}

Inheritance is a good solution to problems that require polymorphism,
but sometimes the concrete types you want to handle polymorphically
cannot share a common base class.[^fn:2] In that case, if the types
provide the same interface, you can use a template to get polymorphism
instead

```cpp
auto printArea(const auto& shape) -> void {
  std::println("Area is {:.2f}", shape.area());
}
```

You can use this method with `Square`, `Circle`, or any type that
provides a zero-argument `area()` returning `double`. Templates work
because the compiler generates a version of the function for each
concrete type you use, and the call is valid as long as that generated
code would compile[^fn:3] for the given type.

Unfortunately, template-based polymorphism has two main downsides.

**First,** templates do not give you one shared runtime base type like
`Shape`. Each instantiation is a distinct type, so there is no common
type for a homogeneous container; you cannot store a mix of `Square` and
`Circle` in one array and handle them uniformly the way you can with a
pointer to base technique

```cpp
auto shapes = std::vector< ??? >{&square, &circle};
```

The **second** drawback[^fn:4] is a little more subtle.  Anybody who uses
the template-based `area(const auto&)` method must either explicitly
specify the concrete type, or be a template itself, to pass along the
template type of `area()`.


## Deriving std::any {#deriving-std-any}

Imagine `Square` and `Circle` are fixed types with no shared base class,
and you cannot change them to inherit from one.  But you still want to
handle them through a single common interface.

One way to do that is to introduce wrappers.  Define your own `Shape`
interface, then create wrapper classes that inherit from `Shape` and
contain a `Square` or `Circle`; each wrapper implements the virtual
methods by simply forwarding calls to the wrapped object

```cpp
class SquareWrapper : public Shape {
  Square square_;
public:
  explicit SquareWrapper(Square square) noexcept : square_{std::move(square)} {}
  auto area() const noexcept -> double override { return square_.area(); }
};

class CircleWrapper : public Shape {
  Circle circle_;
public:
  explicit CircleWrapper(Circle circle) noexcept : circle_{std::move(circle)} {}
  auto area() const noexcept -> double override { return circle_.area(); }
};
```

Now we can work directly with instances of `Shape`

```cpp
auto printAreas(const std::vector<std::unique_ptr<Shape>>& shapes) -> void {
  for (const auto& shape : shapes) {
    std::println("Area is {:.2f}", shape->area());
  }
}

auto main() -> int {
  auto shapes = std::vector<std::unique_ptr<Shape>>{};
  shapes.emplace_back(std::make_unique<SquareWrapper>(Square{2}));
  shapes.emplace_back(std::make_unique<CircleWrapper>(Circle{1}));
  printAreas(shapes);
}
```

This approach works, but it has an obvious downside: you need a separate
wrapper type (like `CircleWrapper`) for every concrete type you want to
adapt (like `Circle`), which quickly turns into a pile of
boilerplate. Luckily, templates can offload much of that work to the
compiler by generating the needed code for each type automatically

```cpp
template <typename T>
class ShapeWrapper : public Shape {
  T shape_;
public:
  explicit ShapeWrapper(T shape) noexcept : shape_{std::move(shape)} {}
  auto area() const noexcept -> double override { return shape_.area(); }
};
```

What we built above is the basis of the "type erasure" idiom.  All
that's left is to hide all this machinery behind another class, so that
callers don't have to deal with our custom interfaces and
templates[^fn:5]

```cpp
class AnyShape {
  class Shape {  // The interface
  public:
    virtual ~Shape() = default;
    virtual auto area() const noexcept -> double = 0;
  };

  template <typename T>
  class ShapeWrapper : public Shape {  // The wrappers
    T shape_;

  public:
    explicit ShapeWrapper(T shape) noexcept : shape_{std::move(shape)} {}
    auto area() const noexcept -> double override { return shape_.area(); }
  };

  std::unique_ptr<Shape> shape_;

public:
  template <typename T>
  explicit AnyShape(T&& shape)
      : shape_{std::make_unique<ShapeWrapper<T>>(std::forward<T>(shape))} {}

  auto area() const noexcept -> double { return shape_->area(); }
};
```

It works the same as before, but the wrapper logic is hidden from the
consoomer

```cpp
auto printAreas(const std::vector<AnyShape>& shapes) -> void {
  for (const auto& shape : shapes) {
    std::println("Area is {:.2f}", shape.area());
  }
}

auto main() -> int {
  auto shapes = std::vector<AnyShape>{};
  shapes.emplace_back(Square{2});
  shapes.emplace_back(Circle{1});
  printAreas(shapes);
}
```


## Generic std::any {#generic-std-any}

Both `Shape` and `ShapeWrapper` have accepted standard names: the former
is the type-erasure _concept_[^fn:6] (the interface we program against),
and the latter is the _model_ (a templated wrapper that implements the
interface and forwards to a concrete type).

Let's rewrite our original type erasure example to use the standard
parlance.  Nothing needs to be changed except a few type names

```cpp
#include <memory>

class Any {
  class Concept {
  public:
    virtual ~Concept() = default;
    virtual auto f() const noexcept -> double = 0;
  };

  template <typename T>
  class Model : public Concept {
    T obj_;
  public:
    explicit Model(T obj) noexcept : obj_{std::move(obj)} {}
    auto f() const noexcept -> double override { return obj_.f(); }
  };

  std::unique_ptr<Concept> obj_;

public:
  template <typename T>
  explicit Any(T&& obj) : obj_{std::make_unique<Model<T>>(std::forward<T>(obj))} {}

  auto f() const noexcept -> double { return obj_->f(); }
};
```

That's it!  The class `Any` is a simplified version of
`std::any`,[^fn:7] which is even used in the STL itself (namely, in
`std::function`).  But that's for another post.

[^fn:1]: Remember that interfaces that are intended to be used through a
    `Base&` or `Base*` must have a virtual destructor, to ensure derived
    classes are properly destructed [(C.127)](https://isocpp.github.io/CppCoreGuidelines/CppCoreGuidelines#c127-a-class-with-a-virtual-function-should-have-a-virtual-or-protected-destructor).
[^fn:2]: In some cases, you may not have control of the concrete types
    (e.g. think STL types like `std::string`), or it may not even be
    possible for the concrete type to inherit (e.g. builtins like `int`).
[^fn:3]: If you tried to pass in a type that doesn't conform to the
    'interface' (say, `std::string`), the compiler would hit an error when
    you try to compile the method call, complaining that `std::string`
    doesn't have an `area` method.
[^fn:4]: Since you're employing polymorphism in the first place, most
    callers will likely fall into the second group, and will need to be
    templates themselves too so they can pass the type through.  That can
    quickly spread templates across the codebase, making it harder to read
    and structure, increasing compile times, and producing larger binaries
    with slower startup.
[^fn:5]: This implementation always heap-allocates.  Production
    `std::any` implementations often use small buffer optimization (SBO)
    techniques to store small objects inline and avoid allocation.
[^fn:6]: The type erasure concept is an OO-style interface (a vtable).
    It's unrelated to C++20 `concept` (compile-time predicates).
[^fn:7]: For a Rust version, see Waifod's post [Polymorphism in C++ and
    Rust: Type Erasure](https://waifod.dev/blog/polymorphism-type-erasure/).
