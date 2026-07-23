# Vite @vite() Second Parameter - Complete Guide

**Date:** 2026-04-01  
**Status:** ✅ **Complete**  
**Priority:** 🔴 **Critical**  

---

## 🎯 Problem Statement

### Error Message

```
Vite manifest not found at: /var/www/_bases/base_fixcity_fila5/public_html/build/manifest.json
```

### Root Cause

```blade
{{-- ❌ WRONG - Missing second parameter --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Why it fails:**
- Laravel looks for manifest in `public_html/build/manifest.json`
- Theme assets are built to `public_html/themes/Sixteen/manifest.json`
- Path mismatch → Error!

### Solution

```blade
{{-- ✅ CORRECT - With second parameter --}}
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```

**Why it works:**
- Second parameter tells Laravel the build directory
- Laravel looks in `public_html/themes/Sixteen/manifest.json`
- Path matches → Success!

---

## 📚 How @vite() Works

### Default Behavior (Without Second Parameter)

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Laravel looks for:**
```
public_html/
└── build/
    └── manifest.json  ← Default location
```

**Manifest structure:**
```json
{
  "resources/css/app.css": {
    "file": "assets/app-abc123.css",
    "src": "resources/css/app.css"
  }
}
```

**Generated HTML:**
```html
<link rel="stylesheet" href="/build/assets/app-abc123.css">
```

---

### With Second Parameter (Theme Build)

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```

**Laravel looks for:**
```
public_html/
└── themes/
    └── Sixteen/
        └── manifest.json  ← Custom location
```

**Manifest structure:**
```json
{
  "resources/css/app.css": {
    "file": "assets/app-C1huuq--.css",
    "src": "resources/css/app.css"
  }
}
```

**Generated HTML:**
```html
<link rel="stylesheet" href="/themes/Sixteen/assets/app-C1huuq--.css">
```

---

## 🏗️ Theme Build Architecture

### Why Themes Need Second Parameter

```
┌─────────────────────────────────────────────────────────┐
│           MAIN LARAVEL APPLICATION                       │
│                                                          │
│  resources/                                              │
│  ├── css/app.css                                        │
│  └── js/app.js                                          │
│                                                          │
│  Vite Build: public_html/build/manifest.json            │
│  @vite([...])  ← Works without second param             │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│              THEME (Sixteen)                             │
│                                                          │
│  laravel/Themes/Sixteen/                                │
│  ├── resources/css/app.css                              │
│  ├── resources/js/app.js                                │
│  └── vite.config.js (outDir: './public')                │
│                                                          │
│  Build Process:                                          │
│  1. npm run build → themes/Sixteen/public/              │
│  2. npm run copy → public_html/themes/Sixteen/          │
│                                                          │
│  Manifest: public_html/themes/Sixteen/manifest.json     │
│  @vite([...], 'themes/Sixteen')  ← NEEDS second param   │
└─────────────────────────────────────────────────────────┘
```

---

## 🔧 Vite Configuration

### Theme vite.config.js

**File:** `laravel/Themes/Sixteen/vite.config.js`

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        outDir: './public',  // ✅ Builds to local public/ folder
        emptyOutDir: true,
        manifest: 'manifest.json',  // Creates manifest.json
        chunkFileNames: 'js/[name]-[hash].js',
        entryFileNames: 'js/[name]-[hash].js',
        assetFileNames: (assetInfo) => {
            const ext = assetInfo.name.split('.').pop();
            if (ext === 'css') return 'css/[name]-[hash].[ext]';
            if (['png','jpg','jpeg','gif','svg','webp','ico'].includes(ext)) 
                return 'images/[name]-[hash].[ext]';
            return 'assets/[name]-[hash].[ext]';
        },
    },
});
```

### Theme package.json

**File:** `laravel/Themes/Sixteen/package.json`

```json
{
  "scripts": {
    "build": "vite build",
    "copy": "cp -rv ./public/* ../../../public_html/themes/Sixteen/"
  }
}
```

**Build Flow:**
```
resources/css/app.css
  ↓
Vite Build (outDir: './public')
  ↓
