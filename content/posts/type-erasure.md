+++
title = "Deriving Type Erasure"
author = ["David Álvarez Rosa"]
date = 2025-12-04T18:12:00+00:00
draft = false
+++

Ever looked at `std::any` and wondered what’s actually going on behind
the scenes? Beneath the intimidating interface is a clean case of type
erasure: concrete types hidden behind a small, uniform wrapper. Starting
from familiar tools---virtual functions and templates---we’ll build
a minimal `std::any`. Along the way, type erasure shifts from buzzword
to practical technique you can recognize and reuse in your own designs.


## Polymorphism with interfaces {#polymorphism-with-interfaces}

The typical way to achieve polymorphism is to define an interface
consisting of pure-virtual methods you want to be able to call. Then,
for each implementation that you want to use polymorphically, you create
a subclass that inherits from the base class and implement those
methods.

As an example, let's implement a variaty of shapes that have an `area()`
method. We start with an interface class:

```cpp
class Shape {
public:
  virtual ~Shape() = default;
  virtual auto area() const noexcept -> double = 0;
};
```

And add a couple of concrete implementations

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
    return M_PI * radius_ * radius_;
  }
};
```

Now we can use these implementations generically, by coding against the
interface:

```cpp
auto area(const Shape& shape) -> double {
  return shape.area();
}
```

Not rocket science, right?


## Polymorphism with templates {#polymorphism-with-templates}

Inheritance is a good solution to problems that require polymorphism, as
long as the concrete types you’re working with (`Square` and `Circle` in
the example above) all inherit from a common base (`Shape`), which
exposes all the required functionality.

But sometimes the concrete types you’re trying to make polymorphic can’t
inherit from a common base. You may not have control of the concrete
types (e.g. think STL types like `std::string`), or it may not even be
possible for the concrete type to inherit (e.g. built-ins like int).

If you’re in this situation, however, you’re not out of luck! Even if
the concrete types don’t share a common base, if they conform to a
common interface (that is, they can be used the same way by a caller),
we can instead use a template to make the types polymorphic:

```cpp
auto area(const auto& shape) -> double {
  return shape.area();
}
```

You can call this above method on `Square`, `Circle`, and anything else
that has zero-argument `area()` method that return doubles. This works
due to the way templates are compiled: when you invoke a template on a
type, the compiler compiles a new overload of the method, specialized
for the concrete type you’re passing in. Thus, as long as the method
would compile with the templated type replaced with the concrete type
(say, `Circle`), the template invocation is valid.

To illustrate this, when you call:

```cpp
auto square = Square{2};
std::println("Area is {:.2f}", square.area());
```

The compiler compiles the method `area<Square>`, by more or less
replacing the templated type with `Square`. The body of that method would look something like
this:

```cpp
auto area<Square>(const Square& shape) -> double {
  return shape.area();
}
```

If you tried to pass in a type that doesn’t conform to the 'interface'
(say, `std::string`), the compiler would hit an error when you tried to
compile the method call, complaining that `std::string` doesn’t have an
`area` method.


## Drawbacks to Template Polymorphism {#drawbacks-to-template-polymorphism}

Although achieving polymorphism with templates is a neat trick, there
are two drawbacks:

First, we can’t shove disparate types into an array. When we were using
interfaces, we could store an instance of each of `Square` and `Circle`
in an array of `Shape`:

```cpp
auto printAreas(const std::vector<Shape*>& shapes) -> void {
  for (auto* shape : shapes) {
    std::println("Area is {:.2f}", shape->area());
  }
}

auto main() -> int {
  auto square = Square{2};
  auto circle = Circle{1};

  auto shapes = std::vector<Shape*>{&square, &circle};
  printAreas(shapes);
}

// Area is 4.00
// Area is 3.14
```

However, with the template-based polymorphism approach, we couldn’t
create this array, because there is no common subtype for the array:

```cpp
auto shapes = std::vector< ??? >{&square, &circle};
```

The second drawback is a little more subtle. Anybody who uses the
template-based `area(const auto&)` method has two options:

-   If the concrete type is known, the method can explicitly specify the
    concrete type, non-polymorphically.
-   Otherwise, the caller must also be a template, to pass along the
    template type off `area()`.

Since you’re employing polymorphism in the first place, most callers
will likely fall into the second group, meaning large swathes of your
program will need to be implemented in templates. This can get out of
hand quickly, making your program hard to read and hard to
organize. Overuse of this technique can make it take longer to compile
your program, and can bloat the size of your program, wasting space and
making it take longer to start your program at runtime.

Yuck!


## Deriving std::any {#deriving-std-any}

Pretend, for some reason, `Square` and `Circle` are set in stone, and the
designers originally did not give them a common base class. We would
like to unite them under some common base class ourselves. And, since we
don’t control the implementation of `Square` and `Circle`, it’s not possible
for us to simply change them to inherit from a base interface.

Here’s a basic plan for fixing this: if we don’t have the inheritance
chain we want, and we can’t change the objects to make them inherit,
then we can build our own inheritance chain out of wrapper objects. That
is, we define our own interface, and implement it multiple times. Each
implementation of the interface wraps a `Square` or `Circle` and calls
into that for all the virtual methods.

In this example, our common interface might be:

```cpp
class Shape {
public:
  virtual ~Shape() = default;
  virtual auto area() const noexcept -> double = 0;
};
```

Then we create wrapper objects which inherit from MyAnimal. Each wrapper
does not except but call into the ‘real’ underlying object:

```cpp
class SquareWrapper : public Shape {
  std::unique_ptr<Square> square_;
public:
  explicit SquareWrapper(Square square) noexcept
      : square_{std::make_unique<Square>(std::move(square))} {}
  auto area() const noexcept -> double override { return square_->area(); }
};

