# 🌐 VHost Configuration Guide

> **Last Updated**: 2026-03-31  
> **Status**: ✅ Active  
> **Environment**: Local Development

---

## 📋 Overview

This guide covers the Apache VirtualHost configuration for FixCity local development environments.

### Primary Domain

<<<<<<< HEAD
<<<<<<< HEAD
- **Domain**: `fixcity.local`
- **Alias**: `www.fixcity.local`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- **Domain**: `fixcity.local`
- **Alias**: `www.fixcity.local`
>>>>>>> a988596b (first)
- **Domain**: `ptv.local`
- **Alias**: `www.ptv.local`
- **Document Root**: `public_html/`
- **Port**: 80 (HTTP)

---

## 📁 Configuration Files

### Master Configuration

<<<<<<< HEAD
<<<<<<< HEAD
**Location**: `laravel/config/vhost/fixcity.local.conf`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Location**: `laravel/config/vhost/fixcity.local.conf`
>>>>>>> a988596b (first)
**Location**: `laravel/config/vhost/ptv.local.conf`

This is the **Single Source of Truth (SSOT)** for vhost configuration.

### Related Documentation

- **Project Docs**: [`docs/project/vhost-configuration.md`](../../../../docs/project/vhost-configuration.md)
- **Themes Docs**: [`../../Themes/docs/vhost-configuration.md`](../../Themes/docs/vhost-configuration.md)

---

## 🚀 Setup Instructions

### 1. Enable VirtualHost

```bash
# Copy to Apache sites-available
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
sudo cp laravel/config/vhost/fixcity.local.conf /etc/apache2/sites-available/

# Enable site
sudo a2ensite fixcity.local.conf
<<<<<<< HEAD
=======
sudo cp laravel/config/vhost/ptv.local.conf /etc/apache2/sites-available/

# Enable site
sudo a2ensite ptv.local.conf
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

# Reload Apache
sudo systemctl reload apache2
```

### 2. Update Hosts File

Edit `/etc/hosts`:

```bash
<<<<<<< HEAD
<<<<<<< HEAD
127.0.0.1    fixcity.local
127.0.0.1    www.fixcity.local
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
127.0.0.1    fixcity.local
127.0.0.1    www.fixcity.local
>>>>>>> a988596b (first)
127.0.0.1    ptv.local
127.0.0.1    www.ptv.local
```

### 3. Verify

```bash
# Test configuration
sudo apache2ctl configtest

# Check vhost is enabled
<<<<<<< HEAD
<<<<<<< HEAD
apache2ctl -S | grep fixcity
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
apache2ctl -S | grep fixcity
>>>>>>> a988596b (first)
apache2ctl -S | grep ptv
```

---

## 🏗️ Architecture

### Directory Structure

```
<<<<<<< HEAD
<<<<<<< HEAD
base_fixcity_fila5/
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
base_fixcity_fila5/
>>>>>>> a988596b (first)
base_ptv_fila5/
├── public_html/              ← Document Root
│   ├── index.php            ← Entry point
│   └── .htaccess            ← URL rewriting
├── laravel/                  ← Application code
│   ├── config/
│   │   └── vhost/
<<<<<<< HEAD
<<<<<<< HEAD
│   │       └── fixcity.local.conf  ← VHost config
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
│   │       └── fixcity.local.conf  ← VHost config
>>>>>>> a988596b (first)
│   │       └── ptv.local.conf  ← VHost config
│   ├── Modules/             ← All modules
│   └── Themes/              ← All themes
└── docs/
    └── project/
        └── vhost-configuration.md  ← Full documentation
```

### Request Flow

```
<<<<<<< HEAD
<<<<<<< HEAD
Browser → Apache vhost (fixcity.local:80)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Browser → Apache vhost (fixcity.local:80)
>>>>>>> a988596b (first)
Browser → Apache vhost (ptv.local:80)
    ↓
DocumentRoot (public_html/)
    ↓
.htaccess (URL rewriting)
    ↓
index.php (Laravel entry point)
    ↓
laravel/ (application code)
    ↓
Modules/ + Themes/
```

---

## ⚙️ Configuration Highlights

### Key Directives

```apache
<VirtualHost *:80>
    ServerName ptv.local
    ServerAlias www.ptv.local
    DocumentRoot /var/www/_bases/base_ptv_fila5/public_html
    
    <Directory /var/www/_bases/base_ptv_fila5/public_html>
        Options -Indexes +FollowSymLinks +MultiViews
        AllowOverride All
        Require all granted
        
        # Laravel routing
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php [L]
        </IfModule>
    </Directory>
    
<<<<<<< HEAD
<<<<<<< HEAD
    ErrorLog ${APACHE_LOG_DIR}/fixcity_local_error.log
    CustomLog ${APACHE_LOG_DIR}/fixcity_local_access.log combined
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    ErrorLog ${APACHE_LOG_DIR}/fixcity_local_error.log
    CustomLog ${APACHE_LOG_DIR}/fixcity_local_access.log combined
>>>>>>> a988596b (first)
    ErrorLog ${APACHE_LOG_DIR}/ptv_local_error.log
    CustomLog ${APACHE_LOG_DIR}/ptv_local_access.log combined
</VirtualHost>
```

