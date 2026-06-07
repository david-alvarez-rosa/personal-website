+++
title = "Self-Hosting Behind CGNAT"
author = ["David Álvarez Rosa"]
tags = ["pers", "blog"]
draft = true
+++

This site is self-hosted on a server that cannot accept a single
inbound connection: the ISP puts it behind CGNAT, so there is no public
IP to forward ports on.  The fix is a _bridge_---a bastion with a
real public address---and a WireGuard tunnel dialed out from the
homelab: clients connect to the bridge, and the bridge forwards
everything back through the tunnel.


## The plan {#the-plan}

With carrier-grade NAT, the ISP shares one public IPv4 address across
many customers: your router's WAN address is itself
private[^fn:1]---a second NAT, outside your home,
that you don't control.  The classic recipe of port forwarding plus
dynamic DNS[^fn:2] dies here: forwarding only gets you through the first NAT,
and the address DDNS would publish is shared with hundreds of
strangers.  Inbound connections are simply impossible.

But outbound connections still work fine---so the homelab dials _out_,
opening a WireGuard tunnel to the bridge and keeping it alive.  The
bridge keeps only WireGuard itself and its own SSH, and forwards every
other inbound connection through the tunnel.  From the outside, the
bridge _is_ the homelab---DNS just points at it.[^fn:3]  Inside the tunnel, the bridge is `10.0.0.1` and the
homelab `10.0.0.2`.

```text
   client                                 admin
      |                                     |
      |                                     |
+---------------------------------------------------------+
|                     public Internet                     |
+---------------------------------------------------------+
      |                         |               |
      |                         |               |
+---------------------+    +--------------+     |
| bridge    10.0.0.1  |    |  Cloudflare  |     |  homelab's own
| DNAT  * ->  homelab |    +--------------+     |  outbound traffic
+---------------------+         ^^              |
      ^^                        ||              |
      ||  WireGuard             || cloudflared  |
      ||  (all ports)           || (backup SSH) |
      vv                        vv              |
+---------------------------------------------------------+
|           homelab   10.0.0.2   (behind CGNAT)           |
+---------------------------------------------------------+
      |                                     ^
      |   self-check via bridge---or reboot |
      +-------------------------------------+
```

Both tunnels are dialed out from the homelab---only outbound works
behind CGNAT.  The admin SSHes in through the bridge like any other
client (port 22 is forwarded with the rest); the homelab's own
outbound traffic never crosses it, only replies to forwarded
connections do.  The Cloudflare backup tunnel and the watchdog loop
are covered at the end.


## The tunnel {#the-tunnel}

On both machines, install WireGuard and generate a keypair; exchange
the public keys---the private ones never leave their machine.

The bridge's entire setup lives in `/etc/wireguard/wg0.conf`

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

where `PostUp` enables IP forwarding and installs the five iptables
rules that do the forwarding, tying their lifetime to the
tunnel's[^fn:4]

```sh
sysctl -w net.ipv4.ip_forward=1 net.ipv4.conf.ens3.route_localnet=1
iptables -t nat -A PREROUTING -i ens3 -p udp --dport 51820 -j RETURN
iptables -t nat -A PREROUTING -i ens3 -p tcp --dport 2222 -j RETURN
iptables -t nat -A PREROUTING -i ens3 -j DNAT --to-destination 10.0.0.2
iptables -A FORWARD -i wg0 -o ens3 -s 10.0.0.2 -j ACCEPT
iptables -A FORWARD -i ens3 -o wg0 -d 10.0.0.2 -j ACCEPT
```

Two `RETURN` rules keep WireGuard (`51820/udp`) and the bridge's own
SSH (`2222/tcp`) local; the catch-all `DNAT` rewrites everything
else---port 22 included---to the homelab's tunnel address; and two
`FORWARD` accepts let that traffic flow both ways.[^fn:5]  Everything happens inside the kernel: netfilter does
the rewriting, and no userspace process ever touches a packet.

The bridge's own sshd listens on 2222 precisely so that port 22 can be
forwarded with everything else: `ssh alvarezrosa.com` lands on the
homelab, `ssh -p 2222` on the bridge.[^fn:6]

The homelab side dials out and answers.  Its `/etc/wireguard/wg0.conf`

