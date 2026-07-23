# Header Navigation - Specification & Implementation

## Reference: lightseagreen-dogfish-560272.hostingersite.com

### Visual Analysis (from screenshots)

**Home page (transparent header over hero):**
- Header is `position: fixed`, fully transparent over the hero image
- On scroll: transitions to solid dark blue (`#0f2b46`) with `backdrop-blur`
- Brand: "Marco Sottana" (bold, white) + "Consulenza Sicurezza" (smaller, lighter)
- Nav items: Home, Chi Siamo, Servizi, Blog, FAQ, Contatti (white text)
- Active item: underlined with white line
- CTA button: "Richiedi Consulenza" with phone icon, white border, transparent bg
- No language switcher on reference (we add it as improvement)

**Internal pages (e.g. /servizi):**
- Header has solid dark blue background from start (hero is shorter with blue gradient)
- Same layout but background is always visible

### Our Implementation (header/v1.blade.php)

**Improvements over reference:**
1. Language switcher (multilingual support via Lang module)
2. Auth user avatar dropdown (logged-in users see profile/dashboard/logout)
3. Mobile responsive menu with hamburger
4. SEO: proper semantic HTML with `<nav>`, `aria-*` attributes
5. Scroll-reactive: transparent on top → solid on scroll

### Content Source
- JSON: `config/local/techplanner/database/content/sections/header.json`
- Bilingual: it/en blocks with nav items, brand, CTA
- Rendered via: `<x-section slug="header"/>` → `Section.php` → `header.blade.php` → `header/v1.blade.php`

### Block Data Structure
```json
{
  "type": "navigation",
  "slug": "nav1",
  "data": {
    "brand": "Marco Sottana",
    "brand_subtitle": "Consulenza Sicurezza",
    "cta_label": "Richiedi Consulenza",
    "cta_url": "/it/contatti",
    "items": [
      {"label": "Home", "url": "/it", "type": "link", "active": true},
      {"label": "Chi Siamo", "url": "/it/chi-siamo", "type": "link"}
    ]
  }
}
```

### Language Switching
- Uses `LaravelLocalization::getSupportedLocales()` and `getLocalizedURL()`
- Flag SVGs at `modules/ui/svg/flags/{code}.svg`
- Config: `config/lang.available_locales` = ['it', 'en', 'de']

### Auth Integration
- `@auth` / `@guest` directives
- Avatar from `auth()->user()->avatar_url` or ui-avatars.com fallback
- Dropdown: Dashboard, Profile, Logout
- Mobile: inline user info + links

### Colors (Brand)
- Primary blue: `#1E5A96`
- Dark nav bg: `#0f2b46`
- Green: `#2D8659`
- Orange/CTA: `#E67E22`

### Known Issues (Fixed)
- ~~Debug output `|---LINE:31---|` showing in production~~ → Removed debug code
- ~~White text on white background when scrolled past hero~~ → Always dark bg with backdrop-blur
- ~~Mobile menu x-data scope issue~~ → Moved to parent element
- ~~CTA orange bg instead of white border~~ → Changed to match reference style
