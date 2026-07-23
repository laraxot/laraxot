# Alpine.js Components Documentation

**Location**: `laravel/Themes/Sixteen/resources/js/components/`  
**Framework**: Alpine.js 3.x  
**Purpose**: Interactive functionality for Design Comuni homepage

## Component Overview

All Alpine components follow a consistent pattern for easy maintenance and testing.

### 1. Dropdown Toggle (`dropdown.js`)

**Purpose**: Manage dropdown menu visibility and interactions

**Features**:
- Toggle visibility on button click
- Close on outside click (via `@click.away`)
- Select item and auto-close
- Keyboard support (Escape to close, ArrowDown to open)

**Usage in Blade**:
```html
<div x-data="dropdownToggle()" @click.away="close()">
  <button @click="toggle()" class="dropdown-toggle">
    Language ▼
  </button>
  <div x-show="isOpen" x-transition class="dropdown-menu">
    <a href="#" @click.prevent="selectItem(() => selectLanguage('it'))">
      Italiano
    </a>
    <a href="#" @click.prevent="selectItem(() => selectLanguage('en'))">
      English
    </a>
  </div>
</div>
```

**Methods**:
- `toggle()` - Toggle isOpen state
- `open()` - Set isOpen to true
- `close()` - Set isOpen to false
- `selectItem(callback)` - Execute callback and close dropdown
- `handleKeydown(event)` - Handle keyboard events

**State**:
```javascript
{
  isOpen: false              // Current visibility state
}
```

---

### 2. Modal Component (`modal.js`)

**Purpose**: Manage modal dialog visibility and focus

**Features**:
- Open/close modal dialogs
- Close on Escape key press
- Background overflow handling (prevent scrolling)
- Focus trap support (basic)
- Keyboard handling

**Usage in Blade**:
```html
<div x-data="modal()" @keydown.escape="close()">
  <!-- Trigger button -->
  <button @click="open()" class="search-button">
    Search
  </button>

  <!-- Modal -->
  <div x-show="isOpen" x-transition class="modal-backdrop" @click="close()">
    <div class="modal-dialog" @click.stop>
      <button @click="close()" class="btn-close">×</button>
      <input type="text" placeholder="Search..." class="form-control">
    </div>
  </div>
</div>
```

**Methods**:
- `open()` - Open modal (shows backdrop, handles overflow)
- `close()` - Close modal (restores scroll)
- `toggle()` - Toggle modal visibility
- `trapFocus()` - Focus first focusable element
- `handleEscape(event)` - Close on Escape key

**State**:
```javascript
{
  isOpen: false              // Current visibility state
}
```

---

### 3. Mobile Menu Component (`mobile-menu.js`)

**Purpose**: Manage responsive mobile navigation

**Features**:
- Toggle menu on mobile (< 768px)
- Auto-close on desktop resize
- Close on item click (mobile only)
- Responsive breakpoint detection
- Keyboard support (Escape to close)

**Usage in Blade**:
```html
<header class="it-header-wrapper" x-data="mobileMenu()">
  <button @click="toggle()" 
          x-show="isMobile()" 
          class="navbar-toggle">
    ☰ Menu
  </button>
  
  <nav x-show="isOpen || !isMobile()" 
       x-transition
       class="navbar-menu"
       @click="closeOnItemClick()">
    <a href="#" class="nav-link">Item 1</a>
    <a href="#" class="nav-link">Item 2</a>
  </nav>
</header>
```

**Methods**:
- `toggle()` - Toggle menu visibility
- `open()` - Open menu
- `close()` - Close menu
- `checkBreakpoint()` - Detect mobile breakpoint (768px)
- `isMobile()` - Return true if < 768px
- `closeOnItemClick()` - Close menu on mobile when item clicked
- `handleEscape(event)` - Close on Escape key

**State**:
```javascript
{
  isOpen: false,             // Menu visibility state
  isMobileBreakpoint: false  // Current breakpoint detection
}
```

---

### 4. Governance Carousel Component (`carousel.js`)

**Purpose**: Manage carousel navigation with Splide integration

**Features**:
- Integrates with Splide carousel library
- Previous/Next navigation buttons
- Keyboard support (Arrow keys)
- Responsive slides per page
- Disabled state for boundary buttons

**Usage in Blade**:
```html
<div x-data="governanceCarousel()" @keydown="handleKeydown($event)">
  <!-- Splide carousel -->
  <div id="governance-carousel" class="splide">
    <div class="splide__track">
      <ul class="splide__list">
        <li class="splide__slide"><!-- Slide 1 --></li>
        <li class="splide__slide"><!-- Slide 2 --></li>
        <li class="splide__slide"><!-- Slide 3 --></li>
      </ul>
    </div>
  </div>

  <!-- Navigation buttons -->
  <button @click="prev()" :disabled="isAtStart()" class="carousel-prev">
    ❮
  </button>
  <button @click="next()" :disabled="isAtEnd()" class="carousel-next">
    ❯
  </button>
</div>
```

