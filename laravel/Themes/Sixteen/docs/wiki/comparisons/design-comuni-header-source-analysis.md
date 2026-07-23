# Design Comuni Header Source Analysis

## Source Files Ingested

- **cmp-header.hbs**: `https://raw.githubusercontent.com/italia/design-comuni-pagine-statiche/main/src/components/cmp-header/cmp-header.hbs`
- **_cmp-header.scss**: `https://raw.githubusercontent.com/italia/design-comuni-pagine-statiche/main/src/components/cmp-header/_cmp-header.scss`
- **segnalazione-02-dati.hbs**: `https://raw.githubusercontent.com/italia/design-comuni-pagine-statiche/main/src/pages/sito/segnalazione-02-dati.hbs`

## Design Comuni Header Structure (3-tier)

### 1. Slim Header (`it-header-slim-wrapper`)
- **Background**: `--dc-green-dark: #00402b` (dark green, NOT blue `#0066CC`)
- **Text color**: `#fff` (white) via `color: #fff` on `.navbar-brand` and `.nav-item.dropdown button`
- **Region link**: `<a class="d-lg-block navbar-brand">` with transparent bg showing slim background
- **Language switcher**: Bootstrap dropdown with `data-bs-toggle="dropdown"`, no Alpine
- **Login button**: `<a class="btn btn-primary btn-icon btn-full">` with icon + text

### 2. Center Header (`it-header-center-wrapper`)
- **Background**: `var(--dc-green)` (logo green `#007a52`)
- **Logo**: 82px on desktop, 48px on mobile
- **Social icons**: Right zone, hidden on mobile
- **Search**: `<button class="search-link rounded-icon">` with modal trigger

### 3. Navbar (`it-header-navbar-wrapper`)
- **Nav items**: `nav-link` with active state
- **Mobile**: Hamburger menu with `.navbar-collapsable`
- **Secondary nav**: Topic links

## Bootstrap → Tailwind Mapping (from .hbs source)

| Bootstrap Class | Tailwind Equivalent | Notes |
|---------------|-------------------|-------|
| `navbar-brand` | `navbar-brand` (custom CSS) | Keep class name, map styles to Tailwind in app.css |
| `d-lg-block` | `hidden lg:block` | |
| `nav-link dropdown-toggle` | `nav-link dropdown-toggle` | Keep for Bootstrap compatibility |
| `btn btn-primary btn-icon btn-full` | `btn-primary btn-icon btn-full` | Mapped in app.css |
| `it-search-wrapper` | Keep class | Search wrapper |
| `search-link rounded-icon` | `search-link rounded-icon` | Mapped in app.css |
| `dropdown-menu` | `dropdown-menu` | Custom CSS positioning |
| `link-list-wrapper` | `link-list-wrapper` | Custom CSS |

## Key Differences: Design Comuni vs Local Implementation

### ✅ Already Matching
- 3-tier header structure (slim/center/navbar)
- Dark green slim (`#00402b`)
- Logo green center (`#007a52`)
- Mobile hamburger menu
- Language switcher dropdown structure

### ✅ Fixed Issues (2026-05-04)
1. **"Nome della Regione" text color**: Now white (`text-white` class added)
2. **Search "Cerca" position**: Now above icon (`flex-col items-center`)
3. **Stepper active underline**: Now centered (`display: inline-flex; margin: 0 auto`)
4. **Stepper hover effects**: Now centered under text
5. **Language switcher dropdown**: Now properly positioned (`position: absolute; top: 100%`)

### ❌ Still To Fix
- (none currently known)

## Files Fixed

1. `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`
   - Added `text-white` to region link
   - Changed search wrapper to `flex-col items-center`
   - Fixed `__('pub_theme::header.center.search.label')` → `__('pub_theme::header.center.search.label.label')`

2. `laravel/Themes/Sixteen/resources/css/app.css`
   - Added `.fi-sc-wizard-header-step.fi-active { text-align: center; }`
   - Added `.fi-sc-wizard-header-step.fi-active .fi-sc-wizard-header-step-btn { display: inline-flex; margin: 0 auto; }`
   - Added `.fi-sc-wizard-header-step-btn:hover { text-align: center; text-underline-offset: 4px; }`
   - Added `position: absolute; top: 100%; left: 0;` to `.it-header-slim-wrapper .dropdown-menu`

## Build & Test
```bash
cd laravel/Themes/Sixteen
npm run build && npm run copy
```

Test at: `http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.data::data::wizard-step`

## Visual Parity Checklist
- [x] "Nome della Regione" = white text on dark green
- [x] Stepper "Servizi" active = centered underline
- [x] Search "Cerca" text = above icon
- [x] Language switcher = properly positioned dropdown
- [x] Hover effects = centered under text
- [x] Login button = light green background (`#007a52`)
