+++
title = "Optimizing a Spin-Lock"
author = ["David Álvarez Rosa"]
tags = ["blog", "cpp", "performance"]
draft = true
subtitle = "Squeezing every pico out of the simplest lock."
+++

A spin-lock is a mutex that never sleeps.  Instead of yielding to the
scheduler, the thread stays on the CPU and spins.  No syscalls.  No
context switches.  The naive implementation loses against standard
`mutex`.  We'll build step by step a version that beats it by 3x in our
benchmark with dedicated cores per thread.


## Benchmark &amp; baseline {#benchmark-and-baseline}

Multiple threads increment 250,000 times a shared counter.  The amount
of work is fixed.  A perfect lock would keep the time flat as threads
are added, so any growth is synchronization overhead.  Threads are
pinned.[^fn:1]

```cpp
template <typename SpinLock>
auto BM_SpinLock(benchmark::State& state) -> void {
  const auto num_threads = state.range(0);

  auto spin_lock = SpinLock{};
  auto val = std::uint64_t{};
  auto threads = std::vector<std::thread>{};
  threads.reserve(num_threads);

  for (auto _ : state) {
    for (auto i = 0U; i < num_threads; ++i) {
      threads.emplace_back([&, i] {
        pinThread(i);
        for (auto j = 0U; j < 250'000U / num_threads; ++j) {
          spin_lock.lock();
          ++val;
          spin_lock.unlock();
        }
      });
    }
    for (auto& thread : threads) thread.join();
    benchmark::DoNotOptimize(val);
    threads.clear();
  }
}
```

The baseline wraps standard `mutex`.

```sh
$ ./benchmark --benchmark_filter='V0>' --benchmark_min_time=200x
BM_SpinLock<SpinLockV0>/1/real_time       1.24 ms
BM_SpinLock<SpinLockV0>/2/real_time       4.18 ms
BM_SpinLock<SpinLockV0>/4/real_time       4.44 ms
```

One thread to two triples the time.  Two to four barely moves.[^fn:2]  The number to beat is **4.44
ms** at four threads.


## A basic spin-lock {#a-basic-spin-lock}

An atomic bool and an exchange loop.[^fn:3]

```cpp
class SpinLockV1 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void { while (locked_.exchange(true)); }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

At one thread it beats the baseline by 1.3x.[^fn:4]  At two threads it
is 1.7x slower, at four **9.56 ms**, 2.2x slower, while burning CPU.

```sh
$ ./benchmark --benchmark_filter='V1>' --benchmark_min_time=200x
BM_SpinLock<SpinLockV1>/1/real_time      0.969 ms
BM_SpinLock<SpinLockV1>/2/real_time       7.10 ms
BM_SpinLock<SpinLockV1>/4/real_time       9.56 ms
```

The cause is cache coherence.  A core must own a cache line exclusively
to write it, so every waiter steals the line from the others.  One
thread misses 0.28% of its L1-d accesses.  Four threads miss 7.93%, and
one in ten branches is mispredicted.[^fn:5]

```sh
$ perf stat -d ./benchmark --benchmark_filter='V1>/1' --benchmark_min_time=200x
1,119,891,388      instructions           # 1.32  insn per cycle
      828,093      branch-misses          # 0.53% of all branches
      719,501      L1-dcache-load-misses  # 0.28% of all L1-dcache accesses

$ perf stat -d ./benchmark --benchmark_filter='V1>/4' --benchmark_min_time=200x
1,999,113,707      instructions           # 0.05  insn per cycle
   34,676,115      branch-misses          # 9.98% of all branches
  101,662,950      L1-dcache-load-misses  # 7.93% of all L1-dcache accesses
```

Spinning costs energy.[^fn:6]  At
four threads it draws **49.23 J** against the baseline's 32.98 J for the
same work.[^fn:7]

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V1>/4' --benchmark_min_time=200x
          49.23 Joules power/energy-pkg/
```

