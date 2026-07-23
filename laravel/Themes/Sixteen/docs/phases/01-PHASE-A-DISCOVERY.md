# Phase A: Discovery & Analysis

> Complete inventory of HTML structure, CSS, and component differences

**Status**: ⏳ In Progress  
**Duration**: 2-3 hours  
**Owner**: Explorer Agent + Designer  
**Last Updated**: 2026-04-02

---

## 🎯 Objective

Complete a comprehensive analysis of differences between the reference Design Comuni homepage and the local Laravel implementation. This analysis feeds all subsequent phases.

---

## �� Deliverables

- [x] HTML structure analysis complete
- [ ] Screenshots captured (3 viewports × 2 sites = 6 files)
- [ ] Bootstrap Italia classes inventory
- [ ] Component breakdown documented
- [ ] Responsive patterns documented
- [ ] Visual differences annotated
- [ ] All analysis docs linked in index

---

## ✅ Success Criteria

| Criterion | Definition | Status |
|-----------|-----------|--------|
| **Structure Analysis** | 90%+ element match identified | ⏳ |
| **Screenshots** | All 6 captured (1920, 768, 375) | ⏳ |
| **Class Inventory** | 100% of Bootstrap classes catalogued | ⏳ |
| **Component Map** | All major blocks identified & analyzed | ⏳ |
| **Responsive Mapping** | All breakpoint behaviors documented | ⏳ |
| **Visual Issues** | 50+ specific CSS differences noted | ⏳ |
| **Documentation** | All analysis docs complete & linked | ⏳ |

---

## 🚀 Tasks

### Task 1: Capture Screenshots (30 min)

**Goal**: Get 6 raw screenshots for visual comparison

**Steps**:
1. Run screenshot capture script:
   ```bash
   ./bashscripts/screenshot-homepage.sh
   ```
   OR manually capture:
   - Reference at 1920×1080, 768×1024, 375×667
   - Local at same viewports
   
2. Store files in: `laravel/Themes/Sixteen/docs/screenshots/`
   - `reference_desktop.png`
   - `reference_tablet.png`
   - `reference_mobile.png`
   - `local_desktop.png`
   - `local_tablet.png`
   - `local_mobile.png`

3. Verify all files exist:
   ```bash
   ls -lh laravel/Themes/Sixteen/docs/screenshots/*.png
   ```

**Done When**: All 6 screenshots captured and stored

---

### Task 2: HTML Structure Analysis (45 min)

**Goal**: Document HTML element differences

**Output**: `analysis/01-HTML-STRUCTURE-ANALYSIS.md`

**Analyze**:

1. **Overall Statistics**
   - Reference element count: 159
   - Local element count: 127
   - Difference: 32 elements (20%)
   - Reason: [TBD - to be discovered]

2. **Element Inventory by Component**
   ```
   Hero Section
   - Reference: [count]
   - Local: [count]
   - Missing/Extra: [list]
   
   Governance Cards
   - Reference: [count]
   - Local: [count]
   - Missing/Extra: [list]
   
   [... repeat for each component ...]
   ```

3. **Semantic HTML Issues**
   - `<h1>`, `<h2>`, `<h3>` hierarchy preserved?
   - `<nav>` landmarks present?
   - `<main>`, `<section>`, `<article>` used correctly?

4. **Attribute Differences**
   - Classes used (Bootstrap, custom)
   - IDs for hooks
   - Data attributes
   - ARIA labels

5. **SVG/Image References**
   - Sprite paths
   - Image CDN URLs
   - Relative vs. absolute paths

**Document Format**:
```markdown
## [Component Name]

### Reference
- Elements: [count]
- Structure: [nested div structure]

### Local
- Elements: [count]
- Structure: [nested div structure]

### Differences
- Missing: [list]
- Extra: [list]
- Layout changes: [description]
```

---

### Task 3: CSS Framework Audit (45 min)

**Goal**: Extract all Bootstrap Italia classes used

**Output**: `analysis/02-CSS-FRAMEWORK-AUDIT.md`

**Audit Process**:

1. **Extract all classes from reference HTML**:
   ```bash
   # Extract unique classes
   curl -s https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html | \
   grep -oP 'class="[^"]*"' | sed 's/class="//;s/"//' | tr ' ' '\n' | sort -u > /tmp/ref_classes.txt
   ```

2. **Extract all classes from local HTML**:
   ```bash
   curl -s http://127.0.0.1:8000/it/tests/homepage | \
   grep -oP 'class="[^"]*"' | sed 's/class="//;s/"//' | tr ' ' '\n' | sort -u > /tmp/local_classes.txt
   ```

