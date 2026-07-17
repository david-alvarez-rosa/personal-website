+++
title = "One Hundred Thousand Reads"
author = ["David Álvarez Rosa"]
tags = ["blog", "meta"]
draft = true
subtitle = "A thank you, and a look at the numbers."
+++

This site just passed one hundred thousand reads.  It started as a
public notebook that almost no one read.  The plan hasn't changed: one
post a month, no quick takes, only deep dives into things I care about.
If you have read even one, thank you.  That number is you.

Visitors stay five and a half minutes on average.  Reddit and Hacker
News send almost nine in ten of them; every search engine put together
sends fewer than one in twenty.

<figure class="sources">
  <figcaption><p><strong>Two sites, most of it.</strong>  Reddit and Hacker News send nearly nine in ten readers; every search engine put together sends under one in twenty.</p></figcaption>
  <div class="chart">
    <div class="plot" role="img" aria-label="Bar chart of traffic sources. Reddit 69 percent, Hacker News 20 percent, search engines under 5 percent, and others 6 percent.">
      <div class="row"><span class="name">reddit</span><span class="track"><span class="bar" style="width:69%"></span><span class="val" data-egg>69%</span></span></div>
      <div class="row"><span class="name">hacker news</span><span class="track"><span class="bar" style="width:20%"></span><span class="val">20%</span></span></div>
      <div class="row"><span class="name">search engines</span><span class="track"><span class="bar" style="width:4%"></span><span class="val">&lt;5%</span></span></div>
      <div class="row"><span class="name">others</span><span class="track"><span class="bar" style="width:6%"></span><span class="val">6%</span></span></div>
    </div>
  </div>
</figure>
<style>
  figure.sources { color: var(--text); margin: 0 0 1.4rem; }
  figure.sources .chart {
    border: 2px solid var(--text); padding: 0.5rem 0.8rem; box-sizing: border-box;
  }
  figure.sources .plot {
    max-width: 34rem; margin: 0 auto;
    display: grid; grid-template-columns: max-content 1fr; align-items: stretch;
  }
  figure.sources .row { display: contents; }
  figure.sources .name {
    font-family: var(--font-sc); font-size: 1.25rem; white-space: nowrap;
    display: flex; align-items: center; justify-content: flex-end;
    padding-right: 0.8rem;
  }
  figure.sources .track {
    display: flex; align-items: center;
    border-left: 2px solid var(--text); padding: 0.5rem 0;
  }
  figure.sources .bar {
    flex: none; height: 1.4rem; box-sizing: border-box;
    border: 1.5px solid var(--text);
    background: repeating-linear-gradient(45deg, var(--text) 0, var(--text) 1.6px, transparent 1.6px, transparent 7px);
  }
  figure.sources .val {
    font-family: var(--font-body); font-size: 1.25rem;
    margin-left: 0.6rem; white-space: nowrap;
  }
  figure.sources .val[data-egg] { position: relative; }
  figure.sources .val[data-egg]::after {
    content: "if you know, you know";
    position: absolute; top: 100%; right: 0; margin-top: 0.1rem;
    white-space: nowrap; font-style: italic; font-size: 1rem; color: var(--accent);
    opacity: 0; transition: opacity 0.25s ease; pointer-events: none;
  }
  figure.sources .val[data-egg]:hover::after { opacity: 1; }
  @media (min-width: 861px) {
    figure.sources figcaption { padding-top: 0; margin-top: -0.3rem; }
  }
</style>

The traffic comes in spikes: a post hits a front page, pulls a few
thousand reads in a day, then goes quiet.  The three most-read posts owe
over 41,000 reads to a few such days.[^fn:1]

Thank you, again, for reading.

[^fn:1]: [Optimizing a Lock-Free Ring
    Buffer](/posts/optimizing-a-lock-free-ring-buffer/) leads with 17,169 reads, followed by the [Fundamental Theorem of
    Calculus](/posts/fundamental-theorem-of-calculus/) (12,575) and [Devirtualization and Static Polymorphism](/posts/devirtualization-and-static-polymorphism/)
    (11,961).
