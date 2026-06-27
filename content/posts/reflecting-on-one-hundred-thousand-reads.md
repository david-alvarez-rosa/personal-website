+++
title = "Reflecting on One Hundred Thousand Reads"
author = ["David Álvarez Rosa"]
tags = ["blog", "meta"]
draft = true
subtitle = "A thank-you, and where the reads come from."
+++

This site just passed one hundred thousand reads.  It began as a
notebook kept in public, read by almost no one, and the idea hasn't
changed: one well-made post a month, not quick writeups---only deep
dives into things that genuinely interest me.  So to everyone who has
read one: thank you; that is what matters, the rest is just numbers.

The numbers are uneven.  Visitors tend to stay---five and a half minutes
on average---but where they come from is the surprise: Reddit and Hacker
News send nearly 90% of readers, every search engine under 5%.

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

And that is fragile.  This traffic spikes, then fades---a post hits a
front page, pulls a few thousand reads in a day, then goes quiet; the
three most-read owe their 40,000-odd reads to a few such days, not
steady interest.[^fn:1] Search is the
opposite: tiny now, but it grows over time, so the deeper posts are
being set up to show up there---and each new one is syndicated more
widely.

Thank you, again, for reading.  [Subscribe](/#subscribe) or follow by [RSS](/index.xml), and feedback
is always welcome---[get in touch](/about/#contact).

[^fn:1]: [Optimizing a Lock-Free Ring Buffer](/posts/optimizing-a-lock-free-ring-buffer/) leads with
    16,635 reads, followed by the [Fundamental Theorem of Calculus](/posts/fundamental-theorem-of-calculus/) (12,506)
    and [Devirtualization and Static Polymorphism](/posts/devirtualization-and-static-polymorphism/) (11,848).
