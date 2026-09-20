+++
title = "Self-Hosting Behind CGNAT"
author = ["David Álvarez Rosa"]
tags = ["blog", "self-hosting"]
draft = true
subtitle = "Serving the public Internet from a box at home."
+++

There is nothing more satisfying than owning, end to end, the software
and the hardware you use without relying on abusive cloud corporations.
The Internet is us, not them.  Break free from censorship by learning
how to self-host at home.

A few years ago self-hosting was easier.  You opened a port on your
router and forwarded it to any machine at home.[^fn:1]  Nowadays, however, due to the shortage of IPv4
addresses, ISPs share the same IP among your neighborhood.  Requests are
routed using carrier-grade NAT (CGNAT), a second layer of NAT inside the
carrier's network, where your router's public address is private too and
the carrier translates it on the way out.


## Topology {#topology}

My services run on a mid-range machine in my mother's basement, and are
exposed to the Internet through a cheap VPS bridge in a French data
center.

```text
  +-------------------------------------+
  |           public Internet           |
  +-------------------------------------+
        ^                       ^
        |                       |
        v                       |
  +------------+                |
  |   bridge   |                |  egress
  +------------+                |
        ^^                      |
        ||  WireGuard           |
        vv                      |
  +-------------------------------------+
  |               homelab               |
  +-------------------------------------+
```

A bidirectional WireGuard tunnel[^fn:2] forwards all
packets in all ports from the bridge to the homelab box, and vice versa.
The beauty of this is that the tunnel is initiated by the homelab, so we
don't need a static dedicated IP at home.[^fn:3]
The penalty of the bridge is 39 ms of RTT.


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

Replies from the homelab have to go back down the tunne.  That is what
the config is for, sending those replies through the bridge, while
leaving the homelab's own traffic on the home router.[^fn:7]


## Resilience {#resilience}

Three pieces can fail.

-   _Homelab._ It cannot be rebooted from outside, so a cronjob checks
    whether SSH is reachable and, if it is not, reboots the box.
-   _Bridge._ In case it fails, I recommend a backup entry point like a
    Cloudflare tunnel or Tailscale directly to the homelab.
-   _Tunnel._ A short drop re-handshakes on its own.  A longer one is
    covered by the two cases above.

<br />

Own your services.  Have fun!

[^fn:1]: A dynamic DNS
    service kept your domain pointing at the right public IP whenever your
    ISP rotated it.
[^fn:2]: [WireGuard](https://www.wireguard.com/) is a fast, modern and
    secure VPN tunnel that lives inside the Linux kernel.
[^fn:3]: Buying a static IP from
    your ISP is a valid alternative, at around 20 euros a month in Spain.
[^fn:4]: See [First Steps on a New Server](/posts/first-steps-on-a-new-server/) for how I set
    up a fresh machine.
[^fn:5]: And
    `PostDown` removes them when the tunnel goes down.
[^fn:6]: Its full configuration lives in my [homelab](https://github.com/david-alvarez-rosa/homelab)
    repository.
[^fn:7]: From then on,
    SSH to `ssh.alvarezrosa.com` at port 22 lands on the homelab, and port
    2222 on the bridge.
