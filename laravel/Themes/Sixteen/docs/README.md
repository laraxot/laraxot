---
title: "Sixteen Module Documentation"
type: documentation
tags: [module, documentation]
created: 2026-06-05
updated: 2026-06-05
---

# Sixteen Theme Documentation

> 🇮🇹 [Biglietto da visita (IT)](../README.md) · 🇬🇧 [Business card (EN)](./readme-en.md)

## Gestionale / replica

FO attivo per Design Comuni. BL field-service = moduli Filament, non Folio Sixteen.  
Hub: [gestionale-docs-index.md](../../docs/gestionale-docs-index.md) · [tenant-modules-navigation-discipline.md](../../docs/tenant-modules-navigation-discipline.md) · [panels vs Sixteen](./gestionale-panels-vs-themes.md).

## Overview
The Sixteen theme is the primary frontend theme for the Fixcity application, built on top of Bootstrap Italia (Design Comuni) framework. It provides a modern, accessible, and responsive interface for all application features.

## Architecture

### Theme Structure

```
Themes/Sixteen/
├── resources/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── views/
│       ├── components/
│       │   ├── blocks/
│       │   │   └── tests/
│       │   │       └── segnalazione-crea.blade.php
│       │   ├── pub_theme/
│       │   │   └── components/
│       │   │       ├── wizard/
│       │   │       │   └── wizard.blade.php
│       │   │       └── sidebar.blade.php
│       │   └── filament/
│       │       └── widgets/
│       │           └── create-ticket-wizard.blade.php
│       └── pages/
│           └── tests/
│               └── [slug].blade.php
├── package.json
├── vite.config.js
└── tailwind.config.js
```

### Key Features

#### 1. Design Comuni Integration
- **Bootstrap Italia**: Complete Bootstrap Italia framework integration
- **Design System**: Consistent design language across all components
- **Accessibility**: WCAG 2.1 compliant design
- **Responsive**: Mobile-first responsive design

#### 2. Asset Management
- **Vite**: Modern build tool for asset compilation
- **Tailwind CSS**: Utility-first CSS framework
- **Asset Pipeline**: Efficient asset bundling and optimization
- **Version Control**: Automatic cache busting

#### 3. Component Architecture
- **Reusable Components**: Modular component system
- **Theme Components**: Custom Design Comuni components
- **Filament Integration**: Seamless Filament widget integration
- **Layout Templates**: Consistent page layouts

## Development Guidelines

### 1. Asset Development

#### CSS Development
```scss
// Use SCSS for component styles
.segnalazione-wizard-container {
    @extend .container;
    
    .wizard-step {
        @extend .card;
        @extend .mb-3;
    }
}
```

#### JavaScript Development
```javascript
// Use modules for JavaScript functionality
import { initializeMap } from './modules/map';
import { initializeWizard } from './modules/wizard';

document.addEventListener('DOMContentLoaded', () => {
    initializeMap();
    initializeWizard();
});
```

### 2. Component Development

#### Blade Components
```blade
{{-- Use Design Comuni components --}}
@component('pub_theme::components.wizard.sidebar', [
    'steps' => $steps,
    'currentStep' => $currentStep
])
@endcomponent

{{-- Use Bootstrap Italia components --}}
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <p class="card-text">{{ $content }}</p>
    </div>
</div>
```

#### View Structure
```blade
{{-- Main page template --}}
@extends('pub_theme::layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                @include('pub_theme::partials.header')
                
                <main class="py-4">
                    {{ $slot }}
                </main>
                
                @include('pub_theme::partials.footer')
            </div>
        </div>
    </div>
@endsection
```

### 3. Build Process

#### Development Build
```bash
# Install dependencies
npm install

# Development build with watch
npm run dev

# Production build
npm run build

# Copy assets to public directory
npm run copy
```

#### Asset Optimization
- **Minification**: Automatic CSS/JS minification
- **Tree Shaking**: Remove unused code
- **Image Optimization**: Automatic image compression
- **Code Splitting**: Lazy loading of chunks

### 4. Theme Configuration

#### Vite Configuration
```javascript
// vite.config.js
export default {
    build: {
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', 'alpine'],
                    bootstrap: ['bootstrap-italia']
                }
            }
        }
    }
}
```

#### Tailwind Configuration
```javascript
// tailwind.config.js
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './src/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#0077b6',
                secondary: '#00b4d8',
            }
        }
    }
}
```

### 5. Design System

