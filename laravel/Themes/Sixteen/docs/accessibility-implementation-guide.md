# WCAG 2.1 Accessibility Implementation Guide - Sixteen Theme

## Executive Summary

This document provides specific accessibility implementation guidelines for the **Sixteen Theme**, the citizen-facing frontend of the FixCity platform. All requirements are based on WCAG 2.1 Level AA compliance, mandatory for Italian/EU public sector websites by **June 28, 2025**.

---

## Theme-Specific Accessibility Requirements

### 1. Color Palette & Contrast Compliance

#### Current Color Scheme Analysis

**Primary Colors** (from `tailwind.config.js`):
```javascript
// Verify these meet WCAG AA contrast ratios
colors: {
    primary: {
        50: '#e6f3ff',   // Light backgrounds
        500: '#0066CC',  // Primary actions - CHECK CONTRAST
        700: '#004C99',  // Dark text
        900: '#002852',  // Darkest
    },
    secondary: {
        // Define with accessibility in mind
    },
    success: '#28a745',  // ✅ Status
    warning: '#ffc107',  // ⚠️ CHECK - May fail on white
    danger: '#dc3545',   // ❌ Status
    info: '#17a2b8',     // ℹ️ Status
}
```

**Required Contrast Checks**:
```css
/* All text must meet 4.5:1 ratio (AA) or 7:1 (AAA) */

/* ❌ FAIL EXAMPLE - Warning yellow on white */
.warning-text {
    color: #ffc107; /* Only 1.9:1 contrast ratio */
    background: #ffffff;
}

/* ✅ PASS EXAMPLE - Fixed warning */
.warning-text {
    color: #b38600; /* Darkened to 4.5:1 ratio */
    background: #ffffff;
}

/* Status badges - ensure 3:1 for UI components */
.status-badge {
    border: 2px solid; /* Border must be 3:1 contrast */
}

.status-pending {
    color: #000000;
    background: #fff3cd;
    border-color: #856404; /* 3:1 against bg */
}

.status-completed {
    color: #155724;
    background: #d4edda;
    border-color: #28a745; /* 3:1 against bg */
}
```

**Automated Contrast Testing**:
```javascript
// resources/js/utils/contrast-checker.js
export function checkContrast(foreground, background) {
    const luminance = (color) => {
        const rgb = color.match(/\d+/g).map(Number);
        const [r, g, b] = rgb.map(val => {
            val /= 255;
            return val <= 0.03928
                ? val / 12.92
                : Math.pow((val + 0.055) / 1.055, 2.4);
        });
        return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    };

    const l1 = luminance(foreground);
    const l2 = luminance(background);
    const ratio = (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);

    return {
        ratio: ratio.toFixed(2),
        passAA: ratio >= 4.5,
        passAAA: ratio >= 7.0,
    };
}
```

---

### 2. Typography & Readability

#### Font Sizing Strategy

```css
/* resources/css/app.css */

/* Base font size - user can zoom */
html {
    font-size: 16px; /* Never use px for body text */
}

/* Use rem for scalability */
body {
    font-size: 1rem; /* 16px */
    line-height: 1.5; /* WCAG recommends 1.5 minimum */
}

h1 {
    font-size: 2.5rem; /* 40px */
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

h2 {
    font-size: 2rem; /* 32px */
    line-height: 1.3;
    margin-bottom: 1.25rem;
}

h3 {
    font-size: 1.5rem; /* 24px */
    line-height: 1.4;
}

/* Small text must be at least 14px (0.875rem) */
.text-small {
    font-size: 0.875rem;
}

/* Ensure text spacing is adjustable */
* {
    letter-spacing: normal;
    word-spacing: normal;
}

/* WCAG 1.4.12 - Text Spacing */
.user-adjustable-text {
    line-height: 1.5 !important;
    letter-spacing: 0.12em !important;
    word-spacing: 0.16em !important;
    paragraph-spacing: 2em !important;
}
```

#### Responsive Typography

