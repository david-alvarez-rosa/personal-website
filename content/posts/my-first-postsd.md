+++
title = "Beyond Big-O: Why Constant Factors Still Matter"
author = ["David Álvarez Rosa"]
date = 2025-12-01T18:52:00+00:00
tags = ["software"]
draft = false
+++

When we talk about algorithmic efficiency, the conversation almost
always revolves around Big-O notation. We proudly say our algorithm is
O(n log n) instead of O(n²) and call it a day. But in real-world
systems, the story doesn’t end there. Two algorithms with the _same_
asymptotic complexity can differ by an order of magnitude in
performance—and those “pesky constant factors” we hand-wave away in
theory suddenly matter a lot.

```cpp
auto main() -> int {
  return 0;
}
```

In production code, constants show up everywhere: cache misses, branch mispredictions, allocations, lock contention, system calls, network round trips, and more. Each one adds a fixed cost that doesn’t change your Big-O, but absolutely changes your latency and throughput. An O(n) solution that touches memory in the wrong pattern can be slower than an O(n log n) one that plays nicely with hardware. Likewise, a “clean” but allocation-heavy design might fall apart under load compared to a more careful implementation with the same complexity on paper.
