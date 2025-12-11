+++
title = "Optimizing a Lock-Free Ring Buffer"
author = ["David Álvarez Rosa"]
draft = true
+++

A single-producer single-consumer (SPSC) queue is a great example of how
far constraints can take a design. One writer, one reader, fixed
capacity. With those rules it’s possible to remove locks, avoid
heavyweight atomics, and still get FIFO semantics with predictable
latency. The path there is incremental: start with the simplest ring
buffer, make it thread-safe with the most conservative choices, then
progressively remove costs while preserving correctness.


## What is a ring buffer? {#what-is-a-ring-buffer}

{{< figure src="/ox-hugo/ringbuffer.jpg" width="margin" caption=" **Ring buffer with 32 slots.** The producer has filled 15 of them, indicated by blue. The consumer is behind the producer, reading data from the slots, freeing them as it does so. A free slot is indicated by orange." >}}  You may have heard of something
called a circular buffer, or maybe even a cyclic queue.  Both are just
other names for the _ring buffer_, a specialized queue in which a
producer produces some data and shoves it into the data structure, and a
consumer comes along and consumes it, all in first-in-first-out order.

What sets the ring buffer apart is the way it manages its data and the
limitations it imposes.  A ring buffer has a fixed capacity; it can’t
grow and it can’t shrink.  Because of that, once the buffer is full, the
producer either has to wait for a slot to become free or start stomping
over data that hasn’t been consumed yet, depending on how the buffer and
the application are designed.  Both approaches can be perfectly valid.

The consumer’s role is simply to consume data. If there’s nothing
available in the ring buffer, the consumer has to wait or go do
something else. Each time the consumer reads an item, it frees up a slot
that the producer can reuse. Ideally, the producer is always just
slightly ahead of the consumer, turning the whole thing into a little
game of _"catch me if you can,"_ with almost no waiting on either side.


## Single-threaded ring buffer {#single-threaded-ring-buffer}

Let's start with a single-threaded ring buffer, which is just an
array{{< sidenote >}}By using `std::array` we are forcing clients to define the buffer size as `constexpr`. It's also common to use instead a `std::vector` to remove that restriction.{{< /sidenote >}}
and two indices.  We can leave one slot empty to distinguish "full" from
"empty."  Push writes to head and advances it; pop reads from tail and
advances it.

```cpp
template <typename T, std::size_t N>
class RingBufferV1 {
  std::array<T, N> buffer_;
  std::size_t head_;
  std::size_t tail_;
};
```

We can now implement the `push` (or write) operation{{< sidenote >}}Note how one item is left unused to indicate that the queue is full, when `head_` is one item behind `tail_` the queue is full.{{< /sidenote >}}

```cpp
auto push(const T& value) noexcept -> bool {
  auto new_head = head_ + 1;
  if (new_head == buffer_.size()) {  // Wrap-around
    new_head = 0;
  }
  if (new_head == tail_) {  // Full
    return false;
  }
  buffer_[new_head] = value;
  head_ = new_head;
  return true;
}
```

Next we implement the `pop` (or read) operation{{< sidenote >}}Again note that `head_ == tail_` indicates that the queue is empty.{{< /sidenote >}}

```cpp
auto pop(T& value) noexcept -> bool {
  if (head_ == tail_) {  // Empty
    return false;
  }
  value = buffer_[tail_];
  auto next_tail = tail_ + 1;
  if (next_tail == buffer_.size()) {  // Wrap-around
    next_tail = 0;
  }
  tail_ = next_tail;
  return true;
}
```


## Thread-safe ring buffer {#thread-safe-ring-buffer}

You probably already noticed that the previous version is not
thread-safe.  The easiest way to solve this, is to add a `mutex` around
push and pop.

```cpp
#include <array>
#include <mutex>

template <typename T, std::size_t N>
class RingBufferV2 {
  std::array<T, N> buffer_;
  alignas(std::hardware_destructive_interference_size) std::size_t head_{0};
  alignas(std::hardware_destructive_interference_size) std::size_t tail_{0};
  std::mutex mutex_;

public:
  auto push(const T& value) noexcept -> bool {
    auto lock = std::lock_guard<std::mutex>{mutex_};  // Thread-safe
    auto next_head = head_ + 1;
    if (next_head == buffer_.size()) {
      next_head = 0;
    }
    if (next_head == tail_) {
      return false;
    }
    buffer_[head_] = value;
    head_ = next_head;
    return true;
  }

  auto pop(T& value) noexcept -> bool {
    auto lock = std::lock_guard<std::mutex>{mutex_};  // Thread-safe
    if (head_ == tail_) {
      return false;
    }
    value = buffer_[tail_];
    auto next_tail = tail_ + 1;
    if (next_tail == buffer_.size()) {
      next_tail = 0;
    }
    tail_ = next_tail;
    return true;
  }
};
```

It’s correct and often fast enough, but it pays for mutual
exclusion even though the producer and consumer never write the same
index. The ownership is asymmetric: the producer is the only writer of
head, and the consumer is the only writer of tail. That asymmetry is the
lever to remove locks.


