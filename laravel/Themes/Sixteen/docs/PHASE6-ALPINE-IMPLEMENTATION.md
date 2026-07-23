# Phase 6: Alpine.js Interactive Elements

## Overview

Implement Alpine.js for interactive components:
1. Mobile menu toggle
2. Search modal
3. Dropdown menus
4. Focus/hover states

## Implementation Plan

### 1. Mobile Menu (Hamburger)
**Location:** Header navigation area
**Requirements:**
- Show/hide nav on mobile (<md breakpoint)
- Smooth open/close animation
- Click outside to close
- Keyboard support (ESC to close)

**Alpine Implementation:**
```html
<div x-data="{ open: false }" @keydown.escape="open = false">
  <button @click="open = !open" class="md:hidden">
    <svg><!-- hamburger icon --></svg>
  </button>
  <nav x-show="open" @click.outside="open = false">
    <!-- nav items -->
  </nav>
</div>
```

### 2. Search Modal
**Location:** Header search icon
**Requirements:**
- Click search icon to open modal
- Modal contains search input + submit button
- Click outside/ESC to close
- Focus trap in modal

**Alpine Implementation:**
```html
<div x-data="{ searching: false }">
  <button @click="searching = true">
    <svg><!-- search icon --></svg>
  </button>
  <div x-show="searching" @keydown.escape="searching = false">
    <input type="search" placeholder="Search...">
  </div>
</div>
```

### 3. Language Dropdown
**Location:** Header top-right
**Requirements:**
- Click to open/close
- Select language to change
- Show current language

**Alpine Implementation:**
```html
<div x-data="{ langOpen: false }">
  <button @click="langOpen = !langOpen">
    IT <span>▼</span>
  </button>
  <ul x-show="langOpen" @click.outside="langOpen = false">
    <li><a href="/en">EN</a></li>
    <li><a href="/it">IT</a></li>
  </ul>
</div>
```

### 4. Accessibility Features
**Requirements:**
- ARIA labels on interactive elements
- Keyboard navigation (Tab, Enter, ESC)
- Focus visible states
- Semantic HTML structure

## Technical Requirements

### Alpine.js Version
- Current: Not installed in package.json
- Action: Add Alpine.js 3.x to dependencies
- Bundle: Include in app.js build

### CSS Classes for Alpine
- `x-show` - Display/hide (preserve layout)
- `x-if` - Mount/unmount (destroy layout)
- Transition classes for animations

### Testing Checklist
- [ ] Mobile menu opens/closes
- [ ] Search modal accessible
- [ ] Language dropdown works
- [ ] All click-outside handlers work
- [ ] ESC key closes all modals
- [ ] No console errors
- [ ] Keyboard navigation works
- [ ] Visual parity maintained

## Files to Create/Modify

### New Files
1. `resources/js/components/mobile-menu.js` - Mobile menu Alpine component
2. `resources/js/components/search-modal.js` - Search modal component
3. `resources/js/components/language-dropdown.js` - Language selection
4. `docs/PHASE6-ALPINE-COMPLETE.md` - Implementation completion report

### Modified Files
1. `resources/views/pages/tests/[slug].blade.php` - Add Alpine directives
2. `resources/css/app.css` - Add focus/active states
3. `tailwind.config.js` - Add focus-visible config
4. `package.json` - Add Alpine.js dependency

## Timeline

1. Install Alpine.js
2. Create component files
3. Update blade template
4. Test interactivity
5. Verify visual parity
6. Document completion

---

## 📚 Related Documentation

- **[← INDEX](./INDEX.md)** - Documentation overview
- **[← FINAL-VISUAL-PARITY-REPORT](./FINAL-VISUAL-PARITY-REPORT.md)** - Visual parity verification
- **[→ PHASE3-ALPINE-PLAN](./PHASE3-ALPINE-PLAN.md)** - Previous Alpine planning

**Phase 6 Status**: In Progress 🔄

