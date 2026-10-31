+++
title = "Deriving Black-Scholes"
author = ["David Álvarez Rosa"]
tags = ["blog", "backlog"]
draft = true
latex = true
subtitle = "Option price from the no-arbitrage hypothesis."
+++

Even if it feels unintuitive, the price of an option does not depend on
whether the underlying moons or tanks.  Under some reasonable market
hypotheses, we can build a fully hedged portfolio by holding the
underlying and shorting the option.  Since it is riskless, by
no-arbitrage assumption, it must return the same as the risk-free
interest rate.  That allows to put together an equation (the
Black-Scholes equation), and solve for the option price (the
Black-Scholes formula).


## Model {#model}

A European call gives the right, but not the obligation, to buy an
underlying at a fixed price \\(K\\!\\), the strike, on a fixed date
\\(T\\!\\), the maturity.  Its value \\(V\\!\\) is the premium you need to pay
for having that optionality, and it depends on time and on the
underlying stock price \\(S\\!\\).  At maturity it is the payoff[^fn:1]

\\[
  V(T,S)=\max(S-K,0).
\\]

Stock moves scale with price, so it is common to model the returns
instead of the price.  Over a short time the return is a small drift
plus noise.  The noise is the sum of many small,
unpredictable[^fn:2] sources.  Such sum can be modeled as a
Brownian motion \\(W\\!\\),[^fn:3] so the return is

\\[
  \frac{dS}{S}=\mu\\,dt+\sigma\\,dW\\!,
\\]

with drift \\(\mu\\) and volatility \\(\sigma\\!\\).  For our model, we will
assume constant drift, volatility and interest rate \\(r\\!\\).  We will also
assume "perfect" trading, with fractional shares allowed, no fees, no
taxes, no dividends, and no arbitrage.


## Hedged portfolio {#hedged-portfolio}

The trick of Black-Scholes is to build a fully hedged portfolio
combining the option and the underlying in the right proportion.  By the
no-arbitrage hypothesis, its returns must be the same as the interest
rate \\(r\\!\\), and that way we can put together an equation.

When the stock moves a little, the option moves by \\(\Delta\\) times as
much, with

\\[
  \Delta=\frac{\partial V}{\partial S}.
\\]

If you short one option and hold \\(\Delta\\) shares, then the two moves
cancel.  This is a delta hedge.

<figure class="tree">
  <figcaption><p><strong>One step.</strong>  From today (left) to
  maturity (right), the stock moves from $100 to $110 or $90, and a call at $100 pays $10 or $0.  Half a share
  minus the call is worth $45 either way, so it is riskless.  Without
  interest it is also worth $45 today, so the call costs
  $50&#160;&#8722;&#160;$45&#160;=&#160;$5.</p></figcaption>
  <div class="chart">
    <svg viewBox="-8.5 2.4 376 224" role="img" aria-label="One-step tree from today to maturity.
         Today the stock is $100, the call $5, and the hedge $45.  At
         maturity the stock is either $110, with the call $10 and the hedge
         $45, or $90, with the call $0 and the hedge $45.">
      <line class="branch" x1="122" y1="113" x2="242" y2="54"/>
      <line class="branch" x1="122" y1="113" x2="242" y2="172"/>
      <g transform="translate(70 113)">
        <text class="name" x="-66" y="-24">stock</text><text class="val" x="8" y="-24">$100</text>
        <text class="name" x="-66" y="6">call</text><text class="val key" x="8" y="6">$5</text>
        <text class="name" x="-66" y="36">hedge</text><text class="val" x="8" y="36">$45</text>
      </g>
      <g transform="translate(320 50)">
        <text class="name" x="-66" y="-24">stock</text><text class="val" x="8" y="-24">$110</text>
        <text class="name" x="-66" y="6">call</text><text class="val" x="8" y="6">$10</text>
        <text class="name" x="-66" y="36">hedge</text><text class="val key" x="8" y="36">$45</text>
      </g>
      <g transform="translate(320 176)">
        <text class="name" x="-66" y="-24">stock</text><text class="val" x="8" y="-24">$90</text>
        <text class="name" x="-66" y="6">call</text><text class="val" x="8" y="6">$0</text>
        <text class="name" x="-66" y="36">hedge</text><text class="val key" x="8" y="36">$45</text>
      </g>
    </svg>
  </div>
</figure>
<style>
  figure.tree { color: var(--text); margin: 0 0 1.4rem; }
  figure.tree .chart {
    border: 2px solid var(--text); padding: 0.5rem 0.8rem; box-sizing: border-box;
  }
  figure.tree svg { display: block; width: 100%; max-width: 26rem; height: auto; margin: 0 auto; }
  figure.tree .branch { stroke: currentColor; stroke-width: 2; }
  figure.tree .name { fill: var(--text); font-family: var(--font-sc); font-size: 1.35rem; }
  figure.tree .val { fill: var(--text); font-family: var(--font-body); font-size: 1.5rem; }
  figure.tree .val.key { font-weight: 700; }
  @media (min-width: 861px) {
    figure.tree figcaption { padding-top: 0; margin-top: -0.3rem; }
    figure.tree svg { position: relative; left: -15px; }
  }