```css
/* Mobile-first approach */
@media (max-width: 640px) {
    html {
        font-size: 14px; /* Slightly smaller on mobile */
    }

    h1 {
        font-size: 2rem; /* 28px on mobile */
    }
}

@media (min-width: 1024px) {
    html {
        font-size: 18px; /* Larger on desktop */
    }
}

/* Support 200% zoom without horizontal scroll */
@media (max-width: 1280px) and (min-resolution: 2dppx) {
    .container {
        max-width: 100%;
        padding: 0 1rem;
    }
}
```

---

### 3. Keyboard Navigation & Focus Management

#### Focus Indicators

```css
/* resources/css/components/focus.css */

/* Global focus styles - MUST be visible */
*:focus {
    outline: 3px solid #0066CC;
    outline-offset: 2px;
}

/* Never remove outline without replacement */
*:focus:not(:focus-visible) {
    outline: none;
}

*:focus-visible {
    outline: 3px solid #0066CC;
    outline-offset: 2px;
}

/* Button focus states */
button:focus,
a:focus {
    outline: 3px solid #0066CC;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.25);
}

/* Input focus states */
input:focus,
textarea:focus,
select:focus {
    outline: 2px solid #0066CC;
    outline-offset: 1px;
    border-color: #0066CC;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.25);
}

/* Map marker focus */
.leaflet-marker-icon:focus {
    outline: 3px solid #FFD700; /* High contrast on map */
    outline-offset: 3px;
}

/* Skip link focus */
.skip-link:focus {
    position: absolute;
    top: 0;
    left: 0;
    background: #0066CC;
    color: white;
    padding: 1rem;
    z-index: 9999;
    outline: 3px solid white;
    outline-offset: -3px;
}
```

#### Skip Navigation Links

```blade
{{-- resources/views/components/layout/app.blade.php --}}
<body>
    {{-- Skip links for keyboard users --}}
    <div class="skip-links">
        <a href="#main-content" class="skip-link">
            {{ __('Skip to main content') }}
        </a>
        <a href="#ticket-form" class="skip-link">
            {{ __('Skip to report form') }}
        </a>
        <a href="#ticket-list" class="skip-link">
            {{ __('Skip to ticket list') }}
        </a>
    </div>

    <x-layout.header />

    <main id="main-content" tabindex="-1">
        {{ $slot }}
    </main>

    <x-layout.footer />
</body>
```

```css
/* Hide skip links until focused */
.skip-link {
    position: absolute;
    top: -100px;
    left: 0;
    background: #0066CC;
    color: white;
    padding: 0.5rem 1rem;
    text-decoration: none;
    z-index: 9999;
    transition: top 0.2s;
}

.skip-link:focus {
    top: 0;
}
```

#### Tab Order & Keyboard Traps

```blade
{{-- Ticket creation form with logical tab order --}}
<form @submit.prevent="submitTicket" class="ticket-form">
    {{-- tabindex=0 for normal flow, -1 to exclude from tab order --}}

    <div>
        <label for="title">{{ __('Title') }}</label>
        <input type="text" id="title" name="title" tabindex="0" required>
    </div>

    <div>
        <label for="category">{{ __('Category') }}</label>
        <select id="category" name="category_id" tabindex="0" required>
            {{-- options --}}
        </select>
    </div>

    {{-- Map interaction --}}
    <div>
        <label id="map-label">{{ __('Location') }}</label>
        <div id="map"
             role="application"
             aria-labelledby="map-label"
             tabindex="0"
             @keydown.enter="selectLocationOnMap"
             @keydown.space.prevent="selectLocationOnMap">
        </div>
        <p class="text-sm text-gray-600">
            {{ __('Press Enter or Space to select location') }}
        </p>
    </div>

    <div>
        <label for="description">{{ __('Description') }}</label>
        <textarea id="description" name="description" tabindex="0"></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" tabindex="0">{{ __('Submit') }}</button>
        <button type="button" @click="cancel" tabindex="0">{{ __('Cancel') }}</button>
    </div>
</form>
```

