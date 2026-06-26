+++
title = "First Steps on a New Server"
author = ["David Álvarez Rosa"]
date = 2026-05-17T17:26:00+01:00
tags = ["blog"]
draft = false
subtitle = "A personal checklist for settling into a fresh machine."
+++

Over the last decade I've been playing with dozens of servers from
multiple providers.  These are the steps I've been perfecting to get up
to speed fast and feel right at home on a new machine.  Wrote it down
here mostly as a personal reference, but hopefully useful to someone
else too.


## Hardware, distro, and DNS {#hardware-distro-and-dns}

Clean Linux install with one large root partition plus big
swap.[^fn:1]  While I run Arch on my laptop, Debian tends to be a better
fit for servers because of its stability and long support window.

Point your domain[^fn:2] to your server's IP at your DNS provider: an A record for IPv4
and an AAAA record for IPv6.  Wait a few minutes, then verify both.

```sh
$ dig alvarezrosa.com A +short
213.32.19.229
$ dig alvarezrosa.com AAAA +short
2001:41d0:305:2100::febc
```

Hardware doesn't matter: a VPS, a Raspberry Pi, or a dedicated box will
do.


## First login {#first-login}

Log in as root, change the password, and update.

```sh
$ ssh root@alvarezrosa.com
$ passwd
$ apt update && apt full-upgrade
```

Create a non-root user with sudo privileges.

```sh
$ useradd --create-home --groups sudo david
$ passwd david
```

Log out, then reconnect as the new user.

```sh
$ ssh david@alvarezrosa.com
```

From here on, stay on this account and use sudo when you need it.


## Dotfiles {#dotfiles}

I like to set up dotfiles early.  Debugging on an unfamiliar shell is
its own kind of miserable.[^fn:3]

```sh
$ git init
$ git remote add origin https://github.com/david-alvarez-rosa/dotfiles.git
$ git fetch origin
$ git checkout -t origin/main
$ git submodule update --init --recursive
$ git config status.showUntrackedFiles no
```

Switch to zsh and install starship.[^fn:4]

```sh
$ sudo apt install zsh starship
$ chsh --shell $(which zsh)
```

Log out and back in to confirm the shell loads correctly.


## SSH keys {#ssh-keys}

Copy your public key to the server from your local machine.[^fn:5]

```sh
$ ssh-copy-id david@alvarezrosa.com
```

Confirm you can get in without a password.

```sh
$ ssh david@alvarezrosa.com
```

If you need root access over SSH, install the key there too.

```sh
$ sudo install -d -m 700 /root/.ssh
$ sudo install -m 600 ~/.ssh/authorized_keys /root/.ssh/authorized_keys
```

Once that's working, disable password auth at least for
root.[^fn:6]


## Timezone, locale, and hostname {#timezone-locale-and-hostname}

Set the timezone and verify with `date`.

```sh
$ timedatectl list-timezones
$ sudo timedatectl set-timezone Europe/Madrid
$ date
```

Then enable `en_US.UTF-8` locale and make it the default.

```sh
$ sudo vim /etc/locale.gen  # Uncomment en_US.UTF-8
$ sudo locale-gen
$ sudo update-locale LANG=en_US.UTF-8
```

Set a sensible hostname and make sure `/etc/hosts` matches.

```sh
$ sudo hostnamectl set-hostname homelab
$ cat /etc/hosts
127.0.0.1  localhost
::1        localhost  ip6-localhost  ip6-loopback
127.0.1.1  homelab
```


## Firewall {#firewall}

Deny all inbound traffic and allow only the ports you need.[^fn:7]

```sh
$ sudo apt install ufw
$ sudo ufw default deny incoming
$ sudo ufw allow 22/tcp
$ sudo ufw enable
```

Add more rules only as services are exposed.


## Automatic security updates {#automatic-security-updates}

Security patches shouldn't depend on remembering to log in every few
days.[^fn:8]

```sh
$ sudo apt install unattended-upgrades apt-listchanges
$ sudo dpkg-reconfigure --priority=low unattended-upgrades
```

After that, security updates mostly take care of themselves.


## Intrusion prevention {#intrusion-prevention}

`fail2ban` watches authentication logs and temporarily blocks IPs that
look like they're brute-forcing your services.

```sh
$ sudo apt install fail2ban
$ sudo systemctl enable --now fail2ban
```


## Web server {#web-server}

Install a web server to verify everything works end to end.[^fn:9]

```sh
$ sudo apt install nginx
$ sudo systemctl enable --now nginx
$ sudo ufw allow 80/tcp
```

Open your domain in a browser.  You should see the default nginx page.  Then
enable HTTPS with Let's Encrypt.[^fn:10]

```sh
$ sudo ufw allow 443/tcp
$ sudo apt install certbot python3-certbot-nginx
$ sudo certbot
```

Follow the prompts; Certbot rewrites the nginx config and sets up
renewal automatically.  Confirm your domain loads over HTTPS.

<br />

That's the baseline.  From here, the machine is yours---go build on it.

[^fn:1]: Predicting future partitioning needs is easy for a desktop,
    but can be difficult for a server.  One large root filesystem is easier
    to manage.
[^fn:2]: This post uses my domain alvarezrosa.com as an
    example.
[^fn:3]: These commands treat the home directory
    as a Git repository, which lets you track dotfiles without symlink
    gymnastics.  GitHub access can be configured shortly after this.
[^fn:4]: Oh My Zsh is a common shell
    add-on, but it isn't required for the server itself.  starship is a fast
    cross-shell prompt.
[^fn:5]: If you
    don't have a key on your local machine yet, generate one first with
    `ssh-keygen`.
[^fn:6]: Debian's default is already `PermitRootLogin
    prohibit-password`, which only allows key-based root logins.
[^fn:7]: Make
    sure SSH is allowed before enabling the firewall, or you will lock
    yourself out of the machine.
[^fn:8]: Logs for unattended updates live in
    `/var/log/unattended-upgrades/`.
[^fn:9]: I've
    been using Apache for quite a few years, but nginx is more lightweight
    and handles concurrent connections more efficiently.
[^fn:10]: Certbot obtains free TLS
    certificates, updates the nginx configuration for you, and sets up
    automatic renewal.
