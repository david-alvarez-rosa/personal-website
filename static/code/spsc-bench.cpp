#include <array>
#include <atomic>
#include <chrono>
#include <cstdlib>
#include <mutex>
#include <new>
#include <print>
#include <stdexcept>
#include <thread>

template <typename T, std::size_t N>
class RingBufferV1 {
  std::array<T, N> buffer_;
  std::size_t head_{0};
  std::size_t tail_{0};

public:
  auto push(const T& value) noexcept -> bool {
    auto next_head = head_ + 1;
    if (next_head == buffer_.size()) {  // Wrap-around
      next_head = 0;
    }
    if (next_head == tail_) {  // Full
      return false;
    }
    buffer_[next_head] = value;
    head_ = next_head;
    return true;
  }

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
};

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

template <typename T, std::size_t N>
class RingBufferV3 {
  std::array<T, N> buffer_;
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t head_{0};
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t tail_{0};

public:
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
};

template <typename T, std::size_t N>
class RingBufferV4 {
  std::array<T, N> buffer_;
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t head_{0};
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t tail_{0};

public:
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
};

template <typename T, std::size_t N>
class RingBufferV5 {
  std::array<T, N> buffer_;
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t head_{0};
  alignas(std::hardware_destructive_interference_size) std::size_t head_cached_{0};
  alignas(std::hardware_destructive_interference_size) std::atomic_size_t tail_{0};
  alignas(std::hardware_destructive_interference_size) std::size_t tail_cached_{0};

public:
  auto push(const T& value) noexcept -> bool {
    const auto head = head_.load(std::memory_order_relaxed);
    auto next_head = head + 1;
    if (next_head == buffer_.size()) {
      next_head = 0;
    }
    if (next_head == tail_cached_) {
      tail_cached_ = tail_.load(std::memory_order_acquire);
      if (next_head == tail_cached_) {
        return false;
      }
    }
    buffer_[head] = value;
    head_.store(next_head, std::memory_order_release);
    return true;
  }

  auto pop(T& value) noexcept -> bool {
    const auto tail = tail_.load(std::memory_order_relaxed);
    if (tail == head_cached_) {
      head_cached_ = head_.load(std::memory_order_acquire);
      if (tail == head_cached_) {
        return false;
      }
    }
    value = buffer_[tail];
    auto next_tail = tail + 1;
    if (next_tail == buffer_.size()) {
      next_tail = 0;
    }
    tail_.store(next_tail, std::memory_order_release);
    return true;
  }
};

auto pinThread(int cpu_id) -> void {
  cpu_set_t cpuset;
  CPU_ZERO(&cpuset);
  CPU_SET(cpu_id, &cpuset);
  if (pthread_setaffinity_np(pthread_self(), sizeof(cpu_set_t), &cpuset) != 0) {
    std::exit(EXIT_FAILURE);
  }
}

template <typename RingBuffer>
auto bench(int num_iters) noexcept -> void {
  auto ring_buffer = RingBuffer{};

  auto consumer = std::thread([&] {
    pinThread(2);
    for (auto i = 0; i < num_iters; ++i) {
      auto value = 0;
      while (!ring_buffer.pop(value));
      if (value != i) {
        throw std::runtime_error("Values not matching");
      }
    }
  });

  pinThread(3);

  auto start = std::chrono::steady_clock::now();
  for (auto i = 0; i < num_iters; ++i) {
    while (!ring_buffer.push(i));
  }
  consumer.join();
  auto stop = std::chrono::steady_clock::now();
  std::println("{:.2f}M ops/s",
               1'000.0 * num_iters /
                   std::chrono::duration_cast<std::chrono::nanoseconds>(stop - start)
                       .count());
}

auto main() -> int {
  // bench<RingBufferV1<int, 100'000>>(100'000'000);
  // bench<RingBufferV2<int, 100'000>>(100'000'000);
  // bench<RingBufferV3<int, 100'000>>(100'000'000);
  bench<RingBufferV4<int, 100'000>>(100'000'000);
  // bench<RingBufferV5<int, 100'000>>(100'000'000);
}
