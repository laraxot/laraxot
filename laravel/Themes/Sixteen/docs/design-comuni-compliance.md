# Design Comuni Compliance - Sixteen Theme
**Date**: 2025-02-02
**Theme**: Sixteen (FixCity Frontend)
**Reference**: design-comuni-pagine-statiche
**Target**: Full AGID compliance for Italian municipalities

---

## Overview

This document tracks the Sixteen theme's adherence to the design-comuni-pagine-statiche standard for Italian municipal websites.

---

## Current Implementation Status

### ✅ Implemented Components

#### 1. Bootstrap Italia Integration
- **Status**: ✅ IMPLEMENTED
- **Files**:
  - `resources/views/pages/bootstrap-italia-showcase.blade.php`
  - Tailwind CSS configured for AGID colors
- **Compliance**: Follows Bootstrap Italia design patterns

#### 2. AGID-Compliant Page Structure
- **Status**: ✅ IMPLEMENTED
- **Example**: `resources/views/pages/tickets/index.blade.php`
- **Features**:
  - Breadcrumb navigation (ARIA compliant)
  - Page headers with titles and subtitles
  - Semantic HTML5 structure
  - Skip-to-content links
  - Role attributes (main, complementary)

#### 3. Accessibility Features
- **Status**: ✅ GOOD
- **Implemented**:
  - Focus indicators (`.agid-focus` class)
  - ARIA labels and landmarks
  - Keyboard navigation
  - Screen reader support
  - Dark mode with proper contrast

#### 4. Required Static Pages
| Page | Route | Status | AGID Requirement |
|------|-------|--------|------------------|
| Homepage | `/` | ✅ | Required |
| Services | `/services` | ✅ | Required |
| News | `/news` | ✅ | Required |
| Administration | `/administration` | ✅ | Required |
| Tickets List | `/tickets` | ✅ | Custom (civic engagement) |

---

## 🔴 Missing Features (From Gap Analysis)

### 1. Interactive Map Component ⭐ CRITICAL
**Priority**: HIGHEST
**Impact**: Cannot visualize ticket locations

#### Required Implementation
Create reusable theme component at:
`resources/views/components/interactive-map.blade.php`

**Features Needed**:
- Leaflet.js integration
- OpenStreetMap tiles
- Marker clustering
- Click popups
- Geolocation button
- Accessibility controls

**Usage Example**:
```blade
<x-interactive-map
    :markers="$tickets"
    :center="[$defaultLat, $defaultLng]"
    :zoom="12"
    cluster="true"
/>
```

---

### 2. Multi-Step Form Component
**Priority**: HIGH
**Impact**: Ticket submission not AGID compliant

#### Required Implementation
Create wizard component at:
`resources/views/components/multi-step-form.blade.php`

**Features**:
- Step progress indicator
- Navigation buttons (Next, Previous, Save Draft)
- Step validation
- Mobile-responsive
- ARIA live regions for status updates

**AGID Pattern** (4 steps for segnalazione):
```blade
<x-multi-step-form steps="4">
    <x-slot:step1 title="Categoria">
        {{-- Category selection --}}
    </x-slot:step1>

    <x-slot:step2 title="Dettagli e posizione">
        {{-- Description + map picker --}}
    </x-slot:step2>

    <x-slot:step3 title="Allegati">
        {{-- Photo/file upload --}}
    </x-slot:step3>

    <x-slot:step4 title="Riepilogo">
        {{-- Summary and confirmation --}}
    </x-slot:step4>
</x-multi-step-form>
```

---

### 3. Search Component
**Priority**: HIGH
**Impact**: Content discoverability

#### Required Pages
1. `/ricerca` - Search results page
2. Global search bar in header

#### Components Needed
- `resources/views/components/search-bar.blade.php`
- `resources/views/components/search-results.blade.php`
- `resources/views/components/search-filters.blade.php`

**AGID Features**:
- Autocomplete suggestions
- Recent searches
- Filter facets (sidebar)
- Results pagination
- "No results" helpful message

---

### 4. FAQ Component
**Priority**: MEDIUM
**Impact**: User self-service

#### Required Implementation
Create `/faq` page with accordion component:
`resources/views/pages/faq.blade.php`

**AGID Pattern**:
- Categorized FAQs (Submission, Tracking, Privacy, Technical)
- Accordion/collapse UI
- Search within FAQs
- Print-friendly version

---

### 5. Citizen Dashboard
**Priority**: MEDIUM
**Impact**: Ticket tracking experience

#### Required Page
`resources/views/pages/dashboard/index.blade.php` (already exists, needs enhancement)

**Missing Features**:
- "I miei ticket" section with status cards
- Timeline view for ticket history
- Quick actions (view, edit draft, cancel)
- Notification preferences
- Document downloads

---

## 🎨 Design System Compliance

### Color Palette (AGID Compliant)
```css
/* Primary - Italian Blue */
--agid-primary-50: #e3f2fd;
--agid-primary-600: #0066cc; /* Main brand */
--agid-primary-800: #004d99;

/* Status Colors */
--agid-success: #28a745;  /* Green */
--agid-warning: #ffc107;  /* Yellow */
--agid-danger: #dc3545;   /* Red */
--agid-info: #17a2b8;     /* Light Blue */

/* Neutrals */
--agid-gray-50: #f8f9fa;
--agid-gray-900: #212529;
```

### Typography
- **Font Family**: Titillium Web (AGID standard) or system fonts
- **Headings**: Bold, clear hierarchy (h1-h6)
- **Body**: 16px minimum for readability
- **Line Height**: 1.5 for body text

