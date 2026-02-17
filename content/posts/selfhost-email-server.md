+++
title = "Self-Hosting an Email Server"
author = ["David Álvarez Rosa"]
tags = ["pers", "blog"]
draft = true
+++

Self-hosting email gives you full control over your communications---no
ads, no scanning, no one can lock you out.  It's easier than most people
think, and this guide covers everything I do when setting up a new mail
server.

You'll need a server with a clean Linux install[^fn:1] and a
domain name pointing to your server's IP.


## DNS records {#dns-records}

Create DNS records for your mail server: A and AAAA records for
`mail.alvarezrosa.com`, plus an MX record pointing to it.[^fn:2]  Verify propagation

```sh
$ dig mail.alvarezrosa.com A +short
213.32.19.229
$ dig mail.alvarezrosa.com AAAA +short
2001:41d0:305:2100::febc
$ dig alvarezrosa.com MX +short
10 mail.alvarezrosa.com.
```

Update your server's hostname to match the mail FQDN.  Edit `/etc/hosts`
so that `hostname -f` returns the fully qualified domain name[^fn:3]

```text
127.0.1.1  mail.alvarezrosa.com  homelab
```


## Receiving mail {#receiving-mail}

Install Postfix and open port 25.  During installation, select "Internet
Site" and enter `mail.alvarezrosa.com` as the system mail name.

```sh
$ sudo apt install postfix
$ sudo ufw allow 25/tcp
```

Configure Postfix to accept mail for your domain.  Edit
`/etc/postfix/main.cf` and add your domain to `mydestination`

```cfg
mydestination = $myhostname, mail.alvarezrosa.com, localhost.alvarezrosa.com, localhost, alvarezrosa.com
```

Restart Postfix and verify it's listening[^fn:4]

```sh
$ sudo systemctl restart postfix
$ sudo ss -tlnp | grep :25
LISTEN 0  100  0.0.0.0:25  0.0.0.0:*  users:(("master",pid=13124,fd=13))
LISTEN 0  100     [::]:25     [::]:*  users:(("master",pid=13124,fd=14))
```

Send a test email to `david@alvarezrosa.com` from Gmail, then check it
arrived

```sh
$ sudo apt install mailutils
$ mail
"/var/mail/david": 1 message 1 new
>N   1 David Álvarez Ros Wed Feb  4 19:39  80/4544  Hello from GMail
```

Your server can now receive mail from anywhere in the world.


## Sending mail {#sending-mail}

Configure Postfix to use your domain in outgoing messages.  Edit
`/etc/postfix/main.cf`

```cfg
myhostname = mail.alvarezrosa.com
mydomain = alvarezrosa.com
myorigin = $mydomain
```

Restart and send a test message

```sh
$ sudo systemctl restart postfix
$ echo "Test from my mail server" | mail -s "Hello" recipient@example.com
```

TODO check this without DMARC policy -- If you send to a major provider
like Gmail before setting up authentication, your message will likely
land in spam or be silently dropped.  That's expected---we'll fix it in
the authentication section.


## Client access {#client-access}

At this point you can send and receive mail, but only from the server's
command line.  To use a real email client, you need IMAP for reading and
authenticated SMTP for sending.

Install Dovecot to expose mailboxes via IMAP.[^fn:5]

```sh
$ sudo apt install dovecot-core dovecot-imapd
```

Obtain a TLS certificate for the mail subdomain[^fn:6]

```sh
$ sudo certbot certonly -d mail.alvarezrosa.com
```

Configure Dovecot TLS.  Edit `/etc/dovecot/conf.d/10-ssl.conf`

```cfg
ssl = required
ssl_server_cert_file = /etc/letsencrypt/live/mail.alvarezrosa.com/fullchain.pem
ssl_server_key_file = /etc/letsencrypt/live/mail.alvarezrosa.com/privkey.pem
```

Configure Postfix TLS.  Add to `/etc/postfix/main.cf`

```cfg
smtpd_tls_key_file = /etc/letsencrypt/live/mail.alvarezrosa.com/privkey.pem
smtpd_tls_cert_file = /etc/letsencrypt/live/mail.alvarezrosa.com/fullchain.pem
smtpd_tls_security_level = encrypt
```

Enable authenticated SMTP submission.  Edit `/etc/postfix/master.cf` and
uncomment the submissions section

```cfg
submissions inet n       -       y       -       -       smtpd
  -o syslog_name=postfix/submissions
  -o smtpd_tls_wrappermode=yes
  -o smtpd_sasl_auth_enable=yes
  -o smtpd_recipient_restrictions=permit_sasl_authenticated,reject
  -o milter_macro_daemon_name=ORIGINATING
```

Configure Postfix to use Dovecot for SASL authentication.  Add to
`/etc/postfix/main.cf`

```cfg
smtpd_sasl_type = dovecot
smtpd_sasl_path = private/auth
smtpd_sasl_auth_enable = yes
```

Connect Dovecot authentication to Postfix.  This lets Postfix
authenticate users against Dovecot.  Edit
`/etc/dovecot/conf.d/10-master.conf` and configure the auth service

```cfg
service auth {
  unix_listener /var/spool/postfix/private/auth {
    mode = 0660
  }
}
```

Open ports 465 (SMTPS) and 993 (IMAPS)

```sh
$ sudo ufw allow 465/tcp
$ sudo ufw allow 993/tcp
```

Restart services

```sh
$ sudo systemctl restart dovecot postfix
```

Configure your email client: IMAP server `mail.alvarezrosa.com` port
993, SMTP server `mail.alvarezrosa.com` port 465, both with SSL/TLS.
Use your Linux username and password as credentials.[^fn:7]  Verify you can send and receive.