#### Color Palette
- **Primary**: #0077b6 (Bootstrap Italia Primary)
- **Secondary**: #00b4d8 (Bootstrap Italia Info)
- **Success**: #28a745 (Bootstrap Italia Success)
- **Danger**: #dc3545 (Bootstrap Italia Danger)

#### Typography
- **Headings**: Bootstrap Italia typography scale
- **Body**: Bootstrap Italia default font
- **Code**: Bootstrap Italia code styling

#### Spacing
- **Padding**: Bootstrap Italia spacing scale
- **Margin**: Bootstrap Italia spacing scale
- **Grid**: Bootstrap Italia grid system

### 6. Integration Patterns

#### With Filament
```blade
{{-- Filament widget integration --}}
<x-filament-widgets::widget>
    {{ $this->form }}
</x-filament-widgets::widget>
```

#### With Laravel
```blade
{{-- Route-based page templates --}}
@php
    $page = request()->route()->parameter('slug');
@endphp

@extends("pages.tests.{$page}")
```

#### With Bootstrap Italia
```blade
{{-- Bootstrap Italia components --}}
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">{{ $title }}</h5>
    </div>
    <div class="card-body">
        {{ $content }}
    </div>
</div>
```

### 7. Performance Optimization

#### Asset Loading
- **Lazy Loading**: Non-critical assets loaded asynchronously
- **Caching**: Proper cache headers and ETags
- **CDN**: Static assets served from CDN
- **Compression**: Gzip/Brotli compression

#### JavaScript Optimization
- **Debouncing**: Event debouncing for scroll/resize events
- **Throttling**: Function throttling for performance
- **Virtual Scrolling**: Efficient large list rendering
- **Intersection Observer**: Lazy loading of off-screen content

### 8. Testing

#### Browser Testing
- **Cross-browser**: Chrome, Firefox, Safari, Edge
- **Responsive**: Mobile, tablet, desktop views
- **Accessibility**: Keyboard navigation, screen readers
- **Performance**: Loading speed, rendering performance

#### Automated Testing
```javascript
// Example test with Playwright
test('wizard step navigation', async ({ page }) => {
    await page.goto('/it/tests/segnalazione-crea');
    
    // Test step 1
    await page.click('text=Next');
    
    // Test step 2
    await page.fill('[name="data.name"]', 'Test Name');
    await page.click('text=Submit');
    
    // Test success
    await page.waitForSelector('text=Success');
});
```

### 9. Deployment

#### Build Process
```bash
# Production build
npm run build

# Copy assets
npm run copy

# Clear cache
php artisan view:clear
php artisan route:clear
```

#### Environment Configuration
```env
# Asset optimization
NODE_ENV=production

# Cache configuration
APP_ENV=production
APP_DEBUG=false
```

### 10. Troubleshooting

#### Common Issues
1. **Asset Not Loading**: Check Vite manifest and build process
2. **CSS Conflicts**: Use proper specificity and BEM naming
3. **JavaScript Errors**: Check console for errors and debug
4. **Rendering Issues**: Verify component lifecycle and state

#### Debugging Tools
- **Browser DevTools**: Inspect elements and network requests
- **Laravel DebugBar**: Debug Laravel application
- **Vite Dev Server**: Development server with hot reload
- **Webpack Bundle Analyzer**: Analyze bundle composition

### 11. Future Enhancements

#### Planned Features
1. **Dark Mode**: Bootstrap Italia dark theme support
2. **Internationalization**: Multi-language support
3. **PWA Support**: Progressive Web App capabilities
4. **Advanced Animations**: Smooth transitions and micro-interactions

#### Technical Improvements
1. **Component Library**: Comprehensive component documentation
2. **Storybook**: Component development and testing
3. **Automated Testing**: End-to-end testing integration
4. **Performance Monitoring**: Real user monitoring (RUM)

### 12. Architettura Laraxot (cross-cutting)

I temi **non** contano modelli/migrazioni, ma gli agenti che toccano Fixcity/User devono rispettare:

- [Parità modulo N=N](../../../../docs/wiki/bmad/architecture-module-model-artifact-parity.md) — audit: `bashscripts/tools/audit-module-artifact-parity.sh Fixcity`
- [Dati sacri](../../../../docs/wiki/rules/data-sacred-no-destructive-db.md) — solo `php artisan migrate`
- Wiki tema: [docs/wiki/](./wiki/) · ingest: `bashscripts/docs/llm-wiki-qmd.sh update`

---

*Last Updated: June 2026*  
*Version: 1.0.0*