```cfg
[Interface]
Address = 10.0.0.2/24
PrivateKey = <homelab-private-key>
Table = off
PostUp = ...
PostDown = ...

[Peer]
PublicKey = <bridge-public-key>
Endpoint = 213.32.19.229:51820
AllowedIPs = 0.0.0.0/0
PersistentKeepalive = 25
```

where `PostUp` sets up policy routing.

```sh
ip route add default dev wg0 table 200
ip rule add from 10.0.0.2 table 200
```

Each line earns its place: `AllowedIPs = 0.0.0.0/0` accepts forwarded
clients from anywhere on the Internet; `Table = off` stops `wg-quick`
from hijacking _all_ of the homelab's traffic through the
bridge[^fn:7]; the policy routing sends only replies of
forwarded connections---packets _from_ `10.0.0.2`---back through the
tunnel; and `PersistentKeepalive` keeps the CGNAT's idle UDP mapping
alive, so the bridge can always reach in.

Enable the tunnel on both machines with
`sudo systemctl enable --now wg-quick@wg0` and verify the handshake
with `sudo wg`.  Then the real test: from outside, any connection to
the bridge's public IP should land on the homelab.  Point your DNS
records at the bridge and the homelab is, for all practical purposes,
on the public Internet.

The detour adds latency: this bridge sits in France, the homelab in
northern Spain, and the tunnel adds ~37 ms of RTT to every
connection.[^fn:8]  Not a problem in practice: with heavy optimization
and a CDN absorbing most requests, this site---served through this
very tunnel---is among the fastest on the web.


## Plan for failure {#plan-for-failure}

Two single points of failure, and a plan for each.

If the _bridge_ dies, the tunnel dies with it---so keep a way into the
homelab that bypasses it entirely.  I run a [Cloudflare Tunnel](https://developers.cloudflare.com/cloudflare-one/):
`cloudflared` uses the same dial-out trick, outbound-only on both ends,
so it also works behind CGNAT.[^fn:9]  It
exposes the homelab's sshd at a hostname of its own, and a
`ProxyCommand` in the client's `\~/.ssh/config` connects through
it---whatever happens to the bridge, `ssh homelab2` still gets in.

If the _homelab_ dies, no tunnel will save you---the machine to reboot
is the one you can't reach.  So it watches itself with a root cron
job---`0 5 * * * ssh ssh.alvarezrosa.com || reboot`---that SSHes to
its own public hostname, out through CGNAT to the bridge and back in
through the tunnel, the whole chain end to end, and reboots if that
fails.[^fn:10]

<br />

That's the whole trick: one cheap bridge, one tunnel, five iptables
rules---and a server behind CGNAT serves the public Internet, this very
page included.

[^fn:1]: Usually in `100.64.0.0/10`, the shared address space
    reserved for CGNAT by RFC 6598.
[^fn:2]: Shaky even without CGNAT: a rotating address is bad
    for anything reputation-sensitive like mail, and every rotation means
    downtime while resolvers keep serving the old IP until the TTL
    expires.
[^fn:3]: The bridge only
    pushes packets, so the smallest of servers will do.  See [First Steps on
    a New Server](/posts/first-steps-on-a-new-server/) for the basic setup.
[^fn:4]: `PostDown` mirrors `PostUp`, undoing every command.
    `ens3` is the bridge's public interface---find yours with `ip a` and
    adjust.
[^fn:5]: Note there is no
    `MASQUERADE`: conntrack reverses the DNAT on the way out, and the
    homelab routes its replies back through the tunnel.  Forwarded services
    see the _real_ client IP---something proxies and third-party tunnels
    can't offer.
[^fn:6]: Add `Port 2222` to the
    bridge's `/etc/ssh/sshd_config` _before_ bringing the tunnel up---the
    moment the DNAT rule takes effect, port 22 belongs to the homelab.
[^fn:7]: Without it, `wg-quick` would install a default route
    matching `AllowedIPs`.
[^fn:8]: Amusingly, pinging the bridge's _public_ IP from the
    homelab reports ~74 ms---exactly double.  The echo request is DNAT'd
    back through the tunnel to the homelab itself, so every packet crosses
    the tunnel twice.
[^fn:9]: Tailscale fills the same role.
[^fn:10]: A bridge outage also trips this check and reboots a
    perfectly healthy homelab---an acceptable false positive, since a
    reboot is harmless.
