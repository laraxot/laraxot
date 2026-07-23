# Vite Manifest Error - Root Cause Analysis

## Problem Description

Error: `Vite manifest not found at: /var/www/_bases/base_fixcity_fila5/public_html/build/manifest.json`

## Theme Detection Process

1. **APP_URL** in `laravel/.env`: `http://fixcity.local`
2. **Domain extraction**: `fixcity.local` → explode by `.` → `['local', 'fixcity']` → reverse → `['fixcity', 'local']` → join with `/` → `fixcity/local`
3. **Config path**: `laravel/config/local/fixcity/xra.php`
4. **Theme name**: `'pub_theme' => 'Sixteen'` (in `xra.php`)

## Root Cause Analysis

### Why This Error Occurs

1. **Theme is built independently**: The Sixteen theme has its own `vite.config.js` with:
   - `outDir: './public'` → builds to `laravel/Themes/Sixteen/public/`
   - Built assets must be copied to `public_html/themes/Sixteen/`

2. **Missing second parameter in @vite()**: 
   - `@vite(['resources/css/app.css', 'resources/js/app.js'])` looks in `public_html/build/manifest.json`
   - `@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')` looks in `public_html/themes/Sixteen/manifest.json`

3. **The manifest.json in public_html/build/ was stale**: It contained PWA config, not Vite build entries

### The Solution

```bash
cd laravel/Themes/Sixteen
composer update -W
npm install
npm run build
npm run copy
```

### Why This Works

1. **composer update -W**: Updates theme dependencies
2. **npm install**: Installs Vite, Tailwind, Alpine.js, etc.
3. **npm run build**: Runs `vite build` which creates:
   - `laravel/Themes/Sixteen/public/manifest.json` (Vite manifest)
   - `laravel/Themes/Sixteen/public/assets/app-*.css`
   - `laravel/Themes/Sixteen/public/assets/app-*.js`
4. **npm run copy**: Copies built assets to `public_html/themes/Sixteen/`

### Correct @vite() Usage in Layouts

In `laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php`:

```blade
@vite(['resources/css/app.css'], 'themes/Sixteen')
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

**Key point**: The second parameter `'themes/Sixteen'` tells Laravel to look for the manifest in `public_html/themes/Sixteen/manifest.json` instead of `public_html/build/manifest.json`.

## Architecture Summary

```
laravel/Themes/Sixteen/
├── vite.config.js          # Theme's Vite config
├── package.json            # Theme's npm dependencies
├── public/                 # Build output (gitignored)
│   ├── manifest.json       # Vite manifest
│   └── assets/             # Compiled CSS/JS
└── resources/
    ├── css/app.css
    └── js/app.js

public_html/themes/Sixteen/  # Published theme assets
├── manifest.json           # Copied from theme public/
└── assets/                 # Copied from theme public/assets/
```

## DRY + KISS

- **DRY**: Theme build process is automated via npm scripts
- **KISS**: Simple copy command after build, no complex deployment
- **Consistency**: Always use second parameter for theme assets

## Related Documentation

- `docs/VITE_MANIFEST_FIX_COMPLETE.md`
- `docs/vite-configuration-rules.md`
- `docs/layout-architecture.md`

## References

- Laravel Vite: https://vitejs.dev/
- Laravel Vite Plugin: https://laravel.com/docs/vite