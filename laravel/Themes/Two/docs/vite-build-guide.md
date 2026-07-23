# Vite Build Guide - Theme Two

## Overview
Theme Two uses Vite with Laravel Vite Plugin to build CSS and JS assets. The build output goes to `public_html/themes/Two/` so that the `@vite()` directive can find the manifest.

## Architecture

```
laravel/Themes/Two/
├── vite.config.js          # Vite configuration
├── package.json            # NPM dependencies
├── resources/
│   ├── css/app.css         # Main CSS entry (Tailwind + Filament)
│   └── js/app.js           # Main JS entry
└── ...

public_html/
└── themes/
    └── Two/
        ├── manifest.json   # Vite manifest (auto-generated)
        └── assets/         # Built CSS/JS files
```

## Vite Configuration

### Key Parameters

| Parameter | Value | Description |
|-----------|-------|-------------|
| `publicDirectory` | `'../../../public_html'` | Points to the web root |
| `buildDirectory` | `'themes/Two'` | Subdirectory within publicDirectory for output |
| `input` | `['resources/css/app.css', 'resources/js/app.js']` | Entry points |

### Why These Paths?
From `laravel/Themes/Two/` to `public_html/`:
- Up 1: `Two/` -> `Themes/`
- Up 2: `Themes/` -> `laravel/`
- Up 3: `laravel/` -> project root
- Then into `public_html/`

Result: `../../../public_html`

The `buildDirectory: 'themes/Two'` creates a subdirectory so the final manifest is at `public_html/themes/Two/manifest.json`.

## @vite Directive

Every `@vite()` call in Blade templates MUST include the second parameter matching `buildDirectory`:

```blade
@vite('resources/css/app.css', 'themes/Two')
@vite('resources/js/app.js', 'themes/Two')
```

### Common Mistakes
- `@vite(['resources/css/app.css'])` - Missing second param, looks in default `build/` directory
- `@vite(['resources/css/app.css'], 'themes/Two/build')` - Wrong path
- `@vite(['resources/css/app.css'], 'themes/Two/dist')` - Wrong path

## CSS Imports for Filament v5

From `resources/css/app.css`, the path to Laravel's vendor directory is 4 levels up:

```
resources/css/ -> resources/ -> Two/ -> Themes/ -> laravel/
```

So all Filament CSS imports use `../../../../vendor/filament/`:

```css
@import 'tailwindcss';

@import '../../../../vendor/filament/support/resources/css/index.css';
@import '../../../../vendor/filament/actions/resources/css/index.css';
@import '../../../../vendor/filament/forms/resources/css/index.css';
@import '../../../../vendor/filament/infolists/resources/css/index.css';
@import '../../../../vendor/filament/notifications/resources/css/index.css';
@import '../../../../vendor/filament/schemas/resources/css/index.css';
@import '../../../../vendor/filament/tables/resources/css/index.css';
@import '../../../../vendor/filament/widgets/resources/css/index.css';

@variant dark (&:where(.dark, .dark *));
```

## Build Commands

```bash
# Install dependencies
cd laravel/Themes/Two && npm install

# Production build
cd laravel/Themes/Two && npm run build

# Development with HMR
cd laravel/Themes/Two && npm run dev
```

## Verification

After build, verify:
1. `public_html/themes/Two/manifest.json` exists
2. Manifest contains entries for both `resources/css/app.css` and `resources/js/app.js`
3. Referenced asset files exist in `public_html/themes/Two/assets/`

## Troubleshooting

| Error | Cause | Fix |
|-------|-------|-----|
| Vite manifest not found | Wrong publicDirectory/buildDirectory | Fix vite.config.js |
| Unable to locate file in Vite manifest | Missing second param in @vite | Add `'themes/Two'` |
| CSS imports not resolved | Wrong relative path depth | Use `../../../../vendor/` |
| Empty CSS output | Missing `@import 'tailwindcss'` | Add as first line |
| DaisyUI warning `@property` | CSS spec warning, harmless | Can be ignored |
