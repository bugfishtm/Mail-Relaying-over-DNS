# Installation

Please read this documentation and the part above carefully before you install this software.

## Requirements

- Server with Static IP (Recommended) + Root Access
- Apache2 with PHP 7.4/8.X
- Apache2 Modules: Rewrite, Header, SSL (Recommended)
- PHP Modules: curl, intl, gd
- Postfix installed (see below for configuration)
- Open Firewall Ports for Mail (25, 465, 587) and the Web Interface (80/443 default)
- Access to MySQL Database

## Installation Steps

2. **Upload Files**: Upload all files from the source directory to your web server's website root directory.

   - Check `settings.sample.php` and set up as needed. Look at the comments to understand the different settings or refer to the details below.
   - After editing the file with the necessary information, rename it to `settings.php`.

4. **SQL Tables**: You do not need to install any SQL tables manually; they will be installed automatically.

5. **Postfix Configuration**: Set up Postfix as described in the Postfix Setup section below. Edit the file /etc/postfix/main.cf.

6. **Cronjobs**: Set up the required cronjobs as described in the Cronjobs section below.

## Postfix Setup

**Warning**: Do not use this configuration in a running Mail Environment! Edit your File /etc/postfix/main.cf on a fresh server to comply with the software's expectations!

```plaintext
smtpd_banner = $myhostname ESMTP $mail_name (Debian/GNU)
biff = no
append_dot_mydomain = no
readme_directory = no
compatibility_level = 2

smtpd_tls_cert_file = *****PATHTOSSLCERT*****
smtpd_tls_key_file = *****PATHTOSSLKEY*****
smtpd_use_tls = yes
smtpd_tls_session_cache_database = btree:${data_directory}/smtpd_scache
smtp_tls_session_cache_database = btree:${data_directory}/smtp_scache

smtpd_relay_restrictions = permit_mynetworks permit_sasl_authenticated reject_unauth_destination
myhostname = *****THISMAILSRVHOSTNAME*****
alias_maps = hash:/etc/aliases
alias_database = hash:/etc/aliases
myorigin = /etc/mailname
mydestination = $myhostname, localhost
relayhost =
mynetworks = 127.0.0.0/8 [::ffff:127.0.0.0]/104 [::1]/128
mailbox_size_limit = 0
recipient_delimiter = +
inet_interfaces = all
inet_protocols = all
maximal_queue_lifetime = 30d

relay_recipient_maps =
relay_domains = hash:/etc/postfix/relaydomains
transport_maps = hash:/etc/postfix/transportmaps
```

## Cronjobs

!!! warning "Run the cronjob with a user privilegued to change the /etc/postfix folder and read DNS Folders if you use DNS Synchronisation. It is recommended to run this cronjobs as root and store the software on a seperate server."

To ensure proper functionality, set up the following cronjobs:

- **Daily IP Blacklist Reset**:
  ```sh
  php _webroot/_cronjob/daily.php >/dev/null 2>&1
  ```

- **Postfix Configuration Sync** (recommended every 30 minutes):
  ```sh
  php _webroot/_cronjob/sync.php >/dev/null 2>&1
  ```


## Initial Login

**Important**: Change the initial password after you have successfully logged in for the first time!

- **Username**: `admin`
- **Password**: `changeme`

---

**Next Steps**: For further configuration details, refer to the Setup Parameters section and the Initial Login information below.
