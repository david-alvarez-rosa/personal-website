+++
title = "Initial Server Setup"
author = ["David Álvarez Rosa"]
tags = ["pers", "blog"]
draft = true
+++

A misconfigured server is an open invitation to attackers.  After
managing dozens of servers, these are the essential steps I run on every
fresh install.


## Hardware, distro, and DNS {#hardware-distro-and-dns}

Hardware can be whatever, from a cheap VPS, to a Raspberry Pi, to a
thick ass server.

If the hardware is managed by a provider, you'll only be able to choose
the operating system.

Start with a clean Linux install and a domain name.  For partitioning, I
keep it simple: one large root partition plus swap.[^fn:1]  While I run Arch on my personal machine, I prefer Debian for
servers because of its stability and long-term support.

Point your domain to the server's IP from your DNS registrar.  Create an
A record for IPv4 and an AAAA record for IPv6.  Wait a few minutes for
changes to propagate, then verify

```sh
$ dig alvarezrosa.com A +short
213.32.19.229
$ dig alvarezrosa.com AAAA +short
2001:41d0:305:2100::febc
```


## First login {#first-login}

Log in as root, change the default password, and update installed
packages

```sh
$ ssh root@alvarezrosa.com
$ passwd
$ apt update && apt upgrade
```

Create a non-root user with sudo privileges

```sh
$ useradd --create-home --groups sudo david
$ passwd david
```

Log out and reconnect as the new user.[^fn:2]

```sh
$ ssh david@alvarezrosa.com
```


## Dotfiles {#dotfiles}

I set up my dotfiles early for a familiar environment---there's nothing
worse than fumbling with an unfamiliar shell while debugging a
misconfiguration.[^fn:3]

```sh
$ git init
$ git remote add origin https://github.com/david-alvarez-rosa/dotfiles.git
$ git fetch origin
$ git checkout -t origin/main
$ git submodule update --init --recursive
$ git config status.showUntrackedFiles no
```

I configure zsh with oh-my-zsh and starship.[^fn:4]

```sh
$ sudo apt install zsh starship
$ chsh --shell $(which zsh) david
```

Log out and back in to verify the new shell loads.


## SSH keys {#ssh-keys}

Configure SSH key-based authentication.  From your local machine, copy
your public key to the server

```sh
$ ssh-copy-id david@alvarezrosa.com
```

Verify you can log in without a password

```sh
$ ssh david@alvarezrosa.com
```

If you need root access via SSH, copy the authorized keys file

```sh
$ su -
$ cp /home/david/.ssh/authorized_keys ~/.ssh/authorized_keys
```

I keep password authentication enabled for my user but disabled for
root.[^fn:5]


## Timezone, locale, and hostname {#timezone-locale-and-hostname}

Set your timezone and verify with `date`

```sh
$ timedatectl list-timezones
$ sudo timedatectl set-timezone Europe/Madrid
$ date
```

Configure locales by uncommenting `en_US.UTF-8` in `/etc/locale.gen`,
then generate them

```sh
$ sudo locale-gen
```

Set a meaningful hostname and update `/etc/hosts`

```sh
$ sudo hostnamectl set-hostname homelab
$ cat /etc/hosts
127.0.0.1  localhost
::1        localhost  ip6-localhost  ip6-loopback
127.0.1.1  homelab
```


## Firewall {#firewall}

Block all incoming connections by default, then open only what you
need.[^fn:6]

```sh
$ sudo apt install ufw
$ sudo ufw default deny incoming
$ sudo ufw allow 22/tcp
$ sudo ufw enable
```


## Automatic security updates {#automatic-security-updates}

Enable unattended-upgrades to keep the system patched without manual
intervention[^fn:7]

```sh
$ sudo apt install unattended-upgrades apt-listchanges
$ sudo dpkg-reconfigure --priority=low unattended-upgrades
```


## Intrusion prevention {#intrusion-prevention}

Install fail2ban to monitor logs and ban IPs that show malicious
patterns[^fn:8]

```sh
$ sudo apt install fail2ban
$ sudo systemctl enable --now fail2ban
```


## Web server {#web-server}

Install a web server to verify everything works.[^fn:9]

```sh
$ sudo apt install nginx
$ sudo systemctl enable --now nginx
$ sudo ufw allow 80/tcp
```

Navigate to `http://alvarezrosa.com` in your browser---you should see
the default nginx page.

Secure the site with HTTPS using Let's Encrypt.[^fn:10]

```sh
$ sudo ufw allow 443/tcp
$ sudo apt install certbot python3-certbot-nginx
$ sudo certbot
```

Follow the prompts to select your domain and enable HTTPS.  Then verify
`https://alvarezrosa.com` loads correctly in the browser.

[^fn:1]: Predicting
    partition sizes is hard.  A single root partition simplifies management
    and avoids running out of space in one mount point while others sit
    empty.
[^fn:2]: From this point forward,
    avoid using root directly.  Running as a non-root user with sudo is
    safer and leaves an audit trail.
[^fn:3]: The commands below treat your home directory as a
    git repo, letting you track dotfiles without symlinks.  I usually
    configure SSH keys for GitHub first.
[^fn:4]: oh-my-zsh provides
    plugins and themes for zsh.  starship is a fast, cross-shell prompt
    written in Rust.
[^fn:5]: Sometimes I SSH from machines without my keys.  To disable
    password auth entirely, set `PasswordAuthentication no` in
    `/etc/ssh/sshd_config` and restart the SSH daemon.
[^fn:6]: Open port 22 before enabling the firewall, or you'll lock
    yourself out.
[^fn:7]: On Debian, unattended-upgrades installs security
    updates automatically.  Check `/var/log/unattended-upgrades/` for logs.
[^fn:8]: fail2ban watches authentication logs and temporarily blocks
    IP addresses after repeated failed login attempts.
[^fn:9]: nginx is a
    lightweight, high-performance web server.  I used to prefer Apache, but
    nginx handles concurrency better and uses less memory.
[^fn:10]: Certbot obtains free
    TLS certificates and configures nginx automatically.  It also sets up a
    systemd timer for renewal.
