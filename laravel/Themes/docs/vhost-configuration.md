# 🌐 Theme VHost Configuration Guide

> **Last Updated**: 2026-03-31  
> **Status**: ✅ Active  
> **Environment**: Local Development

---

## 📋 Overview

This guide covers the Apache VirtualHost configuration specific to **theme development** for the FixCity platform.

### Primary Domain

- **Domain**: `fixcity.local`
- **Document Root**: `public_html/`
- **Active Theme**: **Sixteen** (Bootstrap Italia compliant)
- **Available Theme**: **TwentyOne** (Modern Tailwind)

---

## 📁 Configuration Files

### Master Configuration

**Location**: `laravel/config/vhost/fixcity.local.conf`

This is the **Single Source of Truth (SSOT)** for vhost configuration.

### Related Documentation

- **Project Docs**: [`docs/project/vhost-configuration.md`](../../../../docs/project/vhost-configuration.md)
- **Modules Docs**: [`../../Modules/docs/vhost-configuration.md`](../../Modules/docs/vhost-configuration.md)

---

## 🎨 Theme-Specific Configuration

### Document Root Structure

```
public_html/                    ← Web root
├── index.php                  ← Laravel entry point
├── .htaccess                  ← URL rewriting
├── themes/                    ← Theme assets (build output)
│   ├── Sixteen/              ← Active theme
│   │   ├── css/
│   │   ├── js/
│   │   └── assets/
│   └── TwentyOne/            ← Available theme
│       ├── css/
│       ├── js/
│       └── assets/
└── fonts/                     ← Shared fonts
    └── filament/
```

### Theme Source Code

```
laravel/Themes/                ← Theme source code
├── Sixteen/
│   ├── resources/
│   │   ├── css/
│   │   ├── js/
│   │   └── views/
│   ├── tailwind.config.js
│   └── vite.config.js
└── TwentyOne/
    ├── resources/
    │   ├── css/
    │   ├── js/
    │   └── views/
    ├── tailwind.config.js
    └── vite.config.js
```

---

## 🚀 Setup Instructions

### 1. Enable VirtualHost

```bash
# Copy to Apache sites-available
sudo cp laravel/config/vhost/fixcity.local.conf /etc/apache2/sites-available/

# Enable site
sudo a2ensite fixcity.local.conf

# Reload Apache
sudo systemctl reload apache2
```

### 2. Update Hosts File

Edit `/etc/hosts`:

```bash
127.0.0.1    fixcity.local
127.0.0.1    www.fixcity.local
```

### 3. Build Theme Assets

```bash
# Navigate to Laravel directory
cd laravel

# Install dependencies
npm install

# Build active theme (Sixteen)
npm run build

# Or build specific theme
npm run build:sixteen
npm run build:twentyone
```

### 4. Copy to public_html

```bash
# Copy built assets to public_html
npm run copy

# Or manually
cp -r Themes/Sixteen/public/* ../public_html/themes/Sixteen/
```

### 5. Verify

```bash
# Test configuration
sudo apache2ctl configtest

# Check vhost is enabled
apache2ctl -S | grep fixcity

# Test in browser
curl -I http://fixcity.local
```

---

## 🏗️ Theme Architecture

### Request Flow with Themes

```
Browser Request (fixcity.local)
    ↓
Apache vhost (port 80)
    ↓
DocumentRoot (public_html/)
    ↓
index.php (Laravel entry point)
    ↓
Laravel Application (laravel/)
    ↓
Theme Configuration (config/localhost/xra.php)
    ↓
Active Theme: Sixteen
    ↓
Theme Views (Themes/Sixteen/resources/views/)
    ↓
Theme Assets (public_html/themes/Sixteen/)
    ↓
Response with themed content
```

### Theme Selection

Active theme is configured in `laravel/config/localhost/xra.php`:

```php
return [
    'pub_theme' => 'sixteen',  // Change to 'twentyone' to switch
    // ...
];
```

### Asset Loading

```blade
{{-- In Blade templates --}}
@vite([
    'themes/sixteen/resources/css/app.css',
    'themes/sixteen/resources/js/app.js'
])

{{-- Or with theme helper --}}
{{ theme_asset('css/style.css') }}
```

---

## 🎭 Theme Comparison

| Feature | Sixteen | TwentyOne |
|---------|---------|-----------|
| **Status** | ✅ Active | 📦 Available |
| **Framework** | Bootstrap Italia | Tailwind CSS v4 |
| **Build** | Vite | Vite |
| **Compliance** | AGID, WCAG 2.1 AA | Modern UX |
| **Use Case** | PA, Institutions | Prediction Markets |
| **Design** | Institutional | Cinematic/Kinetic |
| **Dark Mode** | ❌ | ✅ |
| **GSAP** | ❌ | ✅ |

---

## 🔧 Development Workflow

### Local Development

