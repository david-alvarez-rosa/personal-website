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
`mutex`.  We'll build step by step a version that beats it by 7x in our
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
$ ./benchmark --benchmark_filter='V0>' --benchmark_min_time=100x
BM_SpinLock<SpinLockV0>/1/real_time       1.24 ms
BM_SpinLock<SpinLockV0>/2/real_time       4.18 ms
BM_SpinLock<SpinLockV0>/4/real_time       4.44 ms
```

One thread to two triples the time.  Two to four barely moves.[^fn:2]  The number to beat is **4.44
ms** at four threads.


## A naive spin-lock {#a-naive-spin-lock}

An atomic bool and an exchange loop.[^fn:3]

```cpp
class SpinLockV1 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void { while (locked_.exchange(true)); }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

At one thread it beats the baseline by 1.4x.  At two threads it is 1.7x
slower, at four **10.8 ms**, 2.4x slower, while burning CPU.

```sh
$ ./benchmark --benchmark_filter='V1>' --benchmark_min_time=100x
BM_SpinLock<SpinLockV1>/1/real_time      0.901 ms
BM_SpinLock<SpinLockV1>/2/real_time       6.95 ms
BM_SpinLock<SpinLockV1>/4/real_time       10.8 ms
```

The cause is cache coherence.  A core must own a cache line exclusively
to write it, so every waiter steals the line from the others.  One
thread misses 0.27% of its L1-d accesses.  Four threads miss 5.59%, and
one branch in nine is mispredicted.[^fn:4]

```sh
$ perf stat -d ./benchmark --benchmark_filter='V1>/1' --benchmark_min_time=100x
564,653,086      instructions           # 1.36  insn per cycle
    313,156      branch-misses          # 0.40% of all branches
    356,537      L1-dcache-load-misses  # 0.27% of all L1-dcache accesses

$ perf stat -d ./benchmark --benchmark_filter='V1>/4' --benchmark_min_time=100x
765,874,603      instructions           # 0.08  insn per cycle
 13,919,813      branch-misses          # 11.05% of all branches
 32,795,049      L1-dcache-load-misses  # 5.59% of all L1-dcache accesses
```

Spinning costs energy.[^fn:5]  At
four threads it draws **25.31 J** against the baseline's 17.58 J for the
same work.[^fn:6]

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V1>/4' --benchmark_min_time=100x
          25.31 Joules power/energy-pkg/
```


## Memory ordering {#memory-ordering}

The default is `seq_cst`, stronger than a lock needs.

```cpp
class SpinLockV2 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    while (locked_.exchange(true, std::memory_order_acquire));
  }
  auto unlock() noexcept -> void {
    locked_.store(false, std::memory_order_release);
  }
};
```

On x86 `lock` is unchanged.

```asm
SpinLockV2::lock():
        mov     al, 1
        xchg    byte ptr [rdi], al  // Locked exchange, both orderings
        test    al, 1
        jne     .LBB0_1
        ret
```

The difference is in `unlock`.  The default ordering pays for a second
locked read-modify-write, on top of the one already in `lock`

```asm
SpinLockV1::unlock():
        xor     eax, eax
        xchg    byte ptr [rdi], al  // Locked read-modify-write
        ret
```

while `memory_order_release` is a plain store

```asm
SpinLockV2::unlock():
        mov     byte ptr [rdi], 0   // Plain store
        ret
```

Halving the atomic operations per critical section halves the
uncontended time, 0.901 ms to **0.424 ms**.

```sh
$ ./benchmark --benchmark_filter='V2>' --benchmark_min_time=100x
BM_SpinLock<SpinLockV2>/1/real_time      0.424 ms
BM_SpinLock<SpinLockV2>/2/real_time       3.89 ms
BM_SpinLock<SpinLockV2>/4/real_time       8.51 ms
```

At four threads the instruction count barely moves, and both miss rates
fall: branches from 11.05% to 3.51%, L1-d from 5.59% to 3.18%.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V2>/4' --benchmark_min_time=100x
729,464,416      instructions           # 0.10  insn per cycle
  4,373,619      branch-misses          # 3.51% of all branches
 28,053,595      L1-dcache-load-misses  # 3.18% of all L1-dcache accesses
```

Four threads still need **8.51 ms**.  The exchange writes the line even
when it fails.  Waiters must stop writing.


## Backoff {#backoff}

Exchange once, then wait on a read-only load.  `pause`, exposed as
`_mm_pause`, marks the loop as a spin-wait, so the core idles.[^fn:7]

```cpp
class SpinLockV3 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    while (locked_.exchange(true, std::memory_order_acquire)) {
      while (locked_.load(std::memory_order_relaxed)) {  // Read-only spin
        _mm_pause();                                     // Backoff
      }
    }
  }
  auto unlock() noexcept -> void {
    locked_.store(false, std::memory_order_release);
  }
};
```

Four threads drop from 8.51 ms to **6.50 ms**.

```sh
$ ./benchmark --benchmark_filter='V3>' --benchmark_min_time=100x
BM_SpinLock<SpinLockV3>/1/real_time      0.425 ms
BM_SpinLock<SpinLockV3>/2/real_time       3.32 ms
BM_SpinLock<SpinLockV3>/4/real_time       6.50 ms
```

