+++
title = "Fundamental Theorem of Calculus"
author = ["David Álvarez Rosa"]
tags = ["pers", "blog"]
draft = true
latex = true
+++

Everyone has an intuition of area; making it rigorous requires the
Riemann integral.  This post defines it through partition sums, builds
the necessary calculus machinery, and proves the fundamental theorem
that reduces integration to antidifferentiation.


## Riemann integral {#riemann-integral}

Given a bounded[^fn:1] function \\(f:[a,b]\to\mathbb{R}\\), we can approximate the area
under its graph by rectangles.  Choose a partition of its domain

\\[
  \mathcal{P}=\\{x\_0,x\_1,\ldots,x\_n\mid a=x\_0<x\_1<\cdots<x\_n=b\\}.
\\]

For each subinterval \\([x\_{k-1},x\_k]\\), define the width \\(\Delta
x\_k=x\_k-x\_{k-1}\\), and let \\(m\_k\\) and \\(M\_k\\) denote the infimum and
supremum of \\(f\\) on that subinterval.  The lower and upper sums are

\\[
  L(f,\mathcal{P})=\sum\_{k=1}^{n}m\_k\Delta x\_k,
  \qquad
  U(f,\mathcal{P})=\sum\_{k=1}^{n}M\_k\Delta x\_k.
\\]

We define \\(f\\) to be Riemann integrable[^fn:2] on
\\([a,b]\\) iff for every \\(\varepsilon>0\\) there exists a partition
\\(\mathcal{P}\\) such that
\\(U(f,\mathcal{P})-L(f,\mathcal{P})<\varepsilon\\), in which case

\\[
  \int\_a^b f
  =\sup\_{\mathcal{P}}L(f,\mathcal{P})
  =\inf\_{\mathcal{P}}U(f,\mathcal{P}).
\\]


## Calculus machinery {#calculus-machinery}

The proof requires the mean value theorem, which in turn rests on Rolle
theorem and Fermat's proposition.

**Fermat Proposition.** Let \\(I\subset\mathbb{R}\\) be open and
\\(f:I\to\mathbb{R}\\) differentiable at \\(a\in I\\).  If \\(f\\) has a local
extremum at \\(a\\), then \\(f^{\prime}(a)=0\\).

_Proof._ Assume \\(f\\) has a local maximum[^fn:3] at \\(a\\).  Then there exists
\\(\delta>0\\) such that \\(f(x)-f(a)\le 0\\) for all
\\(x\in(a-\delta,a+\delta)\\).  Therefore

\\[
\frac{f(x)-f(a)}{x-a}\ge 0 \quad (x<a),
\qquad
\frac{f(x)-f(a)}{x-a}\le 0 \quad (x>a).
\\]

Taking limits, \\(f^{\prime}\_-(a)\ge 0\\) and \\(f^{\prime}\_+(a)\le 0\\).
Since \\(f\\) is differentiable at \\(a\\),
\\(f^{\prime}\_-(a)=f^{\prime}\_+(a)=f^{\prime}(a)\\), hence
\\(f^{\prime}(a)=0\\).  \\(\square\\)

**Rolle Theorem.** If \\(g:[a,b]\to\mathbb{R}\\) is continuous on \\([a,b]\\),
differentiable on \\((a,b)\\), and \\(g(a)=g(b)\\), then there exists
\\(\xi\in(a,b)\\) such that \\(g^{\prime}(\xi)=0\\).

_Proof._ By the extreme value theorem,[^fn:4]  \\(g\\)
attains its minimum \\(m\\) and maximum \\(M\\) on \\([a,b]\\).  If \\(m=M\\),
then \\(g\\) is constant and any \\(\xi\in(a,b)\\) works.  Otherwise, since
\\(g(a)=g(b)\\), at least one extremum is attained at some
\\(\xi\in(a,b)\\); by Fermat, \\(g^{\prime}(\xi)=0\\).  \\(\square\\)

**Mean Value Theorem.**[^fn:5]  If
\\(f\\) is continuous on \\([a,b]\\) and differentiable on \\((a,b)\\), then
there exists \\(\xi\in(a,b)\\) such that

\\[
  f^{\prime}(\xi)=\frac{f(b)-f(a)}{b-a}.
\\]

_Proof._  Define

\\[
g(x)=f(a)+\frac{f(b)-f(a)}{b-a}(x-a),
\qquad
h(x)=f(x)-g(x).
\\]

Then \\(h\\) is continuous on \\([a,b]\\), differentiable on \\((a,b)\\), and
\\(h(a)=h(b)=0\\).  By Rolle theorem, there exists \\(\xi\in(a,b)\\) with
\\(h^{\prime}(\xi)=0\\), which gives

\\[
  f^{\prime}(\xi)-\frac{f(b)-f(a)}{b-a}=0.\\,\square
\\]


## Fundamental theorem of calculus {#fundamental-theorem-of-calculus}

We now have everything needed to prove the main result.

**Fundamental Theorem of Calculus.**[^fn:6]  Let
\\(f:[a,b]\to\mathbb{R}\\) be Riemann integrable and let
\\(F:[a,b]\to\mathbb{R}\\) satisfy \\(F^{\prime}=f\\) on \\((a,b)\\).  Then

\\[
\int\_a^b f = F(b)-F(a).
\\]

_Proof._  Fix a partition \\(\mathcal{P}=\\{x\_0,\ldots,x\_n\\}\\).  For
each \\([x\_{k-1},x\_k]\\), the mean value theorem applied to \\(F\\) gives
\\(z\_k\in(x\_{k-1},x\_k)\\) such that

\\[
F(x\_k)-F(x\_{k-1})=f(z\_k)\\,\Delta x\_k.
\\]

Since \\(m\_k\le f(z\_k)\le M\_k\\), we obtain

\\[
L(f,\mathcal{P})
\le
\sum\_{k=1}^{n}F(x\_k)-F(x\_{k-1}) = F(b)-F(a)
\le
U(f,\mathcal{P}).
\\]

Taking supremum and infimum over all partitions and using integrability,
we get

\\[
\int\_a^b f=F(b)-F(a).\\,\square
\\]

And there it is: an area problem that looked like it needed
infinitely many rectangles collapses to evaluating one function at two
points.[^fn:7]  The Lebesgue integral pushes
this idea further, but that is for another post.

[^fn:1]: Note that continuity is not required here;
    boundedness alone ensures the subinterval infima and suprema are
    finite.
[^fn:2]: Every continuous function
    on \\([a,b]\\) is Riemann integrable; so is every monotone function.  The
    exact characterization is Lebesgue's criterion: \\(f\\) is Riemann
    integrable iff it is bounded and continuous almost everywhere.
[^fn:3]: The local minimum case is
    identical, with all inequalities reversed.
[^fn:4]: Topological result: \\([a,b]\\)
    is compact (Heine-Borel), the continuous image of a compact set is
    compact, and compact subsets of \\(\mathbb{R}\\) are closed and bounded,
    so they contain their \\(\inf\\) and \\(\sup\\), which are finite.
[^fn:5]: Geometrically: there is always a point where
    the tangent line is parallel to the secant through the endpoints.
[^fn:6]: A broader formulation also
    includes the statement that \\(x\mapsto\int\_a^x f(t)\\,dt\\) is an
    antiderivative of \\(f\\) under suitable regularity assumptions.
[^fn:7]: For example, \\(\int\_0^1 x^2\\,dx = F(1)-F(0) = 1/3\\) with
    \\(F(x)=x^3/3\\).  No partitions needed.