**Modal Focus Trap**:
```javascript
// resources/js/utils/focus-trap.js
export function createFocusTrap(element) {
    const focusableElements = element.querySelectorAll(
        'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
    );

    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    // Save previously focused element
    const previouslyFocused = document.activeElement;

    // Focus first element
    firstFocusable?.focus();

    function handleTabKey(e) {
        if (e.key !== 'Tab') return;

        if (e.shiftKey) {
            // Shift + Tab
            if (document.activeElement === firstFocusable) {
                e.preventDefault();
                lastFocusable?.focus();
            }
        } else {
            // Tab
            if (document.activeElement === lastFocusable) {
                e.preventDefault();
                firstFocusable?.focus();
            }
        }
    }

    element.addEventListener('keydown', handleTabKey);

    return {
        destroy() {
            element.removeEventListener('keydown', handleTabKey);
            previouslyFocused?.focus();
        }
    };
}
```

**Usage in Livewire Modal**:
```blade
{{-- resources/views/livewire/ticket-detail-modal.blade.php --}}
<div x-data="{
    focusTrap: null,
    open: @entangle('showModal')
}"
     x-init="
        $watch('open', value => {
            if (value) {
                $nextTick(() => {
                    focusTrap = createFocusTrap($refs.modal);
                });
            } else {
                focusTrap?.destroy();
            }
        })
     "
     @keydown.escape.window="open = false">

    <div x-show="open"
         x-ref="modal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-title">

        <h2 id="modal-title">{{ $ticket->title }}</h2>

        {{-- Modal content --}}

        <button @click="open = false">{{ __('Close') }}</button>
    </div>
</div>
```

---

### 4. Images & Media Accessibility

#### Image Alt Text Strategy

```blade
{{-- Ticket images with contextual alt text --}}
@if($ticket->image_url)
    <figure>
        <img src="{{ $ticket->image_url }}"
             alt="{{ __('Photo of :category in :location reported on :date', [
                 'category' => $ticket->category->name,
                 'location' => $ticket->address,
                 'date' => $ticket->created_at->format('d/m/Y')
             ]) }}"
             loading="lazy"
             width="800"
             height="600">

        <figcaption>
            {{ $ticket->title }}
            <span class="sr-only">
                - {{ __('Reported by :name', ['name' => $ticket->owner->name]) }}
            </span>
        </figcaption>
    </figure>
@endif

{{-- Decorative images --}}
<img src="{{ asset('images/hero-bg.svg') }}"
     alt=""
     role="presentation"
     aria-hidden="true">

{{-- Icon-only buttons --}}
<button type="button"
        aria-label="{{ __('Delete ticket :id', ['id' => $ticket->id]) }}">
    <svg aria-hidden="true"><!-- Trash icon --></svg>
</button>
```

#### SVG Accessibility

```blade
{{-- Accessible SVG icons --}}
<svg role="img"
     aria-labelledby="icon-title-{{ $ticket->id }}"
     class="status-icon">
    <title id="icon-title-{{ $ticket->id }}">
        {{ __('Status: :status', ['status' => $ticket->status->label()]) }}
    </title>
    <use xlink:href="#icon-{{ $ticket->status->value }}"></use>
</svg>

{{-- Decorative SVG --}}
<svg aria-hidden="true" focusable="false">
    <!-- Decorative pattern -->
</svg>
```

#### Video/Audio Accessibility

```blade
{{-- If tickets support video reports --}}
@if($ticket->video_url)
    <figure>
        <video controls
               preload="metadata"
               width="640"
               height="360">
            <source src="{{ $ticket->video_url }}" type="video/mp4">
            <track kind="captions"
                   src="{{ $ticket->captions_url }}"
                   srclang="it"
                   label="Italiano">
            <track kind="descriptions"
                   src="{{ $ticket->descriptions_url }}"
                   srclang="it"
                   label="Descrizione audio">
            <p>{{ __('Your browser does not support video playback.') }}</p>
        </video>

        <figcaption>
            {{ $ticket->title }}
            <a href="{{ $ticket->transcript_url }}" download>
                {{ __('Download transcript') }}
            </a>
        </figcaption>
    </figure>
@endif
```