</style>

Note that \\(\Delta\\) changes with the stock price and time.  The
portfolio is riskless only over an instant, and stays so only if it is
rebalanced continuously.


## Black-Scholes equation {#black-scholes-equation}

Use the Taylor expansion of the option value and keep the terms up to
order \\(dt\\).  Since \\((dW)^2=dt\\), the \\((dS)^2\\) term is one of them,

\\[
  dV=\frac{\partial V}{\partial t}dt+\frac{\partial V}{\partial S}dS
  +\frac{1}{2}\frac{\partial^2V}{\partial S^2}(dS)^2.
\\]

Substituting the stock model gives

\\[
  dV=\left(\mu S\frac{\partial V}{\partial S}+\frac{\partial V}{\partial t}
  +\frac{1}{2}\sigma^2S^2\frac{\partial^2V}{\partial S^2}\right)dt
  +\sigma S\frac{\partial V}{\partial S}\\,dW.
\\]

Now, we can use the same hedged portfolio \\(\Pi\\) as before, short one
option and long \\(\Delta\\) shares, which is worth \\(-V+\Delta S\\!\\).
Holding \\(\Delta\\) fixed over an instant,

\\[
  d\Pi=-dV+\Delta dS,
\\]

and substituting \\(dS\\) and \\(dV\\!\\) gives

\\[
  d\Pi=\left(-\frac{\partial V}{\partial t}
  -\frac{1}{2}\sigma^2S^2\frac{\partial^2V}{\partial S^2}\right)dt.
\\]

See how noise and drift cancel.  No \\(dW\\!\\) term is left, and
everything else is known at time \\(t\\), so over the next instant the
portfolio change is fixed.[^fn:4]  We've built a riskless portfolio, that
by the no-arbitrage assumption, it must earn the risk-free rate,
\\(d\Pi=r\Pi\\,dt\\).  Equating both expressions gives us the Black-Scholes
equation

\\[
  \frac{\partial V}{\partial t}+rS\frac{\partial V}{\partial S}
  +\frac{1}{2}\sigma^2S^2\frac{\partial^2V}{\partial S^2}=rV.
\\]

Lovely, uh?


## Black-Scholes formula {#black-scholes-formula}

I won't bore you with the technical details,[^fn:5] but the above equation can be
solved by a change of variables to turn it into the heat equation, whose
solution is known.  Define

\\[
  \tau=T-t,\quad
  x=\ln S+\left(r-\frac{1}{2}\sigma^2\right)\tau,\quad
  V(t,S)=e^{-r\tau}w(\tau,x),
\\]

where \\(w\\) is the option value at maturity.  Substituting, all terms
but two cancel, and we get the heat equation

\\[
  \frac{\partial w}{\partial\tau}=\frac{1}{2}\sigma^2\frac{\partial^2w}{\partial x^2}.
\\]

With the payoff as initial condition, the solution is

\\[
  w(\tau,x)=\frac{1}{\sqrt{2\pi\sigma^2\tau}}\int\_{-\infty}^{\infty}
  \max(e^y-K,0)\\,\exp\left(-\frac{(y-x)^2}{2\sigma^2\tau}\right)dy.
\\]

Completing the squares gives the Black-Scholes formula

\\[
  V(t,S)=S\\,N(d\_1)-Ke^{-r\tau}N(d\_2),
\\]

where \\(N\\) is the standard normal CDF and

\\[
  d\_1=\frac{\ln(S/K)+\left(r+\frac{1}{2}\sigma^2\right)\tau}{\sigma\sqrt{\tau}},
  \qquad
  d\_2=d\_1-\sigma\sqrt{\tau}.
\\]

-   \\(N(d\_1)\\) is the hedge ratio \\(\Delta\\), so the first term represents
    the price of an asset-or-nothing call, which pays the share if the
    call ends in the money.
-   \\(N(d\_2)\\) is the risk-neutral probability of exercise, so the second
    term is the present value of the expected strike payment.

<br />

That's all, lads, go make Ken Griffin broke.

[^fn:1]: For
    example, pay $5 today for the right to buy one AMZN share for $200 in a
    month.  If AMZN then trades at $230, you buy at $200 and the option is
    worth $30.  If it trades at $180, you walk away and the option is worth
    nothing.
[^fn:2]: Weak-form efficient market hypothesis: past prices do
    not predict future moves.
[^fn:3]: By the central limit theorem, the sum of
    many small independent variables is normal.
[^fn:4]: A known return with no randomness is
    precisely what riskless means.
[^fn:5]: For the details, see
    John C. Hull, _Options, Futures, and Other Derivatives_, 11th edition,
    Pearson, 2021.  The Bible of derivatives.