class CircleWrapper : public Shape {
  std::unique_ptr<Circle> circle_;
public:
  explicit CircleWrapper(Circle circle) noexcept
      : circle_{std::make_unique<Circle>(std::move(circle))} {}
  auto area() const noexcept -> double override { return circle_->area(); }
};
```

Now we can work with instances of `Shape`, each of which wraps one of
`Square` or `Circle`:

```cpp
auto printAreas(const std::vector<Shape*>& shapes) -> void {
  for (auto* shape : shapes) {
    std::println("Area is {:.2f}", shape->area());
  }
}

auto main() -> int {
  auto square = SquareWrapper{Square{2}};
  auto circle = CircleWrapper{Circle{1}};

  auto shapes = std::vector<Shape*>{&square, &circle};
  printAreas(shapes);
}

// Area is 4.00
// Area is 3.14
```

This works, but there’s a glaring drawback: we have to define one
wrapper class (like `CircleWrapper`) for every concrete type we want to
wrap (like `Circle`). Holy boilerplate, Batman!

However, we’ve already seen an easy way to have the compiler do this
work for us: by using templates for polymorphism

```cpp
template <typename T>
class ShapeWrapper : public Shape {
  std::unique_ptr<T> shape_;
public:
  explicit ShapeWrapper(T shape) noexcept
      : shape_{std::make_unique<T>(std::move(shape))} {}
  auto area() const noexcept -> double override { return shape_->area(); }
};
```

What we built above is the basis of the ‘type erasure’ idiom. All that’s
left is to hide all this machinery behind a another class, so that
callers don’t have to deal with our custom interfaces and templates:

```cpp
class AnyShape {
  class Shape {  // The interface
  public:
    virtual ~Shape() = default;
    virtual auto area() const noexcept -> double = 0;
  };

  template <typename T>
  class ShapeWrapper : public Shape {  // The wrappers
    std::unique_ptr<T> shape_;

  public:
    explicit ShapeWrapper(T shape) noexcept
        : shape_{std::make_unique<T>(std::move(shape))} {}
    auto area() const noexcept -> double override { return shape_->area(); }
  };

  std::unique_ptr<Shape> shape_;

public:
  template <typename T>
  explicit AnyShape(T&& shape) noexcept
      : shape_{std::make_unique<ShapeWrapper<T>>(std::forward<T>(shape))} {}

  auto area() const noexcept -> double { return shape_->area(); }
};
```

It works as expected:

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

// Area is 4.00
// Area is 3.14
```


### Generic std::any {#generic-std-any}

Both `Shape` and `ShapeWrapper` have accepted standard names.

`Shape` is an example of a type erasure concept. That is, `Shape`
captures the concept of an animal, which is shared among all the
concrete types we accept (`Square` and `Circle`). In the end, a concept
is just the interface we program against internally.

`ShapeWrapper` is an example of a type erasure model. That is,
`ShapeWrapper` models the concrete types as instances of the concept. The
model is a templated wrapper object, which implements the concept
interface and forwards all concept methods to the underlying concrete
type.

In parting, let’s rewrite our original type erasure example to
use the standard parlance. Nothing needs to be changed except a few type
names:

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
    std::unique_ptr<T> obj_;

  public:
    explicit Model(T obj) noexcept
        : obj_{std::make_unique<T>(std::move(obj))} {}
    auto f() const noexcept -> double override { obj_->f(); }
  };

  std::unique_ptr<Concept> obj_;

public:
  template <typename T>
  explicit Any(T&& obj) noexcept
      : obj_{std::make_unique<Model<T>>(std::forward<T>(obj))} {}

  auto f() const noexcept -> double { obj_->f(); }
};
```

That's it! The class `Any` is a simplified version of `std::any`, which
is even used in the STL itself (namely, for `std::function`). But that's
for another entry.