Both problems come from the same instruction.  The exchange writes the
line even when it fails.  Waiters must stop writing.


## Active backoff {#active-backoff}

Exchange once, then spin on a read-only load with
backoff.[^fn:8]

```cpp
class SpinLockV2 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    while (true) {
      if (!locked_.exchange(true)) return;
      do {
        for (volatile auto i = 0; i < 150; ++i);  // Active backoff
      } while (locked_.load());                   // Read-only
    }
  }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

Two threads drop to 3.36 ms, ahead of the baseline.  Four threads reach
**7.97 ms**, 1.8x behind.

```sh
$ ./benchmark --benchmark_filter='V2>' --benchmark_min_time=200x
BM_SpinLock<SpinLockV2>/1/real_time      0.964 ms
BM_SpinLock<SpinLockV2>/2/real_time       3.36 ms
BM_SpinLock<SpinLockV2>/4/real_time       7.97 ms
```

L1-d misses at four threads fall from 7.93% to 6.28%.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V2>/4' --benchmark_min_time=200x
28,319,952,728      instructions           # 0.92  insn per cycle
    38,335,855      branch-misses          # 0.55% of all branches
    86,760,836      L1-dcache-load-misses  # 6.28% of all L1-dcache accesses
```

Instructions jump from 2 to 28 billion for the same work.[^fn:9]  The extra ones increment the
`volatile` counter.

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V2>/4' --benchmark_min_time=200x
          52.97 Joules power/energy-pkg/
```

The backoff draws **52.97 J**, the worst of the five versions.  The wait
has to be idle, not busy.


## Passive backoff {#passive-backoff}

There is an x86 instruction for this, `pause`, exposed as
`_mm_pause`.[^fn:10]  It marks the loop as a spin-wait, so the core idles.

```cpp
class SpinLockV3 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    while (true) {
      if (!locked_.exchange(true)) return;
      do {
        for (auto i = 0; i < 4; ++i) _mm_pause();  // Passive backoff
      } while (locked_.load());                    // Read-only
    }
  }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

Four `pause` calls replace the 150-iteration loop.  The times barely
move, 3.60 ms at two threads and **7.13 ms** at four.

```sh
$ ./benchmark --benchmark_filter='V3>' --benchmark_min_time=200x
BM_SpinLock<SpinLockV3>/1/real_time      0.970 ms
BM_SpinLock<SpinLockV3>/2/real_time       3.60 ms
BM_SpinLock<SpinLockV3>/4/real_time       7.13 ms
```

The gain is in the counters.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V3>/4' --benchmark_min_time=200x
1,582,977,444      instructions           # 0.07  insn per cycle
   21,412,494      branch-misses          # 8.81% of all branches
   67,943,161      L1-dcache-load-misses  # 5.14% of all L1-dcache accesses
```

The loop now runs 1.58 billion instructions, 18x fewer than active
backoff, at 0.07 instructions per cycle.  That is a core deliberately
doing nothing.

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V3>/4' --benchmark_min_time=200x
          35.45 Joules power/energy-pkg/
```

Energy falls from 52.97 J to **35.45 J**, the baseline's level.  The wait
is cheap, but it is the same wait for every thread.  A constant delay
keeps the waiters in lockstep, so each release wakes all of them, and
8.81% of branches still miss.


## Exponential backoff {#exponential-backoff}

Let each thread double its delay instead, up to a cap.[^fn:11]