---

### 5. Forms & Input Accessibility

#### Form Labels & Instructions

```blade
{{-- Ticket submission form --}}
<form method="POST" action="{{ route('tickets.store') }}" class="ticket-form">
    @csrf

    {{-- Required field indicator --}}
    <p class="form-required-legend">
        <span aria-label="{{ __('required') }}">*</span> = {{ __('Required field') }}
    </p>

    <fieldset>
        <legend>{{ __('Ticket Information') }}</legend>

        {{-- Title field --}}
        <div class="form-group">
            <label for="ticket-title">
                {{ __('Title') }}
                <span class="required" aria-label="{{ __('required') }}">*</span>
            </label>
            <input type="text"
                   id="ticket-title"
                   name="title"
                   required
                   aria-required="true"
                   aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}"
                   aria-describedby="title-help {{ $errors->has('title') ? 'title-error' : '' }}"
                   value="{{ old('title') }}">

            <p id="title-help" class="form-help">
                {{ __('Brief description of the issue (max 100 characters)') }}
            </p>

            @error('title')
                <p id="title-error" class="form-error" role="alert">
                    <span class="sr-only">{{ __('Error') }}:</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Category select --}}
        <div class="form-group">
            <label for="category">
                {{ __('Category') }}
                <span class="required" aria-label="{{ __('required') }}">*</span>
            </label>
            <select id="category"
                    name="category_id"
                    required
                    aria-required="true">
                <option value="">{{ __('Select a category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Description textarea --}}
        <div class="form-group">
            <label for="description">{{ __('Description') }}</label>
            <textarea id="description"
                      name="description"
                      rows="5"
                      aria-describedby="description-help"
                      maxlength="500">{{ old('description') }}</textarea>

            <p id="description-help" class="form-help">
                {{ __('Detailed description of the issue') }}
                <span aria-live="polite" aria-atomic="true">
                    <span class="char-count">
                        <span id="char-count">0</span>/500
                    </span>
                </span>
            </p>
        </div>
    </fieldset>

    <fieldset>
        <legend>{{ __('Location') }}</legend>

        {{-- Address autocomplete --}}
        <div class="form-group">
            <label for="address">{{ __('Address') }}</label>
            <input type="text"
                   id="address"
                   name="address"
                   autocomplete="street-address"
                   aria-describedby="address-help"
                   aria-autocomplete="list"
                   aria-controls="address-suggestions"
                   @input="searchAddress">

            <p id="address-help" class="form-help">
                {{ __('Start typing to search for an address') }}
            </p>

            <ul id="address-suggestions"
                role="listbox"
                aria-label="{{ __('Address suggestions') }}"
                x-show="suggestions.length > 0">
                <template x-for="(suggestion, index) in suggestions" :key="index">
                    <li role="option"
                        :aria-selected="index === selectedIndex"
                        @click="selectAddress(suggestion)"
                        @keydown.enter="selectAddress(suggestion)">
                        <span x-text="suggestion.label"></span>
                    </li>
                </template>
            </ul>
        </div>
    </fieldset>

    {{-- Form actions --}}
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            {{ __('Submit Report') }}
        </button>
        <button type="reset" class="btn-secondary">
            {{ __('Reset Form') }}
        </button>
    </div>
</form>
```

#### Error Handling & Live Validation

