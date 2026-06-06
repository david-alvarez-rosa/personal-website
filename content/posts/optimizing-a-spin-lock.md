+++
title = "Optimizing a Spin-Lock"
author = ["David Álvarez Rosa"]
tags = ["pers", "blog"]
draft = true
+++

A spin-lock is a mutex that never sleeps: instead of yielding to the
scheduler when the lock is taken, the thread stays on the CPU and keeps
retrying---_spinning_---until it succeeds, avoiding syscalls and context
switches for critical sections of a few nanoseconds.  In this post we'll
write the basic version, see why it is slow, and fix it step by step
until it beats `std::mutex` by 3.4x under contention.


## The benchmark {#the-benchmark}

Each thread increments a shared counter under the lock---250k
increments in total, split evenly across threads.  The total work is
_fixed:_ with a perfect lock the time stays flat as threads are added,
and any growth is pure synchronization overhead.  Threads are pinned to
their own cores[^fn:1]

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

Every version below exposes `lock` and `unlock`.  The baseline,
`SpinLockV0`, simply wraps `std::mutex`

```sh
BM_SpinLock<SpinLockV0>/1/real_time       1.62 ms
BM_SpinLock<SpinLockV0>/2/real_time       5.48 ms
BM_SpinLock<SpinLockV0>/4/real_time       6.13 ms
```

Going from one thread to two more than triples the time, but from two
to four it barely moves: a contended `std::mutex` parks waiters in the
kernel and wakes them one at a time, so the damage does not compound.
The number to beat: **6.13 ms** at four threads.


## A basic spin-lock {#a-basic-spin-lock}

The simplest correct spin-lock is an atomic bool and an exchange
loop[^fn:2]

```cpp
class SpinLockV1 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void { while (locked_.exchange(true)); }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

```sh
BM_SpinLock<SpinLockV1>/1/real_time       1.20 ms
BM_SpinLock<SpinLockV1>/2/real_time       10.3 ms
BM_SpinLock<SpinLockV1>/4/real_time       14.5 ms
```

Uncontended, the spin-lock already wins: **1.20 ms** against the mutex's
1.62 ms---locking is now a single atomic instruction with no library
machinery around it.  Under contention it is a disaster: 1.9x slower
than the mutex at two threads, 2.4x at four---while burning CPU for the
entire wait.

The problem is cache coherence.  To write to a cache line, a core must
first own it exclusively, invalidating every other core's copy.  An
atomic exchange is a write _even when it fails_ and merely swaps `true`
over `true`.  So every waiting thread constantly steals the line away
from everyone else---including from the lock holder, who needs that same
line back just to _release_ the lock.  The line ping-pongs between
cores, and the one useful increment drowns in coherence traffic.

`perf stat -d` counts hardware events; compare one thread against four

```sh
$ perf stat -d ./benchmark --benchmark_filter='V1>/1' --benchmark_min_time=500x
    128,088,290      branches
      1,896,594      branch-misses            #   1.48% of all branches
    645,478,415      L1-dcache-loads
      1,687,482      L1-dcache-load-misses    #   0.26% of all accesses

$ perf stat -d ./benchmark --benchmark_filter='V1>/4' --benchmark_min_time=500x
    674,575,244      branches
    105,479,892      branch-misses            #  15.64% of all branches
  3,262,419,243      L1-dcache-loads
    293,873,584      L1-dcache-load-misses    #   9.05% of all accesses
```

A 35x jump in cache miss rate on the same nine bytes of data, and one
branch in six mispredicted: whether the exchange succeeds is decided by
the other cores, and the predictor cannot learn it.  `perf
c2c`---perf's tool for cache-line contention---ranks cache lines by how
often a core found them dirty in _another_ core's cache (a _HITM_ event)

```sh
$ perf c2c record -a -- ./benchmark --benchmark_filter='V1>/4'
$ perf c2c report --stdio
=================================================
           Shared Data Cache Line Table
=================================================
Index             Address      Hitm
    0      0x7fffffffb440    91.49%
    1  0xffff8c025e923700     2.13%
```

A single cache line---the one holding the lock and the
counter---accounts for 91% of all HITM events.  Spinning is not free in
watts either; the CPU's own energy meter[^fn:3] puts a number on the wait

```sh
$ perf stat -a -e power/energy-pkg/ ./benchmark --benchmark_filter='V1>/4' --benchmark_min_time=200x
          24.43 Joules power/energy-pkg/
```

The mutex does the same 200 iterations for **14.9 J**.


## Active backoff {#active-backoff}

The fix is to stop writing while waiting: attempt the exchange once and,
if it fails, spin on a plain load---read-only copies of the line can
live in every core's L1, so waiting generates no traffic at all.  But
when the holder releases, every waiter sees the lock free at the same
instant, and the whole herd stampedes for the exchange---one wins, the
rest pay the coherence storm anyway.  To thin the herd, space out the
reads with a small delay loop[^fn:4]

```cpp
class SpinLockV2 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    while (true) {
      if (!locked_.exchange(true)) return;
      do {
        for (volatile int i = 0; i < 150; ++i);
      } while (locked_.load());
    }
  }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

At two threads the time drops to **4.82 ms**---2.1x faster than the basic
version and already ahead of the mutex.  At four threads, **12.0 ms**,
most of the gain evaporates: the herd is bigger, and a fixed delay no
longer keeps threads apart.  The counters expose a hidden cost, too:
the cache misses barely improve (**8.6%** against 9.1%), and the energy
gets worse, **29.5 J** against 24.4 J---the delay loop is busy-work
executed at full speed.


