+++
title = "From Threads to Tasks: Rethinking Concurrency in Modern C++"
author = ["David Álvarez Rosa"]
date = 2025-11-25T09:31:00+00:00
draft = false
+++

For years, writing concurrent C++ meant thinking in terms of threads,
mutexes, and condition variables. You’d spin up a few `std::thread` s, protect shared data with locks, and hope you got the ordering and lifetime rules right. As codebases and core counts grew, this model started to creak: too many threads, too much blocking, and too much complexity to reason about correctness and performance.

Modern C++ is steadily moving away from “managing threads” toward “expressing tasks.” Instead of deciding which thread runs what, you describe _what_ needs to happen, _when_ it can happen, and let an executor or scheduler worry about mapping that work onto hardware. This shift—from threads to tasks—enables better scalability, clearer structure, and often fewer bugs.