```javascript
// resources/js/components/form-validation.js
document.addEventListener('alpine:init', () => {
    Alpine.data('ticketForm', () => ({
        errors: {},
        touched: {},

        validateField(field, value) {
            let error = null;

            switch (field) {
                case 'title':
                    if (!value) {
                        error = this.$t('Title is required');
                    } else if (value.length > 100) {
                        error = this.$t('Title must be less than 100 characters');
                    }
                    break;

                case 'category_id':
                    if (!value) {
                        error = this.$t('Please select a category');
                    }
                    break;
            }

            this.errors[field] = error;

            // Update ARIA
            const input = document.getElementById(field);
            if (input) {
                input.setAttribute('aria-invalid', error ? 'true' : 'false');
            }

            // Announce error to screen readers
            if (error && this.touched[field]) {
                this.announceError(field, error);
            }
        },

        announceError(field, message) {
            const liveRegion = document.getElementById('form-errors-live');
            if (liveRegion) {
                liveRegion.textContent = `${field}: ${message}`;
            }
        }
    }));
});
```

```blade
{{-- Live region for error announcements --}}
<div id="form-errors-live"
     class="sr-only"
     role="status"
     aria-live="polite"
     aria-atomic="true">
</div>
```

---

### 6. ARIA Landmarks & Semantic Structure

#### Page Structure Template

```blade
{{-- resources/views/components/layout/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    {{-- Meta tags --}}
</head>
<body>
    {{-- Skip links --}}
    <div class="skip-links">
        <a href="#main-content" class="skip-link">{{ __('Skip to main content') }}</a>
    </div>

    {{-- Header --}}
    <header role="banner">
        <div class="container">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.svg') }}"
                     alt="{{ config('app.name') }} - {{ __('Home') }}">
            </a>

            <nav role="navigation" aria-label="{{ __('Main navigation') }}">
                <ul>
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('tickets.index') }}">{{ __('Reports') }}</a></li>
                    <li><a href="{{ route('tickets.create') }}">{{ __('New Report') }}</a></li>
                    <li><a href="{{ route('about') }}">{{ __('About') }}</a></li>
                </ul>
            </nav>

            {{-- User menu --}}
            @auth
                <nav role="navigation" aria-label="{{ __('User menu') }}">
                    <button aria-expanded="false"
                            aria-controls="user-menu"
                            @click="userMenuOpen = !userMenuOpen">
                        {{ Auth::user()->name }}
                    </button>
                    <ul id="user-menu" hidden>
                        <li><a href="{{ route('profile') }}">{{ __('Profile') }}</a></li>
                        <li><a href="{{ route('logout') }}">{{ __('Logout') }}</a></li>
                    </ul>
                </nav>
            @endauth
        </div>
    </header>

    {{-- Main content --}}
    <main id="main-content" role="main" tabindex="-1">
        {{-- Breadcrumbs --}}
        @if(isset($breadcrumbs))
            <nav aria-label="{{ __('Breadcrumb') }}">
                <ol class="breadcrumbs">
                    @foreach($breadcrumbs as $breadcrumb)
                        <li>
                            @if($loop->last)
                                <span aria-current="page">{{ $breadcrumb['label'] }}</span>
                            @else
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        {{-- Flash messages --}}
        @if(session('success'))
            <div role="alert" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Page content --}}
        {{ $slot }}
    </main>

    {{-- Sidebar (if present) --}}
    @if(isset($sidebar))
        <aside role="complementary" aria-label="{{ __('Filters and additional information') }}">
            {{ $sidebar }}
        </aside>
    @endif

    {{-- Footer --}}
    <footer role="contentinfo">
        <div class="container">
            <nav aria-label="{{ __('Footer navigation') }}">
                <ul>
                    <li><a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="{{ route('terms') }}">{{ __('Terms of Service') }}</a></li>
                    <li><a href="{{ route('accessibility') }}">{{ __('Accessibility') }}</a></li>
                    <li><a href="{{ route('contact') }}">{{ __('Contact Us') }}</a></li>
                </ul>
            </nav>

            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </footer>

    {{-- Live region for dynamic announcements --}}
    <div id="live-region"
         class="sr-only"
         role="status"
         aria-live="polite"
         aria-atomic="true">
    </div>
</body>
</html>
```

---

### 7. Accessibility Testing & Validation

#### Automated Testing Setup