public/manifest.json + public/assets/*.css
  ↓
npm run copy
  ↓
public_html/themes/Sixteen/manifest.json + public_html/themes/Sixteen/assets/*.css
```

---

## ✅ Correct Usage Examples

### Layout Files

**File:** `laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- ✅ CORRECT - Theme build requires second parameter --}}
    @vite(['resources/css/app.css'], 'themes/Sixteen')
    
</head>
<body>
    {{ $slot }}
    
    {{-- ✅ CORRECT - JS also needs second parameter --}}
    @vite(['resources/js/app.js'], 'themes/Sixteen')
</body>
</html>
```

### Page Files

**File:** `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`

```blade
<x-layouts.app>
    @volt('tests.view')
        <div>
            <x-page side="content" :slug="$pageSlug" :data="$data" />
        </div>
    @endvolt
</x-layouts.app>
```

**Note:** `app.blade.php` extends `main.blade.php`, so Vite is loaded once in main.

---

## ❌ Common Mistakes

### Mistake 1: Missing Second Parameter

```blade
{{-- ❌ WRONG --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- ✅ CORRECT --}}
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```

**Error:**
```
Vite manifest not found at: public_html/build/manifest.json
```

---

### Mistake 2: Wrong Second Parameter

```blade
{{-- ❌ WRONG - 'sixteen' (lowercase) --}}
@vite(['resources/css/app.css'], 'sixteen')

{{-- ❌ WRONG - 'Themes/Sixteen' (capital T) --}}
@vite(['resources/css/app.css'], 'Themes/Sixteen')

{{-- ✅ CORRECT - 'themes/Sixteen' (lowercase t) --}}
@vite(['resources/css/app.css'], 'themes/Sixteen')
```

**Why:** Laravel's `@vite()` uses the parameter as-is for the path.

---

### Mistake 3: Split CSS and JS Without Second Param

```blade
{{-- ❌ WRONG --}}
@vite(['resources/css/app.css'])
@vite(['resources/js/app.js'])

{{-- ✅ CORRECT --}}
@vite(['resources/css/app.css'], 'themes/Sixteen')
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

---

### Mistake 4: Using CDN or External URLs

```blade
{{-- ❌ WRONG --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/css/bootstrap-italia.min.css">

{{-- ✅ CORRECT --}}
@vite(['resources/css/app.css'], 'themes/Sixteen')
```

**Why:** We use Tailwind @apply, NOT Bootstrap Italia CDN.

---

## 🔍 Troubleshooting

### Check Manifest Location

```bash
# Check if manifest exists
ls -la /var/www/_bases/base_fixcity_fila5/public_html/themes/Sixteen/manifest.json

# Check manifest content
cat /var/www/_bases/base_fixcity_fila5/public_html/themes/Sixteen/manifest.json
```

### Rebuild Assets

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen

# 1. Update dependencies
composer update -W

# 2. Install NPM packages
npm install

# 3. Build Vite assets
npm run build

# 4. Copy to public_html
npm run copy
```

### Clear Caches

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel

# Clear view cache
php artisan view:clear

# Clear general cache
php artisan cache:clear

# Clear config cache
php artisan config:clear
```

---

## 📊 File Locations

### Theme Structure

```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       └── components/layouts/
│           ├── main.blade.php (has @vite with second param)
│           └── app.blade.php (extends main)
├── public/ (build output)
│   ├── manifest.json
│   └── assets/
└── vite.config.js (outDir: './public')
```

### Public HTML Structure

```
public_html/
├── build/ (main Laravel app)
│   └── manifest.json
└── themes/
    └── Sixteen/ (theme build)
        ├── manifest.json
        └── assets/
```

---

## 🎯 Key Takeaways

### Rule 1: Theme Assets Need Second Parameter

```blade
{{-- Theme CSS --}}
@vite(['resources/css/app.css'], 'themes/Sixteen')

{{-- Theme JS --}}
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

### Rule 2: Main App Assets Don't Need Second Parameter

```blade
{{-- Main Laravel app CSS --}}
@vite(['resources/css/app.css'])

{{-- Main Laravel app JS --}}
@vite(['resources/js/app.js'])
```

### Rule 3: Build in Theme Directory

```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```

### Rule 4: Manifest Must Exist

```bash
public_html/themes/Sixteen/manifest.json
```

---

## 📚 Related Documentation

- [Vite Manifest Fix Complete](VITE_MANIFEST_FIX_COMPLETE.md)
- [Layout Architecture](layout-architecture.md)
- [Build Commands Guide](build-commands-guide.md)
- [Master Documentation Index](../../../docs/MODULE_DOCS_INDEX.md)

---

## 🔗 External Resources

- [Laravel Vite Documentation](https://laravel.com/docs/vite)
- [Vite Documentation](https://vitejs.dev/)
- [Laravel Vite Plugin](https://github.com/laravel/vite-plugin)

---

**Status:** ✅ **Complete**  
**Last Updated:** 2026-04-01  
**Next Review:** When adding new themes  

🐮 **Vite @vite() Second Parameter Documented!**
