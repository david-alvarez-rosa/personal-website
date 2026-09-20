+++
title = "Self-Hosting Behind CGNAT"
author = ["David Álvarez Rosa"]
tags = ["blog", "self-hosting"]
draft = true
subtitle = "Libre software, libre hardware, and my mother's basement."
image = "images/self-hosting-behind-cgnat.png"
+++

There is nothing more satisfying than owning, end to end, the software
and the hardware you use without relying on abusive cloud corporations.
Internet is us, not them.  Break free from censorship.  Learn how to
self-host at home, and be truly _libre_.

In the past, self-hosting was easier.  You just had to open a port on
your router and forward it to any machine at home.[^fn:1]  Nowadays, the shortage of IPv4 addresses means routers
share the same IP across your neighborhood.  Requests are routed using
carrier-grade NAT (CGNAT), a second-layer NAT inside the carrier's
network, where your router's address is private and translated by the
carrier on the way out.  The public address is the carrier's, so port
forwarding no longer works.


## Topology {#topology}

My services run on a mid-range machine in my mother's basement in
northern Spain, and are exposed to the Internet through a cheap VPS
bridge in a French data center.

```text
  +-------------------------------------+
  |           public Internet           |
  +-------------------------------------+
        ^                       ^
        | inbound               |
        v                       |
  +------------+                |
  |   bridge   |                | egress
  +------------+                |
        ^^                      |
        || WireGuard            |
        vv                      |
  +-------------------------------------+
  |               homelab               |
  +-------------------------------------+
```
<div class="src-block-caption">
  <span class="src-block-number">Code Snippet 1:</span>
  <b>Topology diagram.</b>  The homelab is exposed to the Internet through a WireGuard tunnel to a VPS bridge.
</div>

A bidirectional WireGuard tunnel[^fn:2] forwards all
packets in all ports from the bridge to the homelab box, and vice versa.
The beauty of this is that the tunnel is initiated by the homelab, so
you don't need a static dedicated IP at home.[^fn:3]  The penalty of the bridge is 39 ms of RTT.


## Tunnel configuration {#tunnel-configuration}

Bridge's `wg0.conf`.[^fn:4]

```cfg
[Interface]
Address = 10.0.0.1/24
PrivateKey = <bridge-private-key>
ListenPort = 51820
PostUp = ...
PostDown = ...

[Peer]
PublicKey = <homelab-public-key>
AllowedIPs = 10.0.0.2/32
```

`PostUp` sets up NAT and forwarding rules at the kernel level.[^fn:5]  The first two
exclude ports 2222 for SSH, and 51820 for the VPN tunnel itself.  The
last three forward all traffic in all ports to the homelab.  The
destination is rewritten but not the source, so the homelab sees the
real client IPs.

```sh
iptables -t nat -A PREROUTING -i ens3 -p udp --dport 51820 -j RETURN
iptables -t nat -A PREROUTING -i ens3 -p tcp --dport 2222 -j RETURN
iptables -t nat -A PREROUTING -i ens3 -j DNAT --to-destination 10.0.0.2
iptables -A FORWARD -i wg0 -o ens3 -s 10.0.0.2 -j ACCEPT
iptables -A FORWARD -i ens3 -o wg0 -d 10.0.0.2 -j ACCEPT
```

Homelab's `wg0.conf`.[^fn:6]

```cfg
[Interface]
Address = 10.0.0.2/24
PrivateKey = <homelab-private-key>
Table = off
PostUp = ip route add default dev wg0 table 200
PostUp = ip rule add from 10.0.0.2 table 200
PostDown = ...

[Peer]
PublicKey = <bridge-public-key>
Endpoint = 213.32.19.229:51820
AllowedIPs = 0.0.0.0/0
PersistentKeepalive = 25
```

Replies from the homelab have to go back down the tunnel.  That is what
the config is for, sending those replies through the bridge, while
leaving the homelab's own traffic on the home router.[^fn:7]


## Resilience {#resilience}

Three pieces can fail.

-   _Homelab._ A cronjob in the homelab checks whether SSH is still
    working and, if it is not, reboots the box.
-   _Bridge._ In case it fails, I recommend a backup entry point like a
    Cloudflare tunnel or Tailscale directly to the homelab.
-   _Tunnel._ A short drop re-handshakes on its own.  A longer one is
    covered by the two cases above.

<br />

Own your services.  Be _libre_ and have fun!

[^fn:1]: A dynamic DNS
    service kept your domain pointing at the right public IP whenever your
    ISP rotated it.
[^fn:2]: [WireGuard](https://www.wireguard.com/) is a fast, modern and
    secure VPN tunnel that lives inside the Linux kernel.
[^fn:3]: Buying a static IP
    from your ISP is a valid alternative, at around 20 euros a month in
    Spain.
[^fn:4]: See [First Steps on a New Server](/posts/first-steps-on-a-new-server/) for how I set
    up a fresh machine.
[^fn:5]: And
    `PostDown` removes them when the tunnel goes down.
[^fn:6]: Its full configuration lives in my [homelab](https://github.com/david-alvarez-rosa/homelab)
    repository.
[^fn:7]: From then on,
    SSH to `ssh.alvarezrosa.com` at port 22 lands on the homelab, and port
    2222 on the bridge.