## Passive backoff {#passive-backoff}

The delay loop burns the whole wait executing useless increments.  x86
has an instruction for exactly this: `pause`, exposed as the
`_mm_pause()` intrinsic[^fn:5], which inserts a short delay
with the pipeline relaxed

```cpp
class SpinLockV3 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    while (true) {
      if (!locked_.exchange(true)) return;
      do {
        for (auto i = 0; i < 4; ++i) _mm_pause();
      } while (locked_.load());
    }
  }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

The four `pause` calls roughly match the delay of the 150-iteration
loop, and the timings land in the same place: **5.02 ms** at two threads
and **10.9 ms** at four.  The difference is in what the timings don't
show: `perf` counts **24x fewer instructions** for the same work---the
core now spends the wait deliberately doing nothing---and the energy
drops from active backoff's 29.5 J to **17.9 J**.


## Exponential backoff {#exponential-backoff}

Pausing made the wait cheap, but the timings barely moved---and with a
_constant_ delay they cannot: all waiters poll at the same rate, so
every release still wakes the whole herd, and the collisions remain.
For the waiters to arrive at different times, their delays must differ:
let each thread double its delay every time it finds the lock still
taken, up to a cap[^fn:6]

```cpp
class SpinLockV4 {
  std::atomic_bool locked_{false};

public:
  auto lock() noexcept -> void {
    auto backoff_iters = 4;
    while (true) {
      if (!locked_.exchange(true)) return;
      do {
        for (auto i = 0; i < backoff_iters; ++i) _mm_pause();
        backoff_iters = std::min(backoff_iters << 1, 1024);
      } while (locked_.load());
    }
  }
  auto unlock() noexcept -> void { locked_.store(false); }
};
```

The timings collapse: **1.62 ms** at two threads and **1.80 ms** at
four---within 1.5x of the single-threaded time, approaching the flat
line of a perfect lock.  That is 3.4x faster than `std::mutex` and 8x
faster than the basic spin-lock.

The counters explain the win: at four threads the cache miss rate falls
to **1.6%**, branch mispredictions to **2.9%**, and the energy to **3.8 J**, a
quarter of the mutex's 14.9 J---all near their single-threaded levels.
That is the signature of threads running one at a time: backoff
approximately _serializes_ them.  Waiters sit in pause loops of up to
1024 iterations, so the releasing thread usually re-acquires the lock
immediately---lock and counter still warm in its L1---and races through
its share of increments while everyone else stays out of the way.
Serialization is optimal here, since the increments cannot run in
parallel anyway.[^fn:7]


## Summary {#summary}

If you want to reproduce these results, the [benchmark](https://github.com/david-alvarez-rosa/CppPlayground/blob/main/dsa/spin_lock.cpp) lives in my
[CppPlayground](https://github.com/david-alvarez-rosa/CppPlayground) repository.  Each cell reads time / L1d cache misses,
with the winners in bold.

| Version | 1 thread            | 2 threads               | 4 threads               |
|---------|---------------------|-------------------------|-------------------------|
| 0       | 1.62 ms / **0.03%** | 5.48 ms / 1.03%         | 6.13 ms / 1.60%         |
| 1       | **1.20 ms** / 0.26% | 10.3 ms / 3.78%         | 14.5 ms / 9.05%         |
| 2       | 1.25 ms / 0.25%     | 4.82 ms / 3.08%         | 12.0 ms / 8.61%         |
| 3       | 1.25 ms / 0.27%     | 5.02 ms / 2.60%         | 10.9 ms / 6.14%         |
| 4       | 1.25 ms / 0.28%     | **1.62 ms** / **0.67%** | **1.80 ms** / **1.59%** |

The journey is the interesting part: the basic spin-lock lost to
`std::mutex` by 2.4x, and three small fixes---each derived from a
measurement---turned that into a 3.4x win.  In real code,
`std::mutex` remains the right default; reach for a spin-lock when the
critical section is tiny, the threads have dedicated cores, and you have
measured the difference.

[^fn:1]: With `pthread_setaffinity_np`, on a machine tuned
    for benchmarking (AMD Ryzen 7 PRO 8700GE, 8 cores at 3.65 GHz):
    performance governor, hyperthreading and turbo boost disabled.
[^fn:2]: `exchange` atomically writes `true` and returns the previous
    value: `false` means the lock was free and is now ours; `true` means
    someone else holds it, and we retry.
[^fn:3]: The RAPL counters, which
    perf exposes as `power/energy-pkg/`; reading them requires system-wide
    mode (`-a`) and root.
[^fn:4]: `volatile` keeps the compiler from
    deleting the empty loop.  The 150 iterations are a tunable parameter,
    experimentally determined.
[^fn:5]: From `<immintrin.h>`; ARM's closest
    equivalent is the `yield` instruction.
[^fn:6]: Without the cap, threads would end up pausing
    long after the lock has become free.  Both bounds, 4 and 1024, are
    tunable.
[^fn:7]: It is also maximally _unfair:_ nothing stops one
    thread from re-acquiring the lock indefinitely while another starves in
    its backoff loop.  Real implementations bound the unfairness, or enforce
    FIFO order outright with a ticket lock---at a cost in throughput.
