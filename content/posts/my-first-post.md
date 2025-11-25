+++
title = "Rethinking Performance: A Developer’s Guide to CPU Cache Lines"
author = ["David Álvarez Rosa"]
date = 2024-10-12T09:31:00+01:00
tags = ["software"]
draft = false
+++

Modern CPUs can execute billions of instructions per second, yet your code can still be bottlenecked by something as mundane as how an array is laid out in memory. The reason is simple: CPUs are _much_ faster than main memory, so almost every performance-critical program ends up being constrained not by computation, but by data movement. At the center of this tension is a deceptively small unit: the CPU cache line.

Cache lines are how data travels between your code and the CPU’s caches. They determine what gets fetched, what gets evicted, and how different cores interfere with each other’s data. Whether you’re chasing nanoseconds in a trading system, optimizing a game engine, or just trying to make a tight loop scale across cores, understanding cache lines is often the key to unlocking the “mystery” of why some code is fast and other code isn’t.
