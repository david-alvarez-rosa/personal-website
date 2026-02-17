+++
title = "How to setup a tunnel from vps to local homelab"
author = ["David Álvarez Rosa"]
tags = ["pers", "blog", "backlog"]
draft = true
+++

Write a blog on how to setup a tunnel from vps to local homelab

How to manage a selfhosted homelab behind CGNAT?

Homelab &lt;-&gt; Router &lt;-&gt; NAT &lt;-&gt; ONT &lt;-&gt; **CGNAT** &lt;-&gt; ISP &lt;-&gt; Internet

Expose the server through public VPS

Setting up a VPN tunnel from VPS to local homelab

Steps:

In vps bridge **and** homelab

```nil
sudo apt install wireguard
```

this generates privatekey and publickey files

```nil
umask 077
wg genkey | tee privatekey | wg pubkey > publickey
```

in vps bridge:

find first the interface, example ens3

```nil
ip a
```

then run this, but remember to change ens3 with your interface

```nil
echo 'net.ipv4.ip_forward=1' | sudo tee /etc/sysctl.d/99-wireguard.conf
echo 'net.ipv4.conf.ens3.route_localnet=1' | sudo tee -a /etc/sysctl.d/99-wireguard.conf
sudo sysctl --system
```

Server

```cfg
[Interface]
Address = 10.0.0.1/24
PrivateKey = <server-private-key>
ListenPort = 51820
PostUp = iptables -t nat -A PREROUTING -i ens3 -p udp --dport 51820 -j RETURN
PostUp = iptables -t nat -A PREROUTING -i ens3 -j DNAT --to-destination 10.0.0.2
PostUp = iptables -A FORWARD -i wg0 -o ens3 -s 10.0.0.2 -j ACCEPT
PostUp = iptables -t nat -A POSTROUTING -j MASQUERADE
PostDown = iptables -t nat -D PREROUTING -i ens3 -p udp --dport 51820 -j RETURN
PostDown = iptables -t nat -D PREROUTING -i ens3 -j DNAT --to-destination 10.0.0.2
PostDown = iptables -D FORWARD -i wg0 -o ens3 -s 10.0.0.2 -j ACCEPT
PostDown = iptables -t nat -D POSTROUTING -j MASQUERADE

[Peer]
PublicKey = <homelab-public-key>
AllowedIPs = 10.0.0.2/32
```

Homelab

```cfg
[Interface]
Address = 10.0.0.2/24
PrivateKey = <homelab-private-key>

[Peer]
PublicKey = <server-public-key>
Endpoint = <server-public-ip>:51820
AllowedIPs = 10.0.0.1/32
PersistentKeepalive = 25
```

Then, on both:

```nil
sudo systemctl enable --now wg-quick@wg0
```

What happens if VPS is not working?

Setup an alternate way of accessing the homelab

-   Port forwarding + DynDNS: not possible behind CGNAT, but good option
    otherwise
-   Use a third party: Tailscale/Cloudflare, etc. On my case, I've setup
    cloudflare one zero trust.  It uses outbound service daemon on both
    client and server, so no need to expose anything (same as vps bastion
    or jump box). need to run cloudflared both in server and client

What happens if homelab is not working?

In that case, you'll like to reboot, but if you cannot access it on the
first place that's a big problem

-   Manually restarted if you are physically close to the server
-   Setup heuristic cronjobs: check if the server is accessible by itself
    once in a while, and if that fails, then it will restart and notify
