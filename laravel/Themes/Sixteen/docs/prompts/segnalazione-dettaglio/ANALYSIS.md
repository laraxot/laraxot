# Segnalazione Dettaglio - HTML Structure Analysis

## Reference Source
- **URL**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html
- **Design System**: Design Comuni Italia
- **Status**: Official reference implementation
- **File**: `reference.html` (local copy)

---

## Document Overview

### General Statistics
- **Total Lines**: 1,519
- **Format**: HTML5 with Bootstrap 5.3.2
- **Design Framework**: Design Comuni Italia (Bootstrap-based)
- **CSS Assets**: Remote preload via CDN
- **JavaScript**: Module-based with dynamic interactivity

### Key Technologies
- Bootstrap 5.3.2 (responsive grid, components)
- Bootstrap Icons (icon library)
- Popper.js (tooltips, popovers)
- Custom CSS styling from Design Comuni

---

## Structural Hierarchy

### Primary Sections
1. **Document Head** (`<head>`)
   - Meta tags (charset, viewport, description)
   - CSS preload directives
   - Style injections (dark mode, browser logger)

2. **Document Body** (`<body>`)
   - Header Navigation (`#header-nav-wrapper`)
   - Main Container (`#main-container`)
   - Footer Section
   - Script injections (browser logging, theme persistence)

### Major Components

#### 1. Header & Navigation
- **ID**: `header-nav-wrapper`
- **Navigation ID**: `nav4`
- **Navigation Items**: 8 items with class `nav-item`
- **Link Items**: 8 items with class `nav-link`
- **Structure**: Bootstrap navbar with horizontal menu layout

#### 2. Main Content Area
- **ID**: `main-container`
- **Layout**: Bootstrap grid system (`row` classes × 12)
- **Primary Content Grid**: Responsive layout with multiple sections

#### 3. Accordion/FAQ Section
- **Pattern**: Bootstrap accordion components
- **Items**: 19 accordion items (`accordion-item` class)
- **Header Elements**: 19 accordion headers (`accordion-header` class)
- **Collapse Elements**: 19 collapsible sections (`accordion-collapse`, `collapse`)
- **Button Pattern**: `accordion-button collapsed title-small-semi-bold py-3`
- **Body Elements**: 19 accordion bodies

**Accordion Features**:
- Title styling: `title-small-semi-bold` (Design Comuni typography)
- Padding: `py-3` (Bootstrap vertical padding)
- State Management: `collapsed` class for initial state
- Content: Full-paragraph text content in accordion bodies

#### 4. Icon Usage
- **Total Icon Instances**: 23+ (visually hidden + visible)
- **Visible Icons**: 19 instances of class `icon icon-xs me-1 icon-primary`
- **Icon Wrapper**: 19 instances of `icon-wrapper` class
- **Icon Size Variants**:
  - `icon-xs` (extra small - accordion indicators)
  - `icon-sm` (small - footer/other elements)
- **White Icon Pattern**: 18 instances of `icon icon-sm icon-white align-top`
- **Icon Library**: Bootstrap Icons (CDN-based)

#### 5. Footer Section
- **Lists**: 7 footer lists with class `footer-list`
- **Navigation Structure**: Hierarchical links
- **Styling**: Design Comuni footer styling

#### 6. Accessibility Features
- **Visually Hidden Elements**: 23 instances
  - Purpose: Screen reader content
  - Class: `visually-hidden`
  - Common Use Cases**: Skip links, label clarification, icon labels

---

## CSS Class Patterns

### Most Frequent Classes
| Class | Count | Purpose |
|-------|-------|---------|
| `visually-hidden` | 23 | Screen reader only content |
| `icon-wrapper` | 19 | Icon container styling |
| `icon icon-xs me-1 icon-primary` | 19 | Small primary icons with margin |
| `button-wrapper` | 19 | Button container |
| `accordion-item` | 19 | Accordion component wrapper |
| `accordion-header` | 19 | Accordion header |
| `accordion-collapse collapse` | 19 | Collapsible container |
| `accordion-button collapsed` | 19 | Accordion trigger button |
| `accordion-body` | 19 | Accordion content |
| `icon icon-sm icon-white` | 18 | Small white icons |
| `row` | 12 | Bootstrap grid rows |
| `nav-link` | 8 | Navigation links |
| `nav-item` | 8 | Navigation items |
| `icon` | 8 | Icon elements |
| `footer-list` | 7 | Footer link lists |

---

## Key IDs & Element Identifiers

### Structural IDs
- `#browser-logger-active` - Browser logging script ID
- `#header-nav-wrapper` - Header/navbar container
- `#nav4` - Main navigation element
- `#main-container` - Primary content wrapper
- `#autocomplete-three` - Autocomplete component reference
- `#button-3` - Button reference

### Accordion IDs (Pattern)
- `#accordion-faq-{hash}` - Main accordion container
- `#headingfaq-{n}` - Accordion header (e.g., `headingfaq-1`)
- `#collapsefaq-{n}` - Collapsible section (e.g., `collapsefaq-1`)

**Total Accordion Items**: 7 FAQ items (headingfaq-1 through headingfaq-7, collapsefaq-1 through collapsefaq-7)

