---
title: Mail Configuration Handlers Guide
description: Complete guide to configuring mail drivers in Laravel with examples for multiple mail services
category: procedures
date: 2026-07-30
keywords: mail, smtp, mailtrap, mailgun, sendgrid, laravel
---

## Overview

This guide covers configuration options for various mail services and drivers in Laravel applications.

## Mail Drivers & Services

### 1. Mailtrap

Mailtrap is useful for development and testing email functionality.

```env
MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME=Example
```

### 2. Google Mail (Gmail)

Configuration for sending emails via Gmail SMTP.

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME=ENTER_YOUR_EMAIL_ADDRESS(GMAIL)
MAIL_PASSWORD=ENTER_YOUR_GMAIL_PASSWORD
MAIL_ENCRYPTION=ssl
```

**Note:** For Gmail, you may need to use an [app password](https://support.google.com/accounts/answer/185833) instead of your regular password.

### 3. Mailgun

Mailgun provides reliable email delivery at scale.

Configuration: [Mailgun Laravel Setup](https://devdojo.com/devdojo/sending-emails-with-laravel-and-mailgun)

```env
MAIL_DRIVER=mailgun
MAILGUN_DOMAIN=mg.YOUR_DOMAIN.com
MAILGUN_SECRET=YOUR_KEY_HERE
```

### 4. Mandrill

Transactional email service by Mailchimp.

[Mandrill Service](https://mandrillapp.com/)

### 5. SparkPost

High-volume email delivery service.

### 6. Amazon SES (Simple Email Service)

AWS email sending service for production environments.

### 7. Postmark

[Postmark Service](https://postmarkapp.com/)

### 8. Standard SMTP

Configuration for local SMTP or custom SMTP servers.

```env
MAIL_DRIVER=smtp
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@sito.tld
MAIL_FROM_NAME=Sito
```

### 9. SendCloud

China-based email service provider.

- [Laravel SendCloud](https://github.com/kvzn/laravel-sendcloud)
- [Alternative Implementation](https://github.com/nauxliu/Laravel-SendCloud)
- [Shipping Integration](https://github.com/deniztezcan/laravel-sendcloud-shipping)

### 10. DirectMail

Direct mail solutions for Laravel.

- [Switchable Mail Driver](https://github.com/kvzn/laravel-switchable-mail)
- [DirectMail Driver](https://github.com/kvzn/laravel-directmail)
- [Aliyun Direct Mail](https://github.com/HyanCat/aliyun-direct-mail)

### 11. Twilio

SMS and communication services integrated with Laravel.

[Twilio + SendGrid Queue Emails Tutorial](https://www.twilio.com/blog/how-queue-emails-laravel-php-twilio-sendgrid)

### 12. SendGrid

Popular email delivery service.

- [SendGrid Laravel Integration](https://codegits.com/sendgrid-laravel-mail/)
- [SignUp](https://signup.sendgrid.com/)

### 13. Zoho Mail

Zoho's email service.

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=465
MAIL_USERNAME=test@zoho.com
MAIL_PASSWORD=123456
MAIL_ENCRYPTION=ssl
```

### 14. Sendmail

Traditional sendmail configuration.

### 15. Swift Mailer

Mail logging and custom drivers with SwiftMailer.

- [Mail Logging in Laravel 5.3](https://www.sitepoint.com/mail-logging-in-laravel-5-3-extending-the-mail-driver/)
- [Custom Driver Example](https://gist.github.com/arthursalvtr/4c67f82eb3cb083449d3be3aa88e1ae1)
- [Packagist Package](https://packagist.org/packages/talandis/laravel-mail-driver)

### 16. Nexmo

Communication API for email and messaging.

[Driver-based Components in Laravel](https://itnext.io/building-driver-based-components-in-laravel-5b390dc25bd9)

## Email Queue Implementation

### Mail Queue Setup

For asynchronous email sending using queues:

```bash
# 3 easy steps to implement Laravel queue
https://www.zealousweb.com/3-easy-steps-to-implement-laravel-queue/
```

### Shared Hosting Queue Processing

```bash
# Run queue listener on shared hosting
flock -n /tmp/latavel_queues.lockfile /usr/bin/php /path/to/laravel/artisan queue:listen
```

Resources:
- [Database Queue on Shared Hosting](https://laracasts.com/discuss/channels/servers/database-queue-on-shared-hosting)
- [Queue:Work on Shared Hosting](https://stackoverflow.com/questions/46141652/running-laravel-queuework-on-a-shared-hosting)
- [Queue Processing Overview](https://orobogenius.medium.com/laravel-queue-processing-on-shared-hosting-dedd82d0267a)

## Additional Resources

### Email Best Practices

- [Statamic Email Configuration](https://statamic.dev/email)

### Setup Guides

- [Italian SMTP Setup Guide](https://gabrieleromanato.com/2020/06/laravel-inviare-le-e-mail-usando-lsmtp-locale)
- [Cloudways Email Setup](https://www.cloudways.com/blog/send-email-in-laravel/)
- [User Custom SMTP Settings](https://laravel-news.com/allowing-users-to-send-email-with-their-own-smtp-settings-in-laravel)

### Migration Resources

- [Mail Logging Extension](https://www.sitepoint.com/mail-logging-in-laravel-5-3-extending-the-mail-driver/)

### Legacy/Reference

- [PEAR Mail Queue Tutorial](https://pear.php.net/manual/en/package.mail.mail-queue.mail-queue.tutorial.php)

### Multiple Database Setup

- [Multiple Databases in Laravel](https://codegits.com/how-to-use-multiple-databases-in-laravel/)

## Best Practices

1. **Use environment variables** for sensitive credentials
2. **Test mail configuration** before production deployment
3. **Implement queue processing** for high-volume emails
4. **Use appropriate encryption** (SSL/TLS) for mail connections
5. **Monitor email delivery** and handle failures gracefully
6. **Keep credentials secure** and rotate regularly
7. **Use service providers** for switching between drivers dynamically
8. **Implement proper error handling** for mail failures
