# 🎨 Bootstrap Italia Classes + Tailwind @apply

**Version**: 2.0  
**Created**: 2026-03-30  
**Status**: ✅ **Active Architecture**  
**Owner**: Multi-Agent Team

---

## 🚨 Golden Rule

> **HTML: Bootstrap Italia class names**  
> **CSS: Tailwind @apply directives**

**NON usiamo Bootstrap CSS** - solo Tailwind CSS con @apply per replicare lo styling Bootstrap Italia.

---

## 📐 Architecture

### Flow

```
HTML: <div class="it-header-wrapper">
            ↓
CSS: .it-header-wrapper { @apply bg-primary text-white relative; }
            ↓
Output: Styled component (no Bootstrap CSS)
```

### Benefits

| Benefit | Description |
|---------|-------------|
| **HTML Parity** | Same class names as AGID upstream |
| **Tailwind Performance** | Smaller CSS bundle, purged |
| **Maintainability** | Tailwind utilities, easy updates |
| **Customization** | Override Bootstrap Italia easily |
| **No Bootstrap CSS** | No conflicts, lighter |

---

## 🔧 Implementation

### 1. HTML Structure (Bootstrap Italia Names)

```blade
{{-- Header --}}
<div class="it-header-wrapper">
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="it-header-slim-wrapper-content">
        <a href="#" class="it-header-slim-link">Nome della Regione</a>
        <div class="it-header-slim-right-zone">
          {{-- Language, Login, Social --}}
        </div>
      </div>
    </div>
  </div>
  
  <div class="it-header-main-wrapper">
    <div class="container">
      <div class="it-brand-wrapper">
        <h2>Nome del Comune</h2>
      </div>
    </div>
  </div>
  
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <button class="custom-navbar-toggler">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Amministrazione</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
```

### 2. CSS (Tailwind @apply)

**File**: `laravel/Themes/Sixteen/resources/css/components/bootstrap-italia.css`

```css
/* Import Titillium Web font */
@import url('https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap');

/* CSS Custom Properties */
:root {
  --bs-primary: #007a52;
  --bs-primary-dark: #00614a;
  --bs-secondary: #5d7083;
  --bs-success: #008055;
  --bs-blue: #006cc6;
  --bs-dark: #17334f;
  --bs-light: #f8f9fa;
}

/* Header Wrapper */
.it-header-wrapper {
  @apply bg-[var(--bs-primary)] text-white relative z-50;
}

/* Header Slim */
.it-header-slim-wrapper {
  @apply bg-[var(--bs-primary-dark)] py-2 text-sm;
}

.it-header-slim-wrapper-content {
  @apply flex justify-between items-center;
}

.it-header-slim-link {
  @apply text-white no-underline text-sm hover:opacity-80 transition-opacity;
}

.it-header-slim-right-zone {
  @apply flex items-center gap-4;
}

/* Header Main */
.it-header-main-wrapper {
  @apply bg-[var(--bs-primary)] py-4;
}

.it-brand-wrapper {
  @apply flex items-center gap-3;
}

.it-brand-wrapper a {
  @apply flex items-center gap-3 no-underline text-white;
}

.it-brand-text {
  @apply flex flex-col;
}

/* Navbar */
.navbar {
  @apply p-0 bg-[var(--bs-primary)] !important;
}

.navbar-expand-lg {
  @apply bg-[var(--bs-primary)] !important;
}

.navbar-nav {
  @apply flex gap-0 list-none m-0 p-0;
}

.navbar-nav .nav-item {
  @apply relative;
}

.navbar-nav .nav-link {
  @apply text-white font-semibold text-base px-6 py-3 no-underline block transition-colors duration-200;
}

.navbar-nav .nav-link:hover {
  @apply text-[var(--bs-primary)];
}

/* Custom Toggler */
.custom-navbar-toggler {
  @apply border-0 bg-transparent p-2 text-white block lg:hidden;
}

/* Container */
.container {
  @apply w-full px-3 mx-auto max-w-[1200px];
}

/* Row/Col Grid */
.row {
  @apply flex flex-wrap -mx-3;
}

.col-12 {
  @apply flex-none w-full max-w-full px-3;
}

.col-md-6 {
  @apply flex-none w-full max-w-full px-3;
}

@media (min-width: 768px) {
  .col-md-6 {
    @apply flex-none w-1/2 max-w-[50%];
  }
}

.col-lg-4 {
  @apply flex-none w-full max-w-full px-3;
}

@media (min-width: 992px) {
  .col-lg-4 {
    @apply flex-none w-1/3 max-w-[33.333333%];
  }
}

/* Cards */
.card-wrapper {
  @apply mb-4;
}

.card-space {
  @apply transition-shadow duration-200 hover:shadow-md;
}

.card {
  @apply bg-white border border-gray-200 rounded-lg shadow-sm;
}

.card-bg {
  @apply bg-white;
}

.card-body {
  @apply p-6;
}

.card-title {
  @apply text-lg font-semibold text-gray-900 mb-2;
}

.card-text {
  @apply text-gray-600 text-base leading-relaxed;
}

.card-date {
  @apply text-sm text-gray-500 block mb-2;
}

/* Read More Link */
.read-more {
  @apply text-primary fw-semibold text-decoration-none inline-flex items-center gap-2 transition-colors hover:text-primary-dark;
}

/* Typography */
.title-xxxlarge {
  @apply text-5xl font-bold text-gray-900 leading-tight mb-0;
}

.title-xxlarge {
  @apply text-4xl font-bold text-gray-900 mb-0;
}

.title-xlarge {
  @apply text-3xl font-bold text-gray-900 mb-0;
}

/* Sections */
.hero-section {
  @apply py-12 bg-white;
}

.featured-news {
  @apply py-8 bg-white;
}

.featured-services {
  @apply py-8 bg-white;
}

.topics-section {
  @apply py-8 bg-white;
}

.events-section {
  @apply py-8 bg-white;
}

/* Footer */
.it-footer {
  @apply bg-primary-900 text-white;
}

/* Utility Classes */
.text-primary {
  @apply text-[var(--bs-primary)] !important;
}

.text-muted {
  @apply text-gray-500;
}

.fw-semibold {
  @apply font-semibold;
}

.d-inline-flex {
  @apply inline-flex;
}

.align-items-center {
  @apply items-center;
}

.gap-2 {
  @apply gap-2;
}

.gap-4 {
  @apply gap-4;
}

.mb-2 {
  @apply mb-2;
}

.mb-4 {
  @apply mb-4;
}

.mb-6 {
  @apply mb-6;
}

.mt-2 {
  @apply mt-2;
}

.mt-3 {
  @apply mt-3;
}

.ms-2 {
  @apply ms-2;
}

.icon {
  @apply w-4 h-4 fill-current;
}

.icon-sm {
  @apply w-3.5 h-3.5;
}

.icon-primary {
  @apply text-[var(--bs-primary)];
}

/* Icon sizing for Filament icons */
[style*="width: 24px"] {
  @apply w-6;
}

[style*="height: 24px"] {
  @apply h-6;
}
```