3. **Categorize Bootstrap classes**:
   - **Grid**: `.container`, `.row`, `.col-*` (15+ variations)
   - **Components**: `.card`, `.btn-*`, `.chip-*`, `.badge-*`
   - **Typography**: `.text-*`, `.title-*`, `.fw-*`, `.lora`
   - **Spacing**: `.mb-*`, `.mt-*`, `.p-*`, `.px-*`, `.py-*`
   - **Display**: `.d-none`, `.d-block`, `.d-lg-flex`, `.d-md-block`
   - **Utilities**: `.visually-hidden`, `.rounded-*`, `.shadow-*`
   - **Color**: `.bg-*`, `.text-*`, `.border-*`

4. **Create inventory table**:
   ```markdown
   | Class | Type | Count | Usage | Component |
   |-------|------|-------|-------|-----------|
   | .container | grid | 3 | page wrapper | hero, sections |
   | .row | grid | 5 | flex row | layouts |
   | .col-12 | grid | 8 | full width | various |
   ```

---

### Task 4: Component Breakdown (45 min)

**Goal**: Identify and analyze each major visual component

**Output**: `analysis/03-COMPONENT-BREAKDOWN.md`

**Components to Analyze**:

#### 1. **Header/Navigation**
- Header wrapper structure
- Navigation items
- Language dropdown
- Login button
- Social links

#### 2. **Hero Section**
- Layout: image + card overlay
- Image sizing and positioning
- Card content: title, date, description
- Tags/chips
- "Read more" link

#### 3. **Governance Cards** (Most Complex)
- Grid layout (3 columns on desktop)
- Card structure: image + text
- Image positioning (bottom/top/side)
- Text hierarchy
- Call-to-action

#### 4. **Topics Block**
- Featured topic (large)
- Secondary topics (medium grid)
- Thematic sites (collection)
- Layout patterns

#### 5. **Calendar Carousel**
- Slide structure
- Navigation buttons
- Content per slide
- Responsive behavior

#### 6. **Useful Links Section**
- Search bar
- Quick link grid
- Link styling

#### 7. **Contact/Feedback Blocks**
- Icon + text layout
- CTA buttons
- Responsive stacking

**Document Format for Each Component**:
```markdown
## [Component Name]

### Visual Structure
[ASCII diagram or description]

### Elements
- Main container: [classes]
- Child elements: [list]

### Key Classes
- Layout: [list]
- Typography: [list]
- Spacing: [list]

### Responsive Changes
- Desktop (1920px): [description]
- Tablet (768px): [description]
- Mobile (375px): [description]

### Bootstrap Classes Used
- Grid: [list]
- Components: [list]
- Utilities: [list]
```

---

### Task 5: Responsive Patterns (45 min)

**Goal**: Document responsive design breakpoints and reflow behavior

**Output**: `analysis/04-RESPONSIVE-PATTERNS.md`

**Breakpoint Analysis**:

1. **Identify Breakpoints**
   ```
   Mobile First:
   - <768px: Mobile
   - 768px-1024px: Tablet
   - >1024px: Desktop
   ```

2. **Grid Reflow Patterns**
   
   Example for Hero Section:
   ```
   Desktop (1920px):
   [Image: 50% | Card: 50%]
   
   Tablet (768px):
   [Image: 50% | Card: 50%]  (no change? verify)
   
   Mobile (375px):
   [Image: 100%]
   [Card: 100%]
   ```

3. **Element Visibility Changes**
   - What hides at mobile/tablet?
   - What shows only at desktop?
   - Example: `.d-none`, `.d-lg-block`

4. **Typography Scaling**
   ```
   Desktop: h1 = 48px
   Tablet:  h1 = 36px
   Mobile:  h1 = 28px
   ```

5. **Spacing Adjustments**
   ```
   Component margins:
   Desktop: 40px
   Tablet:  30px
   Mobile:  20px
   ```

6. **Image Sizing**
   ```
   Hero image:
   Desktop: 800×600
   Tablet:  100% width
   Mobile:  100% width
   ```

**Document Format**:
```markdown
## Breakpoint Strategy

### Desktop (>1024px)
- Container width: [value]
- Typography scale: [base]
- Grid columns: [count]

### Tablet (768-1024px)
- Container width: [value]
- Typography scale: [adjusted]
- Grid columns: [reduced]
- Changes: [list]

### Mobile (<768px)
- Container width: [100%]
- Typography scale: [smaller]
- Grid columns: [1]
- Stacking: [all elements]

## Component-by-Component Responsive

### Hero Section
[Detailed responsive behavior]

[... repeat for each component ...]
```

---

## 📊 Current State (Before Phase A Completion)

