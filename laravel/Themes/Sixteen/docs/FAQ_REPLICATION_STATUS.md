# FAQ Replication Status (domande-frequenti)

> [Index](#index) | [📋 Design Comuni Index](./00-INDEX.md) | [🔗 Back to Theme Docs](./00-INDEX.md)

## Status: 🔄 IN PROGRESS

**Goal**: Replicate https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html locally (http://127.0.0.1:8000/it/tests/domande-frequenti) using **Tailwind CSS + Alpine.js** (NO Bootstrap Italia).

---

## Index
- [Current Implementation](#current-implementation)
- [Reference Structure](#reference-structure)
- [Known Differences](#known-differences)
- [Block Types](#block-types)
- [Implementation Phases](#implementation-phases)
- [Screenshots & Analysis](#screenshots--analysis)

---

## Current Implementation

### Config Files
- **Content**: `laravel/config/local/fixcity/database/content/pages/tests.domande-frequenti.json`
- **Template**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- **Styles**: `laravel/Themes/Sixteen/resources/css/**/*.css` (Tailwind)
- **Scripts**: `laravel/Themes/Sixteen/resources/js/**/*.js` (Alpine.js)

### Content Structure
The JSON file defines 7 content blocks in order:
1. **breadcrumb** - Navigation trail (Home > Domande frequenti)
2. **hero** - Page title + subtitle
3. **search** - FAQ search input
4. **accordion** - 20 FAQ items (Q&A pairs)
5. **feedback** - User rating component
6. **contacts** - Footer contact methods
7. **footer** - (handled by theme footer)

---

## Reference Structure

### Reference Design Analysis
**Reference URL**: https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html

#### Tag Statistics (Reference)
| Tag | Count | Purpose |
|-----|-------|---------|
| `<div>` | 215 | Layout containers |
| `<a>` | 139 | Links (nav, breadcrumb, footer) |
| `<li>` | 101 | List items (nav, breadcrumb, contacts) |
| `<span>` | 97 | Text wrappers, icons |
| `<svg>` | 49 | Icons (expand, chevron, social) |
| `<use>` | 43 | SVG references |
| `<p>` | 34 | FAQ answers + body text |
| `<h1-h4>` | 39 | Headings |
| `<button>` | 10 | Interactive elements |
| `<input>` | 18 | Search + forms |
| `<label>` | 18 | Form labels |

#### Key Sections
```
<header>
  - it-header-slim-wrapper (language selector, login)
  - it-header-center-wrapper (logo, brand, social media)
  - it-header-navbar-wrapper (main navigation)

<main id="main-container">
  - breadcrumbs
  - hero section
  - search input
  - accordion.faq (20 items)
  
<footer>
  - feedback component
  - contacts component
```

---

## Known Differences

### HTML Structure
| Aspect | Reference | Local | Status |
|--------|-----------|-------|--------|
| Container wrapper | `container col-12 col-lg-10` | Tailwind responsive | ✅ Same |
| Accordion items | `.accordion .accordion-item` | Alpine + Tailwind | ⚠️ Different approach |
| Icon system | `<use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#...">` | Alpine + custom SVG | ⚠️ Different path |
| Search input | Bootstrap form-control | Tailwind input classes | ⚠️ Different styling |
| Feedback widget | Bootstrap form | Custom Alpine component | ⚠️ Different |
| Contacts section | Bootstrap grid | Tailwind grid | ⚠️ Different |

### CSS/Styling Gaps
- [ ] Accordion animation (collapse/expand timing)
- [ ] Hero section background + typography
- [ ] Search input styling (border, focus states)
- [ ] Button styling (primary, hover, active)
- [ ] Breadcrumb separators and styling
- [ ] Feedback rating component styling
- [ ] Contact icons and layout
- [ ] Responsive breakpoints alignment

### Alpine.js Interactions
- [ ] Accordion toggle with `activeIndex` state
- [ ] Keyboard navigation (arrow keys)
- [ ] Search filtering (if implemented)
- [ ] Feedback rating submission
- [ ] Smooth expand/collapse animations

---

## Block Types

### 1. Breadcrumb Block
**Reference**: `.cmp-breadcrumbs` > `nav.breadcrumb-container` > `ol.breadcrumb`

**Local**: `pub_theme::components.blocks.breadcrumb.default`

**Data**:
```json
{
  "type": "breadcrumb",
  "data": {
    "view": "pub_theme::components.blocks.breadcrumb.default",
    "items": [
      {"label": "Home", "url": "/it/tests/homepage"},
      {"label": "Domande frequenti", "url": null}
    ]
  }
}
```

### 2. Hero Block
**Reference**: `.cmp-hero` > `section.it-hero-wrapper`

**Local**: `pub_theme::components.blocks.hero.default`

**Data**:
```json
{
  "type": "hero",
  "data": {
    "view": "pub_theme::components.blocks.hero.default",
    "title": "Domande frequenti",
    "subtitle": "Elenco di risposte alle domande più frequenti..."
  }
}
```

### 3. Search Block
**Reference**: `.cmp-input-search` > form with autocomplete

**Local**: `pub_theme::components.blocks.search.input`

### 4. Accordion Block
**Reference**: `.cmp-accordion.faq` > `.accordion`

**Local**: `pub_theme::components.blocks.accordion.default`

**Data**: 20 Q&A items with HTML answers

### 5. Feedback Block
**Reference**: Rating component

**Local**: `pub_theme::components.blocks.feedback.faq-rating`

### 6. Contacts Block
**Reference**: Contact methods grid

**Local**: `pub_theme::components.blocks.contacts.faq`

---

## Implementation Phases

### Phase 1: Structure Verification ✅
- [x] Extract reference HTML structure
- [x] Extract local HTML structure
- [x] Verify block types match
- [x] Document tag statistics

### Phase 2: Visual Analysis 🔄 IN PROGRESS
- [ ] Capture reference screenshots (desktop, tablet, mobile)
- [ ] Capture local screenshots (same sizes)
- [ ] Document visual differences
- [ ] Create diff analysis document

### Phase 3: Tailwind CSS Implementation
- [ ] Analyze reference CSS (Bootstrap Italia classes)
- [ ] Map to Tailwind equivalents
- [ ] Update component styles
- [ ] Test responsive design

### Phase 4: Alpine.js Interactions
- [ ] Implement accordion toggle
- [ ] Add keyboard navigation
- [ ] Smooth animations
- [ ] Test all interactions

### Phase 5: Testing & Polish
- [ ] Browser testing (Chrome, Firefox, Safari)
- [ ] Responsive testing (320px to 1920px)
- [ ] Accessibility testing (WCAG)
- [ ] Performance check

### Phase 6: Documentation
- [ ] Document all CSS changes
- [ ] Document all JS changes
- [ ] Create component guide
- [ ] Add troubleshooting guide

---

## Screenshots & Analysis

### Directory Structure
```
laravel/Themes/Sixteen/docs/
├── screenshots/
│   ├── reference-desktop.png
│   ├── reference-tablet.png
│   ├── reference-mobile.png
│   ├── local-desktop.png
│   ├── local-tablet.png
│   └── local-mobile.png
└── analysis/
    ├── visual-diff-desktop.md
    ├── visual-diff-tablet.md
    └── visual-diff-mobile.md
```

### To Generate Screenshots
```bash
# Run the analyzer script
cd /var/www/_bases/base_fixcity_fila5
./bashscripts/compare/faq-visual-analyzer.sh

# Or manually with Playwright
npx playwright test --update-snapshots
```

---

## Multi-Agent Coordination

**This task may be executed by multiple AI agents simultaneously.**

### Intent Declaration (GitHub Issues)
When starting work, comment on the relevant issue:
- What you're working on (e.g., "CSS: Hero section styling")
- Timeline estimate
- Dependencies

### Progress Updates
- Every 15-20 minutes: Update status comment
- Link related PRs/branches
- Document blocking issues

### Artifact Sharing
- All findings → `laravel/Themes/Sixteen/docs/analysis/`
- Screenshots → `laravel/Themes/Sixteen/docs/screenshots/`
- Code changes → PR with references to this doc

### Integration Points
- Reference comparison → `/tmp/ref_structure.txt`
- Local structure → `/tmp/local_structure.txt`
- Analysis tools → `bashscripts/compare/`

---

## Quick Commands

### Development Setup
```bash
cd laravel/Themes/Sixteen

# Install dependencies
npm install

# Build Tailwind CSS
npm run build

# Copy assets to public_html
npm run copy

# Watch for changes (development)
npm run watch
```

### Testing
```bash
# View in browser
open http://127.0.0.1:8000/it/tests/domande-frequenti

# Screenshot (requires Playwright)
./bashscripts/compare/faq-visual-analyzer.sh

# HTML structure comparison
./bashscripts/compare/html-structure-analyzer.sh
```

### Documentation
```bash
# View this file (you're reading it!)
# Edit and commit changes to track progress

# Update index
ls -lah laravel/Themes/Sixteen/docs/
```

---

## Related Documentation

- 📋 [Theme Index](./00-INDEX.md)
- 🎨 [Component Catalog](./COMPONENT_CATALOG.md)
- ⚙️ [Configuration Guide](./CONFIGURATION.md)
- 🔧 [Development Guide](./DEVELOPMENT.md)
- 🧪 [Testing Guide](./TESTING.md)

---

## Notes

- No Bootstrap Italia classes allowed anywhere
- All styling via Tailwind CSS + custom CSS in `resources/css/`
- All interactions via Alpine.js in `resources/js/`
- Maintain semantic HTML structure
- Keep components modular and reusable
- Document all changes in this file

---

**Last Updated**: 2026-04-03  
**Status**: In Progress  
**Owner**: [Copilot Team]  
**Coordination**: Multi-agent (see section above)
