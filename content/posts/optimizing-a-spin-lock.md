+++
title = "Optimizing a Spin-Lock"
author = ["David Álvarez Rosa"]
date = 2026-08-27T14:23:00+01:00
tags = ["blog", "cpp", "performance"]
draft = false
subtitle = "Squeezing every pico out of the simplest lock."
+++

A spin-lock is a lock that never sleeps.  Instead of yielding to the
scheduler, the thread stays on the CPU and _spins_.  No syscalls.  No
context switches.  In this post, we'll build a version, step by step,
that is 5.7x faster while drawing 5.4x less energy.


## Benchmark {#benchmark}

Threads increment a shared counter under the lock.[^fn:1]

```cpp
template <typename Lockable>
auto BM_SpinLock(benchmark::State& state) -> void {
  alignas(std::hardware_destructive_interference_size) static auto lockable =
      Lockable{};
  alignas(std::hardware_destructive_interference_size) static auto counter =
      std::uint64_t{};

  pinThread(state.thread_index());
  for (auto _ : state) {
    lockable.lock();
    ++counter;
    lockable.unlock();
  }
  benchmark::DoNotOptimize(counter);
}
```

The lock and the counter get a cache line each.  Threads are pinned.


## A naive spin-lock {#a-naive-spin-lock}

An atomic bool and an exchange loop.[^fn:2]

```cpp
class SpinLockV1 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void { while (locked_.exchange(true)); }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

Uncontended it takes 3.14 ns.  Two threads take 61.5 ns, twenty times as
long.  Four take **246 ns**.

```sh
$ ./benchmark --benchmark_filter='V1>'
BM_SpinLock<SpinLockV1>/real_time/threads:1      3.14 ns
BM_SpinLock<SpinLockV1>/real_time/threads:2      61.5 ns
BM_SpinLock<SpinLockV1>/real_time/threads:4       246 ns
```

A core must own the line exclusively to write it, so waiters take it
from each other.  L1-d misses go from 1.27% at one thread to 61.73% at
four, and one branch in eight is mispredicted.[^fn:3]

```sh
$ perf stat -d ./benchmark --benchmark_filter='V1>.*threads:1'
1,638,619,370      instructions           # 0.51  insn per cycle
      244,253      branch-misses          # 0.11% of all branches
       75,519      L1-dcache-load-misses  # 1.27% of all L1-dcache accesses

$ perf stat -d ./benchmark --benchmark_filter='V1>.*threads:4'
1,231,495,723      instructions           # 0.02  insn per cycle
   33,824,516      branch-misses          # 12.52% of all branches
  208,756,315      L1-dcache-load-misses  # 61.73% of all L1-dcache accesses
```

Spinning costs energy.[^fn:4]  At
four threads it draws **64.92 J**.[^fn:5]

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V1>.*threads:4'
          64.92 Joules power/energy-pkg/
```


## Memory ordering {#memory-ordering}

The default is `seq_cst`, stronger than a lock needs.  It only has to
`acquire` on the way in and `release` on the way out.

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

The difference is in `unlock`.  The default ordering adds a second
locked read-modify-write, on top of the one in `lock`.

```asm
SpinLockV1::unlock():
        xor     eax, eax
        xchg    byte ptr [rdi], al  // Locked read-modify-write
        ret
```

With `memory_order_release`, `unlock` is a plain store.

```asm
SpinLockV2::unlock():
        mov     byte ptr [rdi], 0   // Plain store
        ret
```

One atomic instead of two.  3.14 ns to 1.57 ns uncontended, 246 ns to
**131 ns** at four threads.

```sh
$ ./benchmark --benchmark_filter='V2>'
BM_SpinLock<SpinLockV2>/real_time/threads:1      1.57 ns
BM_SpinLock<SpinLockV2>/real_time/threads:2      32.5 ns
BM_SpinLock<SpinLockV2>/real_time/threads:4       131 ns
```

Miss rates fall too.  L1-d 61.73% to 21.16%, branches 12.52% to 7.43%.
Energy drops to **34.45 J**.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V2>.*threads:4'
773,887,322      instructions           # 0.03  insn per cycle
 12,348,239      branch-misses          # 7.43% of all branches
 99,804,390      L1-dcache-load-misses  # 21.16% of all L1-dcache accesses