---

## 📁 File Structure

```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   ├── app.css                    # Main entry
│   │   └── components/
│   │       ├── bootstrap-italia.css   # BI classes with @apply
│   │       └── tailwind.css           # Tailwind base
│   └── views/
│       └── components/
│           └── blocks/
│               ├── header/
│               ├── hero/
│               └── ...
└── public/
    └── assets/
        └── app.css                    # Compiled CSS
```

---

## 🔧 Build Process

### Development

```bash
cd laravel/Themes/Sixteen
npm run dev

# Watches:
# - resources/css/**/*.css
# - resources/views/**/*.blade.php
# - Auto-compiles to public/assets/
```

### Production

```bash
cd laravel/Themes/Sixteen
npm run build

# Output:
# - public/assets/app.css (compiled, purged)
# - public/assets/app.js

# Copy to public_html
npm run copy
```

---

## ✅ Usage Example

### Blade Template

```blade
{{-- File: blocks/header/full.blade.php --}}
<div class="it-header-wrapper">
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="it-header-slim-wrapper-content">
        <a href="#" class="it-header-slim-link">
          {{ $region ?? 'Nome della Regione' }}
        </a>
        <div class="it-header-slim-right-zone">
          {{-- Language, Login, Social --}}
        </div>
      </div>
    </div>
  </div>
  
  <div class="it-header-main-wrapper">
    <div class="container">
      <div class="it-brand-wrapper">
        <h2 class="text-white text-2xl">{{ $municipality ?? 'Nome del Comune' }}</h2>
      </div>
    </div>
  </div>
  
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <button class="custom-navbar-toggler">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse">
        <ul class="navbar-nav">
          @foreach($menu_items as $item)
          <li class="nav-item">
            <a class="nav-link" href="{{ $item['url'] }}">{{ $item['title'] }}</a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </nav>
</div>
```

### JSON Configuration

```json
{
  "type": "header",
  "data": {
    "view": "pub_theme::components.blocks.header.full",
    "region": "Nome della Regione",
    "municipality": "Nome del Comune",
    "menu_items": [
      {"title": "Amministrazione", "url": "/it/tests/amministrazione"},
      {"title": "Novità", "url": "/it/tests/novita"},
      {"title": "Servizi", "url": "/it/tests/servizi"},
      {"title": "Vivere il Comune", "url": "/it/tests/eventi"}
    ]
  }
}
```

---

## 📊 Comparison

### Before (Bootstrap CSS)

```html
<!-- Load Bootstrap CSS -->
<link rel="stylesheet" href="bootstrap.css">
<link rel="stylesheet" href="bootstrap-italia.css">

<!-- HTML -->
<div class="it-header-wrapper">...</div>

<!-- Issues -->
- Large CSS bundle (200KB+)
- Unused CSS not purged
- Conflicts with custom CSS
- Hard to customize
```

### After (Tailwind @apply)

```html
<!-- Load Tailwind CSS only -->
<link rel="stylesheet" href="app.css">

<!-- HTML (same class names) -->
<div class="it-header-wrapper">...</div>

<!-- Benefits -->
- Small CSS bundle (20KB purged)
- Only used CSS included
- No conflicts
- Easy customization
```

---

## 🤖 OpenViking Context

```bash
openviking add-memory "Bootstrap Italia + Tailwind @apply architecture: HTML uses BI class names, CSS uses Tailwind @apply. No Bootstrap CSS loaded. Pure Tailwind with BI semantics."
```

---

## 📚 Related Documentation

- [Superpowers Integration](./SUPERPOWERS_INTEGRATION_GUIDE.md)
- [SVG Icon Convention](./SVG_ICON_CONVENTION.md)
- [Theme Architecture](./THEME_ARCHITECTURE_OUTFIT.md)
- [Homepage HTML Parity](../../../_bmad/threads/homepage-html-parity.md)

---

**Last Updated**: 2026-03-30  
**Architecture**: Bootstrap Italia classes + Tailwind @apply  
**Status**: ✅ **ACTIVE**  
**Owner**: Multi-Agent Team