**Methods**:
- `prev()` - Go to previous slide
- `next()` - Go to next slide
- `goToSlide(index)` - Jump to specific slide
- `isAtStart()` - Check if at first slide
- `isAtEnd()` - Check if at last slide
- `handleKeydown(event)` - Handle left/right arrow keys

**State**:
```javascript
{
  splide: null,              // Splide instance reference
  currentIndex: 0            // Current slide index
}
```

**Responsive Configuration**:
- Desktop (1024px+): 3 slides per page
- Tablet (768px-1023px): 2 slides per page
- Mobile (< 768px): 1 slide per page

---

## Integration with Blade Templates

### Import Components in app.js

Components are automatically registered via Alpine.data():

```javascript
Alpine.data('dropdownToggle', dropdownToggle);
Alpine.data('modal', modal);
Alpine.data('mobileMenu', mobileMenu);
Alpine.data('governanceCarousel', governanceCarousel);
```

### Using in Templates

Use `x-data` directive to instantiate:

```blade
<div x-data="componentName()">
  <!-- Component markup -->
</div>
```

---

## Common Patterns

### Button States

Control button disabled state based on component state:

```html
<button @click="next()" 
        :disabled="isAtEnd()" 
        :class="{'opacity-50': isAtEnd()}">
  Next →
</button>
```

### Transitions

Use Alpine transitions for smooth animations:

```html
<div x-show="isOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
  Content
</div>
```

### Click-Away Behavior

Use Alpine's `@click.away` modifier to close on outside click:

```html
<div x-data="dropdownToggle()" @click.away="close()">
  <button @click="toggle()">Toggle</button>
  <div x-show="isOpen">Content</div>
</div>
```

### Keyboard Handling

Listen for specific keys and respond:

```html
<div @keydown.escape="close()">
  <div @keydown.arrow-left="prev()" @keydown.arrow-right="next()">
    <!-- Content -->
  </div>
</div>
```

---

## Testing Checklist

**Dropdown Component**:
- [ ] Toggle opens/closes dropdown
- [ ] Click outside closes dropdown
- [ ] Item selection triggers callback and closes
- [ ] Escape key closes dropdown
- [ ] Arrow down opens dropdown

**Modal Component**:
- [ ] Click button opens modal
- [ ] Click backdrop closes modal
- [ ] Escape key closes modal
- [ ] Body overflow is hidden when open
- [ ] Body overflow is restored when closed

**Mobile Menu Component**:
- [ ] Menu hidden on desktop (>= 768px)
- [ ] Menu toggle visible on mobile (< 768px)
- [ ] Menu closes when window resizes to desktop
- [ ] Menu items close menu on click (mobile only)
- [ ] Escape key closes menu

**Carousel Component**:
- [ ] Previous button disabled at start
- [ ] Next button disabled at end
- [ ] Clicking prev/next navigates slides
- [ ] Arrow keys navigate slides
- [ ] Responsive slides per page works

---

## Accessibility Requirements

All components should meet WCAG 2.1 AA standards:

### Keyboard Navigation
- ✅ Tab through interactive elements
- ✅ Enter/Space to activate buttons
- ✅ Escape to close menus/modals
- ✅ Arrow keys for carousel navigation

### ARIA Attributes

Recommended additions for production:

```html
<!-- Dropdown -->
<button aria-haspopup="true" :aria-expanded="isOpen">Menu</button>
<div role="menu" x-show="isOpen">
  <a role="menuitem" href="#">Item</a>
</div>

<!-- Modal -->
<div role="dialog" aria-modal="true" x-show="isOpen">
  <button aria-label="Close dialog">×</button>
</div>

<!-- Menu -->
<nav aria-label="Main navigation" x-show="isOpen || !isMobile()">
  <ul>
    <li><a href="#">Item</a></li>
  </ul>
</nav>
```

### Focus Management

- Focus first interactive element when modal opens
- Return focus to trigger element when modal closes
- Use `x-trap` directive for focus trapping in modals

---

## Build & Deployment

Components are bundled with `npm run build`:

```bash
cd laravel/Themes/Sixteen
npm run build    # Compile JS with Vite
npm run copy     # Copy to public_html
```

**Output Files**:
- `public/assets/app-*.js` - Compiled JavaScript (includes Alpine + components)
- `public/assets/app-*.css` - Compiled CSS (includes Tailwind)

---

## Debugging

To debug component state, add to Blade template:

```html
<div x-data="componentName()" @change="console.log('State:', $data)">
  <!-- Component -->
  <pre x-text="JSON.stringify($data, null, 2)"></pre>
</div>
```

Or use browser DevTools console:

```javascript
// Alpine 3.x
document.querySelectorAll('[x-data]').forEach(el => {
  console.log('Component:', el.__x);
});
```

---

## File Structure