```bash
# Start Vite dev server
cd laravel
npm run dev

# Access in browser
# http://fixcity.local
```

### Hot Module Replacement

Vite HMR works with the vhost configuration:

```javascript
// vite.config.js
export default {
  server: {
    host: 'fixcity.local',
    port: 5173,
    hmr: {
      host: 'fixcity.local'
    }
  }
}
```

### Build Process

```bash
# Development build
npm run build

# Production build (optimized)
npm run build:production

# Verify build
npm run quality
```

---

## 🎨 Theme-Specific Features

### Sixteen (Active Theme)

**Design System**: Bootstrap Italia / Design Comuni

**Key Features**:
- AGID compliant components
- WCAG 2.1 AA accessibility
- Italian Public Administration standards
- Institutional layouts

**Documentation**: [`Sixteen/docs/`](../Sixteen/docs/)

**Key Documents**:
- [`design-comuni/README.md`](../Sixteen/docs/design-comuni/README.md)
- [`AGID_CHECKLIST.md`](../Sixteen/docs/AGID_CHECKLIST.md)
- [`ACCESSIBILITY_IMPLEMENTATION_GUIDE.md`](../Sixteen/docs/ACCESSIBILITY_IMPLEMENTATION_GUIDE.md)

### TwentyOne (Available Theme)

**Design System**: Custom Tailwind CSS

**Key Features**:
- Cinematic UX
- Kinetic animations
- GSAP integration
- Dark/Light mode
- Particle effects

**Documentation**: [`TwentyOne/docs/`](../TwentyOne/docs/)

**Key Documents**:
- [`ZEN_ARCHITECTURE_PHILOSOPHY.md`](../TwentyOne/docs/ZEN_ARCHITECTURE_PHILOSOPHY.md)
- [`KINETIC_WEB_DESIGN_SPEC.md`](../TwentyOne/docs/KINETIC_WEB_DESIGN_SPEC.md)
- [`filament-widget-best-practices.md`](../TwentyOne/docs/filament-widget-best-practices.md)

---

## 🔒 Security Considerations

### Development Only

⚠️ **WARNING**: This configuration is for **LOCAL DEVELOPMENT ONLY**.

For production:
1. Use HTTPS (SSL/TLS)
2. Add security headers
3. Enable asset versioning
4. Use CDN for static assets

### Security Headers (Optional)

```apache
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

---

## 📊 Theme Performance

### Asset Optimization

```bash
# Check bundle sizes
npm run build -- --stats

# Analyze with webpack-bundle-analyzer
npm run analyze
```

### Performance Targets

| Metric | Target |
|--------|--------|
| CSS Bundle | < 300KB |
| JS Bundle | < 200KB |
| Lighthouse | > 90 |
| FCP | < 1.5s |
| TTI | < 3.5s |

---

## 🧪 Testing

### Verify Theme Loading

```bash
# Check theme assets are accessible
curl http://fixcity.local/themes/sixteen/css/app.css

# Should return CSS content
```

### Check VHost Configuration

```bash
# List all vhosts
apache2ctl -S

# Test syntax
apache2ctl configtest

# Check enabled sites
ls -la /etc/apache2/sites-enabled/ | grep fixcity
```

---

## 🔗 Related Documentation

### Internal

- [Project VHost Docs](../../../../docs/project/vhost-configuration.md) - Complete guide
- [Modules VHost Docs](../../Modules/docs/vhost-configuration.md) - Module-specific
- [Sixteen Theme Docs](../Sixteen/docs/) - Active theme
- [TwentyOne Theme Docs](../TwentyOne/docs/) - Available theme

### External

- [Apache VirtualHost Docs](https://httpd.apache.org/docs/2.4/vhosts/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Vite Documentation](https://vitejs.dev/)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/)
- [Tailwind CSS](https://tailwindcss.com/)

---

## 📝 Maintenance

### Update VHost Config

1. Edit `laravel/config/vhost/fixcity.local.conf`
2. Copy to Apache: `sudo cp laravel/config/vhost/fixcity.local.conf /etc/apache2/sites-available/`
3. Reload: `sudo systemctl reload apache2`

### Switch Active Theme

1. Edit `laravel/config/localhost/xra.php`
2. Change `'pub_theme' => 'sixteen'` to `'twentyone'`
3. Clear cache: `php artisan config:clear`
4. Rebuild theme: `npm run build`

### Backup

```bash
sudo cp /etc/apache2/sites-available/fixcity.local.conf \
        /etc/apache2/sites-available/fixcity.local.conf.backup.$(date +%Y%m%d)
```

---

## 📞 Support

- **Slack**: #frontend #devops
- **GitHub**: Issue with label `infrastructure` or `theme`
- **Team**: Frontend + DevOps Teams

---

**Maintainer**: Frontend Team  
**Last Review**: 2026-03-31  
**Next Review**: 2026-06-30  
**Status**: ✅ Active