### Important Notes

1. **Document Root MUST be `public_html/`** - Never point to `laravel/` directly
2. **AllowOverride All** - Required for Laravel `.htaccess`
3. **mod_rewrite** - Essential for Laravel routing
4. **Directory permissions** - Must be readable by Apache

---

## 🔧 Troubleshooting

### Common Issues

| Issue | Solution |
|-------|----------|
| 403 Forbidden | Check directory permissions |
| 500 Error | Check Laravel logs |
| Site not loading | Verify `/etc/hosts` entry |
| mod_rewrite not working | `sudo a2enmod rewrite` |

### Logs

```bash
# Apache error log
<<<<<<< HEAD
<<<<<<< HEAD
tail -f /var/log/apache2/fixcity_local_error.log
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
tail -f /var/log/apache2/fixcity_local_error.log
>>>>>>> a988596b (first)
tail -f /var/log/apache2/ptv_local_error.log

# Laravel log
tail -f laravel/storage/logs/laravel.log
```

---

## 📊 Module Integration

### All Modules Use Same VHost

Every module in `laravel/Modules/` is accessible through the same vhost:

- **Xot**: `ptv.local/admin` (Filament)
- **User**: `ptv.local/login`, `ptv.local/register`
- **Cms**: `ptv.local/pages/*` (CMS pages)
- **Blog**: `ptv.local/blog/*`
- **Fixcity**: `ptv.local/tickets/*`
- **All others**: Via their respective routes

### Theme Selection

Active theme is configured in `laravel/config/localhost/xra.php`:

```php
'pub_theme' => 'sixteen',  // or 'twentyone'
```

Theme assets are served from:
- `public_html/themes/{theme-name}/`

---

## 🔒 Security Notes

### Development Only

⚠️ **WARNING**: This configuration is for **LOCAL DEVELOPMENT ONLY**.

For production:
1. Use HTTPS (SSL/TLS)
2. Add security headers
3. Restrict access by IP
4. Use environment-specific configs

### Security Headers (Optional)

```apache
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>
```

---

## 📝 Maintenance

### Update VHost Config

<<<<<<< HEAD
<<<<<<< HEAD
1. Edit `laravel/config/vhost/fixcity.local.conf`
2. Copy to Apache: `sudo cp laravel/config/vhost/fixcity.local.conf /etc/apache2/sites-available/`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
1. Edit `laravel/config/vhost/fixcity.local.conf`
2. Copy to Apache: `sudo cp laravel/config/vhost/fixcity.local.conf /etc/apache2/sites-available/`
>>>>>>> a988596b (first)
1. Edit `laravel/config/vhost/ptv.local.conf`
2. Copy to Apache: `sudo cp laravel/config/vhost/ptv.local.conf /etc/apache2/sites-available/`
3. Reload: `sudo systemctl reload apache2`

### Backup

```bash
<<<<<<< HEAD
<<<<<<< HEAD
sudo cp /etc/apache2/sites-available/fixcity.local.conf \
        /etc/apache2/sites-available/fixcity.local.conf.backup.$(date +%Y%m%d)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
sudo cp /etc/apache2/sites-available/fixcity.local.conf \
        /etc/apache2/sites-available/fixcity.local.conf.backup.$(date +%Y%m%d)
>>>>>>> a988596b (first)
sudo cp /etc/apache2/sites-available/ptv.local.conf \
        /etc/apache2/sites-available/ptv.local.conf.backup.$(date +%Y%m%d)
```

---

## 🧪 Testing

### Verify Configuration

```bash
# List all vhosts
apache2ctl -S

# Test syntax
apache2ctl configtest

# Check enabled sites
<<<<<<< HEAD
<<<<<<< HEAD
ls -la /etc/apache2/sites-enabled/ | grep fixcity
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
ls -la /etc/apache2/sites-enabled/ | grep fixcity
>>>>>>> a988596b (first)
ls -la /etc/apache2/sites-enabled/ | grep ptv
```

### Test Domain

```bash
# Ping test
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
ping fixcity.local

# Curl test
curl -I http://fixcity.local
<<<<<<< HEAD
=======
ping ptv.local

# Curl test
curl -I http://ptv.local
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
```

---

## 🔗 Related Documentation

### Internal

- [Project VHost Docs](../../../../docs/project/vhost-configuration.md) - Complete guide
- [Themes VHost Docs](../../Themes/docs/vhost-configuration.md) - Theme-specific
- [Deployment Guide](../../../../docs/deployment/README.md)
- [Environment Setup](../../../../docs/project/environment-setup.md)

### External

- [Apache VirtualHost Docs](https://httpd.apache.org/docs/2.4/vhosts/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [mod_rewrite Guide](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)

---

## 📞 Support

- **Slack**: #devops
- **GitHub**: Issue with label `infrastructure`
- **Team**: DevOps Team

---

**Maintainer**: DevOps Team  
**Last Review**: 2026-03-31  
**Next Review**: 2026-06-30  
**Status**: ✅ Active