```

The exchange writes the line even when it fails.  Waiters must stop
writing.


## Test and test-and-set {#test-and-test-and-set}

Exchange once, then wait on a read-only load.  The `_mm_pause`
instruction marks the loop as a spin-wait, so the core idles.[^fn:6]

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

Two threads drop by a third, 32.5 ns to 21.3 ns.  Four threads gain 8%,
131 ns to **120 ns**.

```sh
$ ./benchmark --benchmark_filter='V3>'
BM_SpinLock<SpinLockV3>/real_time/threads:1      1.58 ns
BM_SpinLock<SpinLockV3>/real_time/threads:2      21.3 ns
BM_SpinLock<SpinLockV3>/real_time/threads:4       120 ns
```

L1-d misses fall from 21.16% to 17.31%, branches from 7.43% to 3.72%.  A
read-only spin is predictable.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V3>.*threads:4'
1,290,214,448      instructions           # 0.05  insn per cycle
   12,089,906      branch-misses          # 3.72% of all branches
   83,836,255      L1-dcache-load-misses  # 17.31% of all L1-dcache accesses
```

Energy falls 10%, from 34.45 J to **30.97 J**.

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V3>.*threads:4'
          30.97 Joules power/energy-pkg/
```

Every waiter pauses for the same length of time, so they all wake
together.


## Exponential backoff {#exponential-backoff}

Intel documents the fix.  Wait longer each round, doubling up to a
cap.[^fn:7]

```cpp
class SpinLockV4 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    auto backoff = 1;
    while (locked_.exchange(true, std::memory_order_acquire)) {
      do {
        for (auto i = 0; i < backoff; ++i) _mm_pause();   // Backoff
        backoff = backoff < 64 ? backoff << 1 : 64;       // Exp. growth
      } while (locked_.load(std::memory_order_relaxed));  // Read-only spin
    }
  }
  auto unlock() noexcept -> void {
    locked_.store(false, std::memory_order_release);
  }
};
```

Waiters back off by different amounts and stop waking together.  Four
threads drop from 120 ns to **43.0 ns**.

```sh
$ ./benchmark --benchmark_filter='V4>'
BM_SpinLock<SpinLockV4>/real_time/threads:1      1.58 ns
BM_SpinLock<SpinLockV4>/real_time/threads:2      18.3 ns
BM_SpinLock<SpinLockV4>/real_time/threads:4      43.0 ns
```

L1-d misses fall from 17.31% to 12.88%.

```sh
$ perf stat -d ./benchmark --benchmark_filter='V4>.*threads:4'
600,071,010      instructions           # 0.07  insn per cycle
  8,296,063      branch-misses          # 6.17% of all branches
 33,717,087      L1-dcache-load-misses  # 12.88% of all L1-dcache accesses
```

Energy falls to **11.92 J**, 5.4x less than the naive version.

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V4>.*threads:4'
          11.92 Joules power/energy-pkg/
```


## Summary {#summary}

Reproduce it with the [benchmark](https://github.com/david-alvarez-rosa/CppPlayground/blob/main/dsa/spin_lock.cpp).

| Version | 1 thread    | 2 threads   | 4 threads                 | Notes                 |
|---------|-------------|-------------|---------------------------|-----------------------|
| V1      | 3.14 ns     | 61.5 ns     | 246 ns / 64.92 J          | Naive                 |
| V2      | 1.57 ns     | 32.5 ns     | 131 ns / 34.45 J          | Memory ordering       |
| V3      | 1.58 ns     | 21.3 ns     | 120 ns / 30.97 J          | Test and test-and-set |
| **V4**  | **1.58 ns** | **18.3 ns** | **43.0 ns** / **11.92 J** | Exponential backoff   |

In most code, `std::mutex` is still the right default.  Consider a
spin-lock when the threads are pinned to dedicated cores, and only after
measuring.[^fn:8]

[^fn:1]: Run on a box
    [tuned for benchmarking](/posts/tuning-a-server-for-benchmarking/).  Built with `clang`.  All optimizations
    enabled.
[^fn:2]: `exchange` atomically writes
    `true` and returns the previous value.  `false` means the lock was free
    and is now ours.  `true` means someone else holds it, so we retry.
[^fn:3]: Whether the exchange
    succeeds is decided by the other cores, so the branch predictor has
    nothing to learn.
[^fn:4]: High-frequency trading shops care about it.
    [Exchange colocation services](https://www.nyse.com/technology/colo) charge for power, and NYSE [caps](https://www.federalregister.gov/documents/2023/11/20/2023-25548/self-regulatory-organizations-new-york-stock-exchange-llc-nyse-american-llc-nysearca-inc-nyse) at 32 kW.
[^fn:5]: Reading the RAPL counters requires
    system-wide mode (`-a`) and root, so the figure covers the whole
    package, idle cores included.
[^fn:6]: The
    load can be `relaxed`.  What orders the critical section is the
    `exchange` that succeeds, not the reads that fail.
[^fn:7]: Example 2-10, _Contended Locks with Increasing Back-off_, in
    the [Intel Optimization Reference Manual](https://cdrdv2.intel.com/v1/dl/getContent/671488) (PDF, 248966-050US).
[^fn:8]: With one writer and many readers, consider a `seqlock`
    instead.