### Spacing
- **Container**: Max-width 1200px (`.agid-container`)
- **Grid**: 12-column responsive grid
- **Gutters**: 1rem (16px) mobile, 1.5rem (24px) desktop

---

## 🔧 Technical Implementation

### Required Dependencies

#### NPM Packages (Add to package.json)
```json
{
  "dependencies": {
    "leaflet": "^1.9.4",
    "leaflet.markercluster": "^1.5.3",
    "@geoman-io/leaflet-geoman-free": "^2.15.0"
  }
}
```

#### CSS Files to Create
1. `resources/css/components/map.css`
2. `resources/css/components/wizard.css`
3. `resources/css/components/search.css`
4. `resources/css/agid-overrides.css`

#### JavaScript Files to Create
1. `resources/js/components/interactive-map.js`
2. `resources/js/components/multi-step-form.js`
3. `resources/js/components/search-autocomplete.js`

---

## 🚀 Implementation Priority

### Sprint 1 (Week 1-2) - CRITICAL
1. **Interactive Map Component** ⭐
   - Install Leaflet dependencies
   - Create Blade component
   - Integrate with FixCity tickets
   - Test accessibility
   - Mobile responsive

2. **Build Process Updates**
   - Add Leaflet to Vite config
   - Update `npm run build` to include map assets
   - Ensure `npm run copy` includes new files

### Sprint 2 (Week 3-4) - HIGH
3. **Multi-Step Form Component**
   - Create wizard component
   - Add progress indicator
   - Implement step validation
   - Draft save functionality

4. **Search Implementation**
   - Global search bar in header
   - Search results page
   - Filter sidebar
   - Autocomplete

### Sprint 3 (Week 5-6) - MEDIUM
5. **FAQ System**
   - Create FAQ page
   - Accordion component
   - Content management

6. **Enhanced Dashboard**
   - "I miei ticket" view
   - Status timeline
   - Notifications panel

---

## 📱 Responsive Breakpoints (AGID Standard)

```css
/* Mobile First */
@media (min-width: 576px) { /* Small tablets */ }
@media (min-width: 768px) { /* Tablets */ }
@media (min-width: 992px) { /* Desktop */ }
@media (min-width: 1200px) { /* Large desktop */ }
```

### Mobile-Specific Considerations
- Touch targets minimum 44x44px
- Hamburger menu for navigation
- Swipeable components (carousels, galleries)
- Geolocation "Trova posizione" button prominent on mobile

---

## ♿ Accessibility Checklist

### WCAG 2.1 AA Requirements
- [ ] Color contrast ratio ≥ 4.5:1 for text
- [ ] Interactive elements ≥ 3:1 contrast
- [ ] Focus indicators visible and clear
- [ ] Keyboard-only navigation functional
- [ ] Form labels properly associated
- [ ] Error messages descriptive and helpful
- [ ] ARIA landmarks on all pages
- [ ] Skip-to-content links
- [ ] Alt text on all images
- [ ] Captions for videos (if any)

### Testing Tools
1. **Lighthouse** (Chrome DevTools)
2. **WAVE** browser extension
3. **axe DevTools** for detailed audits
4. **NVDA/JAWS** screen reader testing
5. **Keyboard-only** manual testing

---

## 📊 Performance Targets

### Core Web Vitals (AGID Requirement)
| Metric | Target | Current | Priority |
|--------|--------|---------|----------|
| Largest Contentful Paint (LCP) | <2.5s | ⚠️ Unknown | HIGH |
| First Input Delay (FID) | <100ms | ⚠️ Unknown | HIGH |
| Cumulative Layout Shift (CLS) | <0.1 | ⚠️ Unknown | HIGH |
| Time to Interactive (TTI) | <3.8s | ⚠️ Unknown | MEDIUM |

### Optimization Strategies
1. **Images**: WebP format, lazy loading, responsive srcset
2. **JavaScript**: Code splitting, dynamic imports, tree shaking
3. **CSS**: Critical CSS inline, defer non-critical
4. **Fonts**: Font-display: swap, WOFF2 format, subsetting
5. **Caching**: Service worker, HTTP cache headers

---

## 🔐 Security & Privacy (GDPR/AGID)

### Required Implementations
- [ ] Cookie consent banner (GDPR)
- [ ] Privacy policy page (`/privacy`)
- [ ] Terms of service page (`/terms`)
- [ ] Cookie policy page (`/cookie-policy`)
- [ ] Data portability (export user data)
- [ ] Right to be forgotten (delete account)

### AGID-Specific Requirements
- [ ] SPID/CIE authentication (production)
- [ ] PEC email integration (certified email)
- [ ] Digital signature support (if needed)
- [ ] Audit log for data access

---

## 🎯 Next Actions

1. **TODAY**: Start interactive map implementation
2. **This Week**: Complete map + multi-step form
3. **Next Week**: Search functionality + FAQ
4. **Before Production**: Lighthouse audit + accessibility testing

---

## 📚 Reference Links

- [Design Comuni](https://designers.italia.it/kit/comuni/)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/)
- [AGID Guidelines](https://docs.italia.it/italia/design/lg-design-servizi-web/)
- [WCAG 2.1](https://www.w3.org/WAI/WCAG21/quickref/)
- [Leaflet Docs](https://leafletjs.com/reference.html)

---

**Document Owner**: Sixteen Theme Team
**Status**: Active Development - AGID Compliance in Progress