```bash
# Install testing tools
npm install --save-dev @axe-core/cli pa11y cypress-axe
```

```javascript
// package.json
{
    "scripts": {
        "test:a11y": "npm run test:axe && npm run test:pa11y",
        "test:axe": "axe http://localhost:8000 --tags wcag2a,wcag2aa,wcag21a,wcag21aa",
        "test:pa11y": "pa11y-ci --config .pa11yci.json"
    }
}
```

```.pa11yci.json
{
    "defaults": {
        "standard": "WCAG2AA",
        "timeout": 10000,
        "chromeLaunchConfig": {
            "args": ["--no-sandbox"]
        }
    },
    "urls": [
        "http://localhost:8000/",
        "http://localhost:8000/segnalazioni",
        "http://localhost:8000/segnalazioni/nuova",
        "http://localhost:8000/login"
    ]
}
```

#### Cypress Accessibility Tests

```javascript
// cypress/e2e/accessibility/ticket-form.cy.js
describe('Ticket Form Accessibility', () => {
    beforeEach(() => {
        cy.visit('/segnalazioni/nuova');
        cy.injectAxe();
    });

    it('should have no accessibility violations', () => {
        cy.checkA11y();
    });

    it('should have proper focus order', () => {
        cy.get('#title').should('have.focus');
        cy.realPress('Tab');
        cy.get('#category').should('have.focus');
        cy.realPress('Tab');
        cy.get('#description').should('have.focus');
    });

    it('should announce errors to screen readers', () => {
        cy.get('form').submit();
        cy.get('[role="alert"]').should('be.visible');
        cy.get('#title').should('have.attr', 'aria-invalid', 'true');
    });

    it('should support keyboard navigation', () => {
        cy.get('#category').realPress('Enter');
        cy.get('[role="listbox"]').should('be.visible');
        cy.realPress('ArrowDown');
        cy.realPress('Enter');
        cy.get('#category').should('have.value', '1');
    });
});
```

---

## Implementation Checklist

### Theme-Wide Accessibility Tasks

#### Critical (Level A - Must Fix)
- [ ] Audit color contrast for all text/background combinations
- [ ] Add alt text to all meaningful images
- [ ] Ensure all forms have proper labels
- [ ] Implement skip navigation links
- [ ] Fix keyboard navigation for all interactive elements
- [ ] Remove any keyboard traps
- [ ] Add ARIA labels to icon-only buttons
- [ ] Ensure proper heading hierarchy on all pages
- [ ] Add language attribute to HTML tag
- [ ] Test with keyboard only (no mouse)

#### High Priority (Level AA - Required for Compliance)
- [ ] Ensure 4.5:1 contrast for all text
- [ ] Ensure 3:1 contrast for UI components
- [ ] Implement visible focus indicators
- [ ] Add error suggestions for form validation
- [ ] Ensure consistent navigation across pages
- [ ] Add captions to video content (if any)
- [ ] Support 200% text zoom without loss of functionality
- [ ] Test with NVDA screen reader
- [ ] Test with VoiceOver screen reader

#### Nice to Have (Level AAA - Enhanced Accessibility)
- [ ] Achieve 7:1 contrast for body text
- [ ] Provide sign language interpretation for videos
- [ ] Implement extended audio descriptions
- [ ] Add reading level indicators

---

## Resources

### Testing Tools
- **MAUVE++**: https://mauve.isti.cnr.it/ (Italian accessibility validator)
- **axe DevTools**: Browser extension for automated testing
- **NVDA**: Free screen reader for Windows
- **VoiceOver**: Built-in screen reader for macOS/iOS
- **Color Contrast Analyzer**: Desktop app for contrast checking

### Documentation
- **WCAG 2.1**: https://www.w3.org/TR/WCAG21/
- **ARIA Authoring Practices**: https://www.w3.org/WAI/ARIA/apg/
- **WebAIM**: https://webaim.org/resources/

---

**Document Version**: 1.0
**Next Review**: January 2026
