# Filament v5 Standalone Frontend - Theme Two

## Overview
Theme Two uses Filament v5 components standalone (outside the admin panel) for the public-facing frontend. This enables Forms Builder for content blocks, notifications, and other interactive UI elements.

## Required CSS Imports

All Filament standalone CSS must be imported in `resources/css/app.css`:

```css
@import 'tailwindcss';

/* Required by all components */
@import '../../../../vendor/filament/support/resources/css/index.css';

/* Required by actions and tables */
@import '../../../../vendor/filament/actions/resources/css/index.css';

/* Required by actions, forms and tables */
@import '../../../../vendor/filament/forms/resources/css/index.css';

/* Required by actions and infolists */
@import '../../../../vendor/filament/infolists/resources/css/index.css';

/* Required by notifications */
@import '../../../../vendor/filament/notifications/resources/css/index.css';

/* Required by actions, infolists, forms, schemas and tables */
@import '../../../../vendor/filament/schemas/resources/css/index.css';

/* Required by tables */
@import '../../../../vendor/filament/tables/resources/css/index.css';

/* Required by widgets */
@import '../../../../vendor/filament/widgets/resources/css/index.css';

@variant dark (&:where(.dark, .dark *));
```

## Layout Requirements

The base layout at `resources/views/layouts/app.blade.php` must include:

```blade
<head>
    ...
    @filamentStyles
    @vite('resources/css/app.css', 'themes/Two')
</head>
<body>
    {{ $slot }}
    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js', 'themes/Two')
</body>
```

Key directives:
- `@filamentStyles` - Loads Filament's CSS
- `@filamentScripts` - Loads Filament's JS (Alpine.js, Livewire)
- `@livewire('notifications')` - Required for flash notifications

## Forms Builder for Content Blocks

### How It Works
Content pages use Filament's Forms Builder to define content blocks. Each page's content is stored as JSON in:

```
laravel/config/local/<tenant>/database/content/pages/<page>.json
```

### Block Components
Block Blade components live in:
```
resources/views/components/blocks/<type>/<name>.blade.php
```

### Content JSON Format
```json
[
    {
        "type": "hero",
        "data": {
            "title": "Welcome",
            "description": "Your description here",
            "cta_text": "Get Started",
            "cta_url": "/register"
        }
    },
    {
        "type": "features",
        "data": {
            "title": "Features",
            "items": [
                {"icon": "check", "title": "Feature 1", "description": "..."}
            ]
        }
    }
]
```

### Section Components
Section components (header, footer) are managed similarly:
```
resources/views/components/sections/<name>.blade.php
```
With content in:
```
laravel/config/local/<tenant>/database/content/sections/<name>.json
```

## Dark Mode
DaisyUI themes handle dark mode via the `data-theme` attribute. The CSS includes:
```css
@variant dark (&:where(.dark, .dark *));
```

## Available Libraries
- **Tailwind CSS v4**: Utility-first CSS framework
- **DaisyUI 5**: Tailwind component library (installed as dependency)
- **Alpine.js**: Lightweight JS (included via Livewire/Filament)
- **Livewire 3**: Server-driven interactivity
