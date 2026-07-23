# Build Commands - Sixteen Theme

**Type**: Build & Deployment Documentation  
**Status**: ✅ Documented  
**Last Update**: 2026-05-26 - Added Canonical Auth Pattern Rule
**Related**: [[00-index]], [[layout-hierarchy]], [[vite-configuration-rules]]

---

## 🎯 Overview

The Sixteen theme uses **Vite** for building CSS and JavaScript assets.

---

## 🔑 Canonical Patterns

### Auth Pages — Use Filament Livewire Widgets (NOT raw Volt)

**✅ CORRECT:**
```blade
<x-layouts.app>
    <x-slot name="title">
        {{ __('user::auth.register.page.meta_title.label') }}
    </x-slot>
    @livewire(\Modules\User\Filament\Widgets\Auth\RegisterWidget::class)
</x-layouts.app>
```

**❌ WRONG:**
```blade
<x-layouts.guest>
    @volt('auth.register')
    <!-- manual form + validation -->
    @endvolt
</x-layouts.guest>
```

**Rule**: Auth forms must use `Modules\User\Filament\Widgets\Auth\*` widgets.
Never use raw Volt forms for authentication — you'll bypass validation, translation, and security.

---

## 📦 Build Process

### Step 1: Composer Dependencies
```bash
cd laravel/Themes/Sixteen
composer update -W
```

**What it does**:
- Updates PHP dependencies
- Ensures compatibility with Laravel packages
- Updates `composer.lock` file

**Expected output**:
```
Loading composer repositories with package information
Updating dependencies
Nothing to modify in lock file
Writing lock file
Installing dependencies from lock file
```

### Step 2: NPM Dependencies
```bash
npm install
```

**What it does**:
- Installs JavaScript dependencies
- Installs Tailwind CSS, Alpine.js, PostCSS
- Creates `node_modules/` directory

**Expected output**:
```
up to date, audited 540 packages in 3s
85 packages are looking for funding
```

### Step 3: Build Assets
```bash
npm run build
```

**What it does**:
- Compiles Tailwind CSS
- Bundles JavaScript with Alpine.js
- Generates `manifest.json`
- Outputs to `public/` directory

**Expected output**:
```
vite v7.3.1 building client environment for production...
✓ 3 modules transformed.
public/manifest.json              0.33 kB │ gzip:  0.17 kB
public/assets/app-C1huuq--.css  145.43 kB │ gzip: 21.50 kB
public/assets/app-DR9d2vyK.js    46.65 kB │ gzip: 16.76 kB
✓ built in 3.90s
```

**Files generated**:
- `public/manifest.json` - Vite manifest for asset versioning
- `public/assets/app-*.css` - Compiled CSS
- `public/assets/app-*.js` - Bundled JavaScript

### Step 4: Copy to Public Directory
```bash
npm run copy
```

**What it does**:
- Copies built assets from `public/` to `../../public_html/themes/Sixteen/`
- Makes assets accessible to Laravel

**Expected output**:
```
> sixteen@0.0.0 copy
> cp -r ./public/* ../../public_html/themes/Sixteen/
```

**Files copied**:
```
public_html/themes/Sixteen/
├── manifest.json          ← Vite manifest
├── assets/
│   ├── app-*.css         ← Compiled CSS
│   └── app-*.js          ← Bundled JS
├── dist/                  ← Additional assets
└── images/                ← Theme images
```

---

## 🔧 Complete Build Command (One-Liner)

```bash
cd laravel/Themes/Sixteen && composer update -W && npm install && npm run build && npm run copy
```

---

## 📁 File Structure

### Source Files
```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   └── app.css           ← Tailwind CSS source
│   └── js/
│       └── app.js            ← JavaScript source (Alpine.js)
├── public/                   ← Build output (temporary)
│   ├── manifest.json
│   └── assets/
└── package.json              ← NPM configuration
```

### Public Files (After Build)
```
public_html/themes/Sixteen/
├── manifest.json             ← Used by @vite() helper
├── assets/
│   ├── app-C1huuq--.css     ← Compiled CSS
│   └── app-DR9d2vyK.js      ← Bundled JS
├── dist/                     ← Additional assets
└── images/                   ← Theme images
```

---

## 🎯 Vite Configuration

### vite.config.js
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    build: {
        outDir: 'public',
        emptyOutDir: false,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            publicDirectory: 'public',
            buildDirectory: 'assets',
        }),
    ],
});
```

### package.json Scripts
```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "copy": "cp -r ./public/* ../../public_html/themes/Sixteen/"
    }
}
```

---

## 🎨 Blade Usage

### In Layout Files
```blade
{{-- resources/views/layouts/main.blade.php --}}
<head>
    @filamentStyles
    @vite(['resources/css/app.css'], 'themes/Sixteen')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/cookie-consent/css/cookie-consent.css') }}">
    @stack('styles')