---

## Element Count Summary

| Element Type | Count | Notes |
|--------------|-------|-------|
| Accordion Items | 19 | 7 FAQ pairs × 3 (header, button, body) |
| Navigation Items | 8 | Main menu items |
| Footer Lists | 7 | Footer navigation sections |
| Icon Instances | 45+ | Distributed across components |
| Bootstrap Rows | 12 | Grid layout components |
| Visually Hidden | 23 | Accessibility enhancements |

---

## Design Comuni Styling Conventions

### Typography Classes
- `title-small-semi-bold` - Heading style used in accordions

### Color Classes
- `icon-primary` - Primary brand color for icons
- `icon-white` - White color variant (used on dark backgrounds)

### Spacing & Layout
- `me-1` - Bootstrap margin-end (right padding)
- `py-3` - Bootstrap padding Y-axis (vertical)
- `align-top` - Bootstrap alignment utility

### Bootstrap Integration
- Grid system (`row`, `col-*`)
- Accordion components (`.accordion-*` classes)
- Navigation components (`.nav-item`, `.nav-link`)
- Utilities (visually-hidden, spacing, alignment)

---

## Mapping to Local Implementation

### Reference → Local Implementation

| Reference Component | Local Implementation | Notes |
|---|---|---|
| Header/Navigation | Local theme header component | May use Livewire/Volt components |
| Main Content Grid | Local page layout system | May use theme-specific grid |
| Accordion (FAQ) | Local accordion component | Verify 19-element pattern |
| Icons | Bootstrap Icons library | Verify icon names/sizes match |
| Accessibility | Local visually-hidden utility | Should include 23+ hidden elements |
| Footer | Local theme footer | Footer list structure |

### Component Checklist
- [ ] Navigation: 8 items rendered correctly
- [ ] Accordion: 19 elements (7 FAQ pairs)
- [ ] Icons: Primary, small, white variants
- [ ] Typography: `title-small-semi-bold` styling
- [ ] Grid: 12 row elements in layout
- [ ] Accessibility: All visually-hidden elements present

---

## Known Deviations & Rationale

### Expected Deviations (Design Acceptable)
1. **JavaScript Framework**
   - Reference: Vanilla JS + Bootstrap JS
   - Local: Livewire/Volt components (dynamic rendering)
   - Rationale: Laravel framework benefits; functionality preserved

2. **Asset Delivery**
   - Reference: CDN-based CSS/JS
   - Local: Vite build pipeline + theme assets
   - Rationale: Improved performance, caching; visual output identical

3. **HTML Nesting**
   - Reference: Static HTML structure
   - Local: Template-generated structure (may vary slightly)
   - Rationale: Dynamic content generation; final rendered output should match

4. **ID/Class Generation**
   - Reference: Hardcoded IDs (e.g., `accordion-faq-hash`)
   - Local: May use data attributes or dynamic IDs
   - Rationale: Functional equivalence; visual and semantic output preserved

### What Must Match
- **Visual Appearance**: Layout, typography, colors, spacing
- **Functionality**: All interactive elements work identically
- **Accessibility**: ARIA attributes and visually-hidden content
- **Semantic Structure**: Element hierarchy and content organization

---

## Parity Analysis Reports

### Generated Reports Location
Reports are generated by comparison scripts and saved to:
```
../../../body-structure-comparison/segnalazione-dettaglio/
```

### Report Types
1. **comparison-report.json**
   - Machine-readable detailed comparison data
   - Element-by-element analysis
   - Difference metrics

2. **comparison-report.md**
   - Human-readable comparison summary
   - Section-by-section breakdown
   - Parity score calculation

3. **details/** Directory
   - Per-element detailed differences
   - Side-by-side comparisons
   - CSS/attribute analysis

### Interpretation
- **Parity Score**: Percentage of elements matching reference structure
- **Minor Deviations**: Class ordering, whitespace, non-semantic attributes
- **Major Deviations**: Missing elements, structural differences, functionality gaps

---

## Links & References

### Related Documentation
- [HTML Parity Output & Reports](../../body-structure-comparison/segnalazione-dettaglio/README.md) - Analysis results
- [Design Comuni Theme](../../00-INDEX.md) - Theme documentation
- [Comparison Scripts](../../../bashscripts/) - Generation scripts

### External Resources
- [Design Comuni Pagine Statiche](https://italia.github.io/design-comuni-pagine-statiche/) - Official reference
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3/) - Bootstrap documentation
- [Bootstrap Icons](https://icons.getbootstrap.com/) - Icon reference

---

## Next Steps

1. **Run Comparison Scripts**
   ```bash
   ./bashscripts/compare-html-structure.sh segnalazione-dettaglio
   ```

2. **Review Parity Report**
   Check `body-structure-comparison/segnalazione-dettaglio/comparison-report.md`

3. **Address Major Deviations**
   - Identify structural issues
   - Update local implementation if needed
   - Document rationale for acceptable deviations

4. **Verify Functionality**
   - Test all interactive elements
   - Validate accessibility features
   - Confirm visual parity in browser

---

**Last Updated**: See git history  
**Status**: Reference file captured  
**Next Phase**: Run comparison analysis