```
Reference homepage (italia.github.io)
├── Total elements: 159
├── Bootstrap classes: ~80 unique
├── Components: 7 major
└── Responsive: 3 breakpoints

Local homepage (127.0.0.1:8000)
├── Total elements: 127
├── Bootstrap classes: partial
├── Components: partial/incomplete
└── Responsive: partial
```

---

## 📝 Analysis Document Checklist

### 01-HTML-STRUCTURE-ANALYSIS.md
- [ ] Element counts documented
- [ ] Semantic HTML reviewed
- [ ] Component nesting analyzed
- [ ] Missing elements identified
- [ ] Attribute differences noted

### 02-CSS-FRAMEWORK-AUDIT.md
- [ ] All grid classes catalogued
- [ ] Component classes inventoried
- [ ] Typography classes documented
- [ ] Spacing utilities mapped
- [ ] Display/utility classes listed
- [ ] Usage frequency noted

### 03-COMPONENT-BREAKDOWN.md
- [ ] 7 components analyzed
- [ ] Visual structure documented
- [ ] Bootstrap classes per component listed
- [ ] Responsive behavior per component noted
- [ ] Element counts per component

### 04-RESPONSIVE-PATTERNS.md
- [ ] 3 breakpoints defined
- [ ] Grid reflow patterns documented
- [ ] Visibility changes noted
- [ ] Typography scaling documented
- [ ] Spacing adjustments noted
- [ ] Image sizing patterns noted

---

## 🔗 Analysis Outputs Feed

```
Phase A Outputs
├── 01-HTML-STRUCTURE-ANALYSIS.md
│   ↓ (feeds to)
├── 02-CSS-FRAMEWORK-AUDIT.md
│   ↓ (feeds to)
├── Mappings/01-BOOTSTRAP-CLASSES-INVENTORY.md
│   ↓ (feeds to)
├── Mappings/02-TAILWIND-EQUIVALENTS.md
│   ↓ (feeds to)
├── Mappings/04-COLOR-TOKEN-MAPPING.md
└── Phases/02-PHASE-B-CONFIG.md

├── 03-COMPONENT-BREAKDOWN.md
│   ↓ (feeds to)
├── Visual Comparison/04-STYLING-ISSUES-SUMMARY.md
│   ↓ (feeds to)
└── Phases/03-PHASE-C-COMPONENTS.md

├── 04-RESPONSIVE-PATTERNS.md
│   ↓ (feeds to)
├── Visual Comparison/01,02,03-VISUAL-DIFF-*.md
│   ↓ (feeds to)
└── Phases/05-PHASE-E-POLISH.md
```

---

## 🎓 Key Insights to Discover

During analysis, document:

1. **Why 32 elements different?**
   - Missing navigation logic?
   - Simplified structure?
   - Different markup approach?

2. **Bootstrap Italia version**
   - Bootstrap 5 features used?
   - Custom AGID components?
   - Icon system (SVG sprites)?

3. **Responsive strategy**
   - Mobile-first or desktop-first?
   - Breakpoint values?
   - Reflow logic?

4. **Custom styling**
   - AGID color tokens?
   - Custom typography scale?
   - Spacing system?

5. **Data binding**
   - How does JSON data map to HTML?
   - Template patterns in local version?
   - Dynamic vs. static content?

---

## 📝 Lessons Learned

Document discoveries as you go:

```markdown
### Discovery: [Title]
- **What**: [What you found]
- **Why**: [Why it matters]
- **Impact**: [Affects which components/phases]
- **Action**: [What to do about it]
```

Examples:
- Discovery: Grid column count changes at tablet
- Discovery: Custom `.chip-simple` class not in Bootstrap
- Discovery: SVG sprite paths differ between reference and local

---

## ✅ Definition of Done

Phase A is complete when:

1. ✅ All 6 screenshots captured and stored
2. ✅ All 4 analysis documents created and linked
3. ✅ 90%+ confidence in component inventory
4. ✅ Bootstrap class mapping ready for Phase B
5. ✅ Responsive patterns documented
6. ✅ Visual issue list consolidated
7. ✅ Index links all documents
8. ✅ Team reviewed and approved

---

## 🚀 Next Phase

When Phase A complete → Move to [**Phase B: Tailwind Configuration**](./02-PHASE-B-CONFIG.md)

---

## 🔗 Related Documents

- [Master Index](../00-HOMEPAGE-REPLICATION-INDEX.md)
- [Analysis Folder](../analysis/README.md)
- [Screenshots Folder](../screenshots/README.md)
- [Visual Comparison Folder](../visual-comparison/README.md)
- [Mappings Folder](../mappings/README.md)

---

**Navigation**: [← Back to Phases](./README.md) | [Next: Phase B →](./02-PHASE-B-CONFIG.md)