</head>
<body>
    {{ $slot }}
    @yield('body')
    <livewire:toast />
    @livewire('notifications')
    @filamentScripts
    @vite(['resources/js/app.js'], 'themes/Sixteen')
    @stack('scripts')
</body>
```

### CRITICAL: No Inline Scripts or CDN Imports

**NEVER**:
```blade
<!-- ❌ NO inline JavaScript - violates CSP -->
<script>
    // This is legacy code - WIPE IT OUT
</script>

<!-- ❌ NO CDN imports - bootstrap-italia is local -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-italia@...">

<!-- ❌ NO separate Vite entry for theme JS -->
@vite(['resources/js/theme/header-mobile-nav.js'], 'themes/Sixteen')
```

**Why**:
- Vite compiles all JS into `resources/js/app.js` 
- `header-mobile-nav.js` is already imported in `app.js` line 16
- CDN imports break CSP and caching
- Inline scripts bypass Vite optimization

### How @vite() Works
1. Reads `public_html/themes/Sixteen/manifest.json`
2. Maps `resources/css/app.css` → `assets/app-C1huuq--.css`
3. Maps `resources/js/app.js` → `assets/app-DR9d2vyK.js`
4. Adds cache-busting hashes to filenames

---

## ⚠️ Common Issues

### Issue 1: Vite Manifest Not Found
**Error**: `Vite manifest not found at: public_html/themes/Sixteen/manifest.json`

**Solution**:
```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```

**Why**: The manifest.json is generated during build and must be copied to the public directory.

### Issue 2: Assets Not Loading
**Error**: CSS/JS files return 404

**Solution**:
1. Check `npm run copy` was executed
2. Verify files exist in `public_html/themes/Sixteen/`
3. Clear Laravel cache: `php artisan cache:clear`

### Issue 3: CSS @import Order
**Warning**: `@import rules must precede all rules aside from @charset and @layer`

**Solution**: Move `@import` statements to top of `app.css`:
```css
/* ✅ CORRECT */
@import url('https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap');
@import "tailwindcss";

/* Rest of CSS */
```

---

## 🧪 Verification Checklist

After build, verify:

- [ ] `public_html/themes/Sixteen/manifest.json` exists
- [ ] `public_html/themes/Sixteen/assets/*.css` exists
- [ ] `public_html/themes/Sixteen/assets/*.js` exists
- [ ] Page loads without Vite errors
- [ ] CSS styles are applied correctly
- [ ] JavaScript (Alpine.js) works
- [ ] No console errors in browser

---

## 🚀 Development vs Production

### Development (npm run dev)
```bash
npm run dev
```
- Hot Module Replacement (HMR)
- No manifest.json needed
- Direct file serving
- Faster rebuilds

### Production (npm run build)
```bash
npm run build && npm run copy
```
- Minified CSS/JS
- Cache-busting hashes
- manifest.json required
- Optimized for performance

---

## 📊 Build Output Example

### manifest.json
```json
{
  "resources/css/app.css": {
    "file": "assets/app-C1huuq--.css",
    "src": "resources/css/app.css",
    "isEntry": true
  },
  "resources/js/app.js": {
    "file": "assets/app-DR9d2vyK.js",
    "src": "resources/js/app.js",
    "isEntry": true
  }
}
```

### File Sizes
```
public/manifest.json              0.33 kB │ gzip:  0.17 kB
public/assets/app-C1huuq--.css  145.43 kB │ gzip: 21.50 kB
public/assets/app-DR9d2vyK.js    46.65 kB │ gzip: 16.76 kB
```

---

## 🔗 Related Documentation

### Internal
- [[00-index]] - Main documentation index
- [[layout-hierarchy]] - Layout architecture
- [[vite-configuration-guide]] - Vite configuration (WHY `outDir: './public'`)
- [[components-directory-structure]] - Component organization

### External
- [Vite Documentation](https://vitejs.dev/)
- [Laravel Vite Plugin](https://laravel.com/docs/vite)
- [Tailwind CSS](https://tailwindcss.com/docs)

---

## 📝 Changelog

| Date | Version | Change | Author |
|------|---------|--------|--------|
| 2026-04-01 | 1.0 | Initial documentation | AI Agent Team |
| 2026-04-01 | 1.1 | Added troubleshooting section | AI Agent Team |

---

*Documentation conforme agli standard Laraxot - DRY + KISS compliant*