```cpp
class SpinLockV4 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    auto iters = 4;
    while (true) {
      if (!locked_.exchange(true)) return;
      do {
        for (auto i = 0; i < iters; ++i) _mm_pause();  // Passive backoff
        iters = std::min(iters << 1, 1024);            // Exponential growth
      } while (locked_.load());                        // Read-only
    }
  }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

The times collapse to 1.12 ms at two threads and **1.47 ms** at four.

```sh
$ ./benchmark --benchmark_filter='V4>' --benchmark_min_time=200x
BM_SpinLock<SpinLockV4>/1/real_time      0.965 ms
BM_SpinLock<SpinLockV4>/2/real_time       1.12 ms
BM_SpinLock<SpinLockV4>/4/real_time       1.47 ms
```

That is **3x** faster than the baseline.  Cache misses fall to 1.93% and
mispredictions to 2.15%.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V4>/4' --benchmark_min_time=200x
1,226,803,007      instructions           # 0.24  insn per cycle
    4,048,354      branch-misses          # 2.15% of all branches
    8,288,327      L1-dcache-load-misses  # 1.93% of all L1-dcache accesses
```

The instruction count is 1.23 billion, the lowest of the four
spin-locks.

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V4>/4' --benchmark_min_time=200x
           9.09 Joules power/energy-pkg/
```

It draws **9.09 J**, 3.6x below the baseline and 5.8x below active
backoff.


## Summary {#summary}

Reproduce it with the [benchmark](https://github.com/david-alvarez-rosa/CppPlayground/blob/main/dsa/spin_lock.cpp).

| Version | 1 thread | 2 threads   | 4 threads                |
|---------|----------|-------------|--------------------------|
| V0      | 1.24 ms  | 4.18 ms     | 4.44 ms / 32.98 J        |
| V1      | 0.969 ms | 7.10 ms     | 9.56 ms / 49.23 J        |
| V2      | 0.964 ms | 3.36 ms     | 7.97 ms / 52.97 J        |
| V3      | 0.970 ms | 3.60 ms     | 7.13 ms / 35.45 J        |
| **V4**  | 0.965 ms | **1.12 ms** | **1.47 ms** / **9.09 J** |

In generic code, `std::mutex` remains the right default.  Reach for a
spin-lock only when the threads have dedicated cores,[^fn:12] and you have measured the difference.

[^fn:1]: Run on a box [tuned for benchmarking](/posts/tuning-a-server-for-benchmarking/) (AMD Ryzen 7 PRO 8700GE,
    8 cores at 3.65 GHz).  Built with `clang`.  All optimizations enabled.
[^fn:2]: A
    contended `std::mutex` parks waiters in the kernel and wakes them one at
    a time, so the damage does not compound.
[^fn:3]: `exchange` atomically writes
    `true` and returns the previous value.  `false` means the lock was free
    and is now ours.  `true` means someone else holds it, so we retry.
[^fn:4]: Locking is now one
    atomic instruction, with no library code around it.
[^fn:5]: Whether the exchange succeeds
    is decided by the other cores, so the branch predictor has nothing to
    learn.
[^fn:6]: High-frequency trading shops care about it.
    [Exchange colocation services](https://www.nyse.com/technology/colo) bill power, and NYSE [caps](https://www.federalregister.gov/documents/2023/11/20/2023-25548/self-regulatory-organizations-new-york-stock-exchange-llc-nyse-american-llc-nysearca-inc-nyse) at 32 kW.
[^fn:7]: Reading the RAPL counters requires system-wide mode
    (`-a`) and root, so the figure includes idle cores.
[^fn:8]: `volatile` keeps the compiler from deleting the empty loop.
    The 150 iterations are tuned experimentally.
[^fn:9]: The delay
    loop also floods the branch counter with predictable branches, which is
    why the miss rate collapses to 0.55%.  The contended exchange is still
    unpredictable, just outnumbered.
[^fn:10]: Declared in `<xmmintrin.h>`, which `<immintrin.h>`
    pulls in.
[^fn:11]: Without the
    cap, threads would pause long after the lock has become free.  Both
    hard-coded bounds, 4 and 1024, are tunable.
[^fn:12]: The standard
    library cannot assume that.  A spin-lock waits for a holder the
    scheduler may have descheduled, so `std::mutex` parks the waiters and
    hands the core back instead.