```
resources/js/
├── app.js                    ← Main entry point (imports components)
├── components/
│   ├── dropdown.js          ← Dropdown toggle component
│   ├── modal.js             ← Modal dialog component
│   ├── mobile-menu.js       ← Responsive menu component
│   ├── carousel.js          ← Carousel/slider component
│   └── bootstrap-italia.js  ← Legacy Bootstrap functionality
```

---

## Header Mobile Navigation — Vanilla JS Pattern (No Alpine)

**⚠️ NEVER USE INLINE JAVASCRIPT IN BLADE TEMPLATES**

### Architecture Change (2026-05-26)

Il componente mobile navigation è stato **rifattorizzato da Alpine.js a Vanilla JS**:

- **Prima**: Alpine.js `x-data="headerMobileNav"` con dual-file bootstrap sincrono
- **Ora**: Vanilla JS con `data-*` attributes, caricato come ES module Vite (defer OK)

### Perché Vanilla JS?

1. **Più leggero**: No overhead Alpine per componente semplice
2. **Nessun problema di timing**: Non dipende da `alpine:init` o registrazione sincrona
3. **ES module nativo**: Caricato con `@vite` senza workarounds

### Files (Clean Architecture)

| File | Purpose | Loaded By |
|------|---------|-----------|
| `resources/js/theme/header-mobile-nav.js` | Vanilla JS controller | `import` in app.js |
| `resources/js/app.js` | Main entry point | `@vite` in Blade |

### Architecture

```
Blade: @vite(['resources/js/app.js'])
    ↓
app.js: import { initHeaderMobileNav } from './theme/header-mobile-nav.js'
    ↓
app.js: document.addEventListener('DOMContentLoaded', initHeaderMobileNav)
    ↓
header-mobile-nav.js: Auto-initializes on DOM ready
```

**Perché così?**
- **Single entry point**: Solo `app.js` è entry point Vite
- **ES modules**: `header-mobile-nav.js` è importato, non caricato separatamente
- **Defer OK**: Vanilla JS con `DOMContentLoaded` funziona con defer
- **No legacy**: Niente file bootstrap, niente plugin custom, niente duplicazione

### ATTENZIONE: LEGACY RIMOSSA

Il vecchio pattern `header-mobile-nav.js` (vanilla JS con `data-sixteen-mobile-nav-*`) e l'entry Vite separato `header-mobile-nav-boot.js` sono stati **rimossi** il 2026-05-26. Zero template usavano i loro selettori.

### Pattern Attuale: Alpine `mobileMenu()` con Inline Bootstrap

Il mobile nav usa l'Alpine component `mobileMenu()` registrato su 5 template con `x-data="mobileMenu()"`.

**Problema**: Vite emette `type="module"` defer → `app.js` arriva dopo che Alpine (in @livewireScripts) ha già scandito il DOM.

**Soluzione**: inline `<script>` su `alpine:init` nel `<head>` PRIMA di `@livewireScripts`:

```html
{{-- components/layouts/main.blade.php --}}
<script>
    document.addEventListener('alpine:init', function () {
        Alpine.data('mobileMenu', () => ({
            isOpen: false,
            isMobileBreakpoint: false,
            init() {
                this.checkBreakpoint();
                window.addEventListener('resize', () => this.checkBreakpoint());
            },
            toggle() { this.isOpen = !this.isOpen; },
            open() { this.isOpen = true; },
            close() { this.isOpen = false; },
            checkBreakpoint() {
                this.isMobileBreakpoint = window.innerWidth < 768;
                if (!this.isMobileBreakpoint && this.isOpen) this.close();
            },
            isMobile() { return this.isMobileBreakpoint; },
            closeOnItemClick() { if (this.isMobileBreakpoint) this.close(); },
        }));
    });
</script>
```

La factory `mobileMenu` esiste anche come `export` in `resources/js/components/mobile-menu.js`, registrata in `app.js` come fallback per navigazioni Livewire successive.

### Loading in Blade (Single Entry Point)

```blade
@filamentScripts
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

**NO multiple entry points** — Tutto passa da `app.js`.

### Key Rules

1. **Use `@vite`**: Standard Vite loading (defer ES module)
2. **NEVER INLINE**: Tutto il JS in file esterni
3. **Data attributes**: Usa `data-*` per markup HTML, non classi inline
4. **Idempotent**: Controlla `dataset.bound` per evitare double-binding

---

## Related Documentation

- **Alpine.js Docs**: https://alpinejs.dev
- **Splide Carousel**: https://splidejs.com
- **Tailwind CSS**: https://tailwindcss.com
- **WCAG 2.1 Guidelines**: https://www.w3.org/WAI/WCAG21/quickref/
- **CSP Best Practices**: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP

---

**Last Updated**: 2026-05-26  
**Status**: ✅ Header Mobile Nav Pattern Updated — No More Inline JS  
**Test Coverage**: Components ready for integration testing