## Lock-free ring buffer {#lock-free-ring-buffer}

Now using atomics instead of locks.

```cpp
template <typename T, std::size_t N>
class RingBufferV3 {
  std::array<T, N> buffer_;
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t head_{0};
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t tail_{0};
};
```

The push implementation

```cpp
auto push(const T& value) noexcept -> bool {
  const auto head = head_.load();
  auto next_head = head + 1;
  if (next_head == buffer_.size()) {
    next_head = 0;
  }
  if (next_head == tail_.load()) {
    return false;
  }
  buffer_[head] = value;
  head_.store(next_head);
  return true;
}
```

The pop implementation.

```cpp
auto pop(T& value) noexcept -> bool {
  const auto tail = tail_.load();
  if (tail == head_.load()) {
    return false;
  }
  value = buffer_[tail];
  auto next_tail = tail + 1;
  if (next_tail == buffer_.size()) {
    next_tail = 0;
  }
  tail_.store(next_tail);
  return true;
}
```

Results are XXM ops/s, put here the output of perf.


### Tuning memory order {#tuning-memory-order}

How to tune memory order.

```cpp
auto push(const T& value) noexcept -> bool {
  const auto head = head_.load(std::memory_order_relaxed);
  auto next_head = head + 1;
  if (next_head == buffer_.size()) {
    next_head = 0;
  }
  if (next_head == tail_.load(std::memory_order_acquire)) {
    return false;
  }
  buffer_[head] = value;
  head_.store(next_head, std::memory_order_release);
  return true;
}
```

And then, the pop

```cpp
auto pop(T& value) noexcept -> bool {
  const auto tail = tail_.load(std::memory_order_relaxed);
  if (tail == head_.load(std::memory_order_acquire)) {
    return false;
  }
  value = buffer_[tail];
  auto next_tail = tail + 1;
  if (next_tail == buffer_.size()) {
    next_tail = 0;
  }
  tail_.store(next_tail, std::memory_order_release);
  return true;
}
```


## Further optimizations {#further-optimizations}

We already have a pretty fast ring buffer, but what if we want to go
even further?  We can do by following the approach from
<https://rigtorp.se/ringbuffer/> put this as a side note.

Why do we have 3 cache misses per read-write pair? Consider a read
operation: the read index needs to be updated and thus that cache line
is loaded into the L1 cache in exclusive state (see MESI protocol). The
write index needs to be read in order to check that the queue is not
empty and is thus loaded into the L1 cache in shared state. Since a
queue write operation needs to read the read index it causes the
reader’s read index cache line to be evicted or transition into shared
state. Now the read operation requires some cache coherency traffic to
bring the read index cache line back into exclusive state. In turn a
write operation will require some cache coherency traffic to bring the
write index cache line back into exclusive state. In the worst case
there will be one cache line transition from shared to exclusive for
every read and write operation. These cache line state transitions are
counted as cache misses. We don’t know the exact implementation details
of the cache coherency protocol, but it will behave roughly as the MESI
protocol.

To reduce the amount of coherency traffic the reader and writer can keep
a cached copy of the write and read index respectively. In this case
when a reader first observes that N items are available to read, it
caches this information and the N-1 subsequent reads won’t need to read
the write index. Similarly when a writer first observes that N items are
available for writing, it caches this information and the N-1 subsequent
writes won’t need to read the read index.

The new ring buffer is defined as follows:

```cpp
template <typename T, std::size_t N>
class RingBufferV5 {
  std::array<T, N> buffer_;
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t head_{0};
  alignas(std::hardware_destructive_interference_size) std::size_t head_cached_{0};
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t tail_{0};
  alignas(std::hardware_destructive_interference_size) std::size_t tail_cached_{0};
};
```

The push operation is updated to first consult the cached read index
(readIdxCached_) and if that fails retry after updating the cache:

```cpp
if (next_head == tail_cached_) {
  tail_cached_ = tail_.load(std::memory_order_acquire);
  if (next_head == tail_cached_) {
    return false;
  }
 }
```

The pop operation is updated in a similar way to first consult the
cached write index (writeIdxCached_):

```cpp
if (tail == head_cached_) {
  head_cached_ = head_.load(std::memory_order_acquire);
  if (tail == head_cached_) {
    return false;
  }
 }
```

Re-running the same benchmark as before with the new ring buffer
implementation:

Put results of running with perf.

Wow this is great! Throughput is now 112M items per second and the
number of cache misses was significantly reduced. Checkout
ringbuffer.cpp if you want to verify this yourself.


## Summary {#summary}

asdfasd

| Version | Throughput | Notes                                            |
|---------|------------|--------------------------------------------------|
| 1       | N/A        | Not thread-safe                                  |
| 2       | 12M ops/s  | Mutex / lock                                     |
| 3       | 35M ops/s  | Lock-free (atomics)                              |
| 4       | 108M ops/s | Lock-free (atomics) + memory order               |
| 5       | 305M ops/s | Lock-free (atomics) + memory order + index cache |

Some closing comments here.
