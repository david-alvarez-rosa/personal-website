+++
title = "Implementing a Single Producer Single Consumer Queue"
author = ["David Álvarez Rosa"]
date = 2025-11-25T09:31:00+00:00
tags = ["tag1"]
categories = ["category1"]
draft = false
+++

Lock-free queues are a staple of high-performance systems, but their
implementations often look like black magic. In this post we’ll build a
simple **single-producer single-consumer (SPSC)** queue from scratch in
C++, explain the core ideas, and discuss correctness and performance.

The constraints:

-   Only **one producer thread** calls `push`.
-   Only **one consumer thread** calls `pop`.
-   No locks (`std::mutex`) in the data path.
-   Bounded capacity (fixed-size ring buffer).