The gain is in the counters.  L1-d misses fall from 3.18% to 2.43%,
because the waiters no longer take the line exclusively on every failed
attempt.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V3>/4' --benchmark_min_time=100x
675,518,683      instructions           # 0.15  insn per cycle
  6,256,808      branch-misses          # 4.69% of all branches
 16,625,891      L1-dcache-load-misses  # 2.43% of all L1-dcache accesses
```

Energy barely moves, 22.17 J to **21.70 J**.  Every waiter pauses for the
same length of time, so they stay in lockstep and every release wakes
all of them at once.

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V3>/4' --benchmark_min_time=100x
          21.70 Joules power/energy-pkg/
```


## Exponential backoff {#exponential-backoff}

Intel documents the fix: when the lock is found busy, wait longer each
round, doubling up to a cap.[^fn:8]

```cpp
class SpinLockV4 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    auto mask = 64;
    while (locked_.exchange(true, std::memory_order_acquire)) {
      do {
        for (auto i = 0; i < mask; ++i) _mm_pause();      // Backoff
        mask = mask < 1024 ? mask << 1 : 1024;            // Exp. growth
      } while (locked_.load(std::memory_order_relaxed));  // Read-only
    }
  }
  auto unlock() noexcept -> void {
    locked_.store(false, std::memory_order_release);
  }
};
```

Two threads reach 0.484 ms and four **0.624 ms**, **7.1x** the baseline, on
**2.11 J** against its 17.58 J.

```sh
$ ./benchmark --benchmark_filter='V4>' --benchmark_min_time=100x
BM_SpinLock<SpinLockV4>/1/real_time      0.422 ms
BM_SpinLock<SpinLockV4>/2/real_time      0.484 ms
BM_SpinLock<SpinLockV4>/4/real_time      0.624 ms
```

Cache misses fall to 0.66% and mispredictions to 1.11%, both below the
mutex for the first time.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V4>/4' --benchmark_min_time=100x
542,494,570      instructions           # 0.87  insn per cycle
    930,440      branch-misses          # 1.11% of all branches
  1,207,860      L1-dcache-load-misses  # 0.66% of all L1-dcache accesses
```

Instructions drop from 676 to 542 million and the rate rises from 0.15
to 0.87 per cycle.  Fewer instructions at a higher rate means the run is
no longer dominated by waiting.

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V4>/4' --benchmark_min_time=100x
           2.11 Joules power/energy-pkg/
```

Energy falls to **2.11 J**, an eighth of the baseline's 17.58 J for the
same work.


## Summary {#summary}

Reproduce it with the [benchmark](https://github.com/david-alvarez-rosa/CppPlayground/blob/main/dsa/spin_lock.cpp).

| Version | 1 thread | 2 threads    | 4 threads                 | Notes               |
|---------|----------|--------------|---------------------------|---------------------|
| V0      | 1.24 ms  | 4.18 ms      | 4.44 ms / 17.58 J         | Baseline (mutex)    |
| V1      | 0.901 ms | 6.95 ms      | 10.8 ms / 25.31 J         | Naive               |
| V2      | 0.424 ms | 3.89 ms      | 8.51 ms / 22.17 J         | Memory order        |
| V3      | 0.425 ms | 3.32 ms      | 6.50 ms / 21.70 J         | Backoff             |
| **V4**  | 0.422 ms | **0.484 ms** | **0.624 ms** / **2.11 J** | Exponential backoff |

In generic code, `std::mutex` remains the right default.  Reach for a
spin-lock only when the threads have dedicated cores,[^fn:9] and you have measured the difference.

[^fn:1]: Run on a box [tuned for benchmarking](/posts/tuning-a-server-for-benchmarking/) (AMD Ryzen 7 PRO 8700GE,
    8 cores at 3.65 GHz).  Built with `clang`.  All optimizations enabled.
[^fn:2]: A
    contended `std::mutex` parks waiters in the kernel and wakes them one at
    a time, so the damage does not compound.
[^fn:3]: `exchange` atomically writes
    `true` and returns the previous value.  `false` means the lock was free
    and is now ours.  `true` means someone else holds it, so we retry.
[^fn:4]: Whether the exchange succeeds is
    decided by the other cores, so the branch predictor has nothing to
    learn.
[^fn:5]: High-frequency trading shops care about it.
    [Exchange colocation services](https://www.nyse.com/technology/colo) bill power, and NYSE [caps](https://www.federalregister.gov/documents/2023/11/20/2023-25548/self-regulatory-organizations-new-york-stock-exchange-llc-nyse-american-llc-nysearca-inc-nyse) at 32 kW.
[^fn:6]: Reading the RAPL counters requires system-wide mode
    (`-a`) and root, so the figure includes idle cores.
[^fn:7]: The
    load can be `relaxed`: what orders the critical section is the
    `exchange` that succeeds, not the reads that fail.
[^fn:8]: Example 2-10, _Contended Locks with
    Increasing Back-off_, in the [Intel Optimization Reference Manual](https://cdrdv2.intel.com/v1/dl/getContent/671488) (PDF,
    248966-050US).  Intel's ramp starts at one `pause` and re-checks before
    backing off.
[^fn:9]: The standard
    library cannot assume that.  A spin-lock waits for a holder the
    scheduler may have descheduled, so `std::mutex` parks the waiters and
    hands the core back instead.