You now have a fully functional email server---you can read and compose
mail from any client.  The hard part is done.


## Authentication {#authentication}

Your server works, but mail will land in spam without proper
authentication.  Modern email requires four mechanisms: rDNS, SPF, DKIM,
and DMARC.  Each one builds trust with receiving servers, proving you
are who you claim to be and that your messages haven't been forged or
tampered with.

**rDNS.**  Reverse DNS (also called PTR records) maps your IP back to your
domain, proving you control it.  Most mail servers reject messages from
IPs without proper rDNS.  Configure it through your VPS provider's
control panel---map your IPs to `mail.alvarezrosa.com` and verify

```sh
$ dig +short -x 213.32.19.229
mail.alvarezrosa.com.
```

**SPF.**  SPF (Sender Policy Framework) specifies which servers can send
mail for your domain, preventing spammers from forging your address.
Create a DNS TXT record on your root domain: `v=spf1 mx -all` means only
servers listed in your MX records can send; reject all others.[^fn:8]
Verify the record

```sh
$ dig +short TXT alvarezrosa.com
"v=spf1 mx -all"
```

**DKIM.**  DKIM (DomainKeys Identified Mail) adds a cryptographic
signature to outgoing mail.  Receivers verify it against a public key in
your DNS, proving the message came from your server and wasn't altered
in transit.  Install OpenDKIM to sign outgoing messages.

```sh
$ sudo apt install opendkim opendkim-tools
$ sudo mkdir -p /etc/opendkim/keys/alvarezrosa.com
$ sudo opendkim-genkey -D /etc/opendkim/keys/alvarezrosa.com -d alvarezrosa.com -s mail
$ sudo chown -R opendkim:opendkim /etc/opendkim/keys
$ sudo chmod 600 /etc/opendkim/keys/alvarezrosa.com/mail.private
```

The generated file contains your public key.  Create a DNS TXT record at
`mail._domainkey.alvarezrosa.com` with its contents

```sh
$ sudo cat /etc/opendkim/keys/alvarezrosa.com/mail.txt
```

Verify the record is published

```sh
$ dig +short TXT mail._domainkey.alvarezrosa.com
"v=DKIM1; h=sha256; k=rsa; p=MIIBIjANBgkqhkiG9w0B..."
```

Configure OpenDKIM.  Edit `/etc/opendkim.conf`[^fn:9]

```cfg
Mode            sv
Domain          alvarezrosa.com
Selector        mail
KeyFile         /etc/opendkim/keys/alvarezrosa.com/mail.private
Socket          inet:12301@localhost
UserID          opendkim
PidFile         /run/opendkim/opendkim.pid
```

Hook Postfix to OpenDKIM.  Add to `/etc/postfix/main.cf`

```cfg
milter_default_action = accept
milter_protocol = 6
smtpd_milters = inet:localhost:12301
non_smtpd_milters = inet:localhost:12301
```

Restart services

```sh
$ sudo systemctl enable --now opendkim
$ sudo systemctl restart postfix
```

**DMARC.**  DMARC ties SPF and DKIM together, telling receivers what to do
when checks fail.  Create a DNS TXT record at `_dmarc.alvarezrosa.com`.
The `p` parameter sets the policy: start with `p=none` to monitor
without affecting delivery, then switch to `p=reject` once everything
works.  The `rua` parameter sends aggregate reports to your email.

```text
v=DMARC1; p=reject; rua=mailto:david@alvarezrosa.com
```

Verify the record

```sh
$ dig +short TXT _dmarc.alvarezrosa.com
"v=DMARC1; p=reject; rua=mailto:david@alvarezrosa.com"
```


## Verification {#verification}

Send a test email to Gmail and check the message headers---SPF, DKIM,
and DMARC should all show `pass`.  Use [mail-tester.com](https://www.mail-tester.com) for a
comprehensive deliverability check (aim for 10/10) and [MX Toolbox](https://mxtoolbox.com/SuperTool.aspx) to
verify your DNS records.

Congratulations---your email server is now fully operational, with
proper authentication that major providers will trust.  Messages should
land in inboxes, not spam folders.

Note that new mail servers often face deliverability issues due to IP
reputation.  If your mail lands in spam initially, keep sending
legitimate emails and request delisting from any blacklists your IP
appears on.  Building a positive reputation can take weeks.

[^fn:1]: I use Debian for
    servers.  For initial server setup, see my [Server Setup](/posts/initial-server-setup/) post.
[^fn:2]: MX records
    tell other mail servers where to deliver mail.  The number 10 is the
    priority---lower numbers are tried first if you have multiple mail
    servers.
[^fn:3]: Postfix
    uses the FQDN to identify itself in SMTP conversations.  The short
    hostname can remain `homelab`, but `hostname -f` must return
    `mail.alvarezrosa.com`.
[^fn:4]: You can also test the
    connection with `telnet mail.alvarezrosa.com 25`---you should see a
    Postfix greeting.
[^fn:5]: IMAP keeps messages on
    the server and syncs across devices.  I prefer it over POP3, which
    downloads messages and typically deletes them from the server.
[^fn:6]: Certbot obtains free
    certificates from Let's Encrypt and auto-renews them.  Email clients
    require TLS for secure connections.
[^fn:7]: I use mu4e in
    Emacs.  For testing, Thunderbird works well and auto-detects most
    settings.
[^fn:8]: Use
    `~all` (soft fail) instead of `-all` (hard fail) during testing.
[^fn:9]: Mode `sv` tells
    OpenDKIM to sign outgoing mail and verify incoming signatures.
