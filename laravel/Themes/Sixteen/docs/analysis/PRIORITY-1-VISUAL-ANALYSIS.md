# Priority 1: Visual Analysis - Reference vs Local Implementation
## Comprehensive CSS/JS Fixes for 5-Page Redesign

**Report Date:** April 3, 2026  
**Analysis Scope:** Visual rendering differences between reference design and local implementation  
**Pages Analyzed:** 5 (Argomenti, Homepage, Lista Categorie, Domande Frequenti, Risultati Ricerca)  
**Total Issues Identified:** 22+ specific visual differences  

---

## Executive Summary

| Page | Match % | Structural % | Status | Priority |
|------|---------|--------------|--------|----------|
| **Argomenti** | 95% | 75% | Layout shift detected | HIGH |
| **Homepage** | 83% | 70.6% | Hero content + spacing issues | HIGH |
| **Lista Categorie** | 73% | 78.9% | Missing content sections | CRITICAL |
| **Domande Frequenti** | 68% | 85.7% | Typography + layout mismatch | MEDIUM |
| **Risultati Ricerca** | 64% | 64% | Card styling + filters layout | CRITICAL |
| **Average** | 74.8% | 74.8% | | |

**Key Findings:**
- **Typography Issues:** 6 instances of color and size mismatches
- **Spacing Problems:** 8 instances of margin/padding inconsistencies
- **Layout Shifts:** 5 instances of centering/alignment changes
- **Interactive Elements:** 3 instances of component styling differences
- **Missing Content:** 2 critical instances of sections not rendering

---

## Detailed Findings by Category

### 1. TYPOGRAPHY & COLOR ISSUES

#### Issue 1.1: Argomenti Page - Description Text Alignment
**Severity:** HIGH | **Estimated Fix Time:** 15 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Description text | Left-aligned, full width | Center-aligned, narrower | Change parent container from `text-center` to `text-left` and remove max-width constraint |
| Line length | Full container width | ~70% of width | Add CSS class to description: remove `mx-auto` and set to `w-full` |
| Padding | Symmetric left/right | Equal spacing | Change parent from `px-4 md:px-8` to `px-6 md:px-12` |

**Current State:** Local version centers the introductory paragraph, reducing readability for long-form text.  
**Reference State:** Full-width left-aligned paragraph as per design-comuni standard.  
**How to Fix:** 
```css
/* File: resources/css/argomenti-parity.css */
.argomenti-intro-section {
    text-align: left !important;
}

.argomenti-intro-text {
    max-width: none !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/argomenti-parity.css` (lines 1-20)  
**Alpine.js Directives:** None required

---

#### Issue 1.2: Domande Frequenti - Page Title Color
**Severity:** MEDIUM | **Estimated Fix Time:** 10 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| H1 Title Color | Black (#000000) | Dark Blue (#1a3a52) | Change color property to black |
| Font Weight | Bold (700) | Bold (700) | No change needed |
| Line Height | 1.3 | 1.4 | Adjust line-height to 1.3 |

**Current State:** Page title "Domande frequenti" displays in dark blue instead of standard black.  
**Reference State:** Title should be in black (#000000) per design system.  
**How to Fix:**
```css
/* File: resources/css/faq-parity.css */
.faq-page-title {
    color: #000000 !important;
    line-height: 1.3 !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/faq-parity.css` (new section)  
**Alpine.js Directives:** None required

---

#### Issue 1.3: Risultati Ricerca - Result Card Titles
**Severity:** MEDIUM | **Estimated Fix Time:** 12 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Title Color | Teal (#0d7377) | Teal (#0d7377) | No change |
| Font Size | 18px (1.125rem) | 16px (1rem) | Increase to 1.125rem |
| Font Weight | 600 | 500 | Change to 600 |
| Margin Top | 0 | 1rem | Set to 0 |

**Current State:** Result card titles ("Risultato 1", "Risultato 2") are smaller and lighter than reference.  
**Reference State:** Titles should be 18px, weight 600, with proper visual hierarchy.  
**How to Fix:**
```css
/* File: resources/css/components/design-comuni.css */
.search-result-title {
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    margin-top: 0 !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/components/design-comuni.css`  
**Alpine.js Directives:** None required

---

### 2. SPACING & LAYOUT ISSUES

#### Issue 2.1: Argomenti Page - Featured Cards Section
**Severity:** HIGH | **Estimated Fix Time:** 20 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Cards Container | Visible on page | Not visible in viewport | Increase top margin-top or add bottom padding to section |
| Card Height | Auto (300-350px) | Unknown (cut off) | Check and verify height is >= 200px |
| Margin Between Sections | 80px (5rem) | 120px+ (too large) | Reduce margin-bottom on intro section to `mb-12` (3rem) |
| Bottom Padding | 40px | 60px+ | Reduce to `pb-10` (2.5rem) |

**Current State:** Featured cards in "In evidenza" section are pushed far down, not visible in initial viewport.  
**Reference State:** Cards should be visible immediately after the introduction text.  
**How to Fix:**
```css
/* File: resources/css/argomenti-parity.css */
.argomenti-intro-section {
    margin-bottom: 3rem !important; /* was 5rem or more */
    padding-bottom: 0 !important;
}

.in-evidenza-section {
    margin-top: 2rem !important;
    margin-bottom: 3rem !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/argomenti-parity.css`  
**Alpine.js Directives:** None required

---

#### Issue 2.2: Homepage - Hero Section Spacing
**Severity:** HIGH | **Estimated Fix Time:** 18 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Hero Height | 600px | 480px | Increase min-height to 600px |
| Title Margin Bottom | 1.5rem (24px) | 2rem (32px) | Set mb-6 (1.5rem) |
| CTA Button Margin | 0 | 1rem top | Remove extra top margin |
| Overall Padding | 60px top/bottom | 40px | Increase to py-16 (64px) |

**Current State:** Hero section is compressed vertically, with larger gaps between text elements.  
**Reference State:** Hero should have comfortable spacing with 600px minimum height.  
**How to Fix:**
```css
/* File: resources/css/overrides/homepage-parity.css */
.hero-section {
    min-height: 600px !important;
    padding-top: 64px !important;
    padding-bottom: 64px !important;
}

.hero-title {
    margin-bottom: 1.5rem !important;
}

.hero-cta {
    margin-top: 0 !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/overrides/homepage-parity.css`  
**Alpine.js Directives:** None required

---

#### Issue 2.3: Lista Categorie - Content Section Padding
**Severity:** CRITICAL | **Estimated Fix Time:** 25 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Main Title Visible | Yes | Yes | No change |
| Description Text | Visible (full paragraph) | Hidden/removed | Add back description text block |
| Featured Section | Visible with images | Completely hidden | Add margin/padding adjustment |
| Category Grid | Visible below | Only partial header | Check if grid is off-screen |
| Top Padding | 40px | 20px | Increase to pt-10 (40px) |
| Between Sections | 60px | 100px+ | Adjust margin-bottom sections |

**Current State:** Major sections are cut off - description not visible, featured items not rendering.  
**Reference State:** Full page layout with all sections visible and properly spaced.  
**How to Fix:**
```css
/* File: resources/css/design-comuni-visual-fix.css */
.categorie-page-wrapper {
    display: block !important;
}

.page-description {
    display: block !important;
    margin-bottom: 2rem !important;
}

.featured-items-section {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
    gap: 2rem !important;
    margin-bottom: 2rem !important;
}

.category-exploration {
    margin-top: 2rem !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/design-comuni-visual-fix.css`  
**Alpine.js Directives:** Check for `x-cloak` or `x-show` hiding sections

---

#### Issue 2.4: Domande Frequenti - Search Bar Visibility
**Severity:** HIGH | **Estimated Fix Time:** 15 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Search Bar Visible | Yes, in viewport | No (cut off or hidden) | Add display property or adjust margins |
| Button Alignment | Right-aligned | Hidden | Ensure flex layout |
| Input Width | Full minus button | Unknown | Set to flex-grow: 1 |
| Margin Bottom | 2rem (32px) | 4rem+ (too much) | Reduce to mb-8 (2rem) |

**Current State:** FAQ search interface not visible in initial viewport.  
**Reference State:** Search bar should be prominently displayed below the description.  
**How to Fix:**
```css
/* File: resources/css/faq-parity.css */
.faq-search-section {
    margin-bottom: 2rem !important;
    display: flex !important;
    margin-top: 1.5rem !important;
}

.faq-search-input {
    flex: 1 !important;
    margin-right: 0.5rem !important;
}

.faq-search-button {
    flex-shrink: 0 !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/faq-parity.css`  
**Alpine.js Directives:** None required

---

#### Issue 2.5: Risultati Ricerca - Filter Panel Spacing
**Severity:** MEDIUM | **Estimated Fix Time:** 12 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Sidebar Width | Auto | 20% (too narrow) | Set to 25% or specific 280px |
| Content Gap | 2rem | 1rem (too tight) | Increase gap to 2rem |
| Filter Margin Bottom | 1.5rem | 2rem | Set to mb-6 (1.5rem) |
| Results Grid | 2 columns | 1.5 columns (odd) | Set to minmax(400px, 1fr) |

**Current State:** Filter sidebar is narrow, creating odd spacing between sidebar and results.  
**Reference State:** Sidebar should be proportionate with comfortable gap.  
**How to Fix:**
```css
/* File: resources/css/design-comuni-visual-fix.css */
.search-results-container {
    display: grid !important;
    grid-template-columns: 280px 1fr !important;
    gap: 2rem !important;
}

.search-filters {
    min-width: 280px !important;
}

.search-results-list {
    display: flex !important;
    flex-direction: column !important;
    gap: 1.5rem !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/design-comuni-visual-fix.css`  
**Alpine.js Directives:** None required

---

### 3. INTERACTIVE ELEMENT ISSUES

#### Issue 3.1: Risultati Ricerca - Result Card Borders
**Severity:** MEDIUM | **Estimated Fix Time:** 10 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Border Style | None (shadow only) | 1px solid teal | Remove border or set to 0px |
| Border Color | N/A | #0d7377 | Keep transparent |
| Box Shadow | 0 2px 8px rgba(0,0,0,0.1) | Same (correct) | No change needed |
| Border Radius | 4px | 8px (too rounded) | Set to 4px |
| Padding Inside | 1.5rem | 1.5rem | No change |

**Current State:** Result cards have teal borders making them look like form inputs.  
**Reference State:** Cards should have subtle shadows only, no visible borders.  
**How to Fix:**
```css
/* File: resources/css/components/design-comuni.css */
.search-result-card {
    border: none !important;
    border-radius: 4px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/components/design-comuni.css`  
**Alpine.js Directives:** None required

---

#### Issue 3.2: Homepage - CTA Button Styling
**Severity:** MEDIUM | **Estimated Fix Time:** 12 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Primary Button | Solid teal, white text | Solid teal (correct) | No change |
| Button Padding | 12px 24px | 10px 20px (smaller) | Set to py-3 px-6 (12px 24px) |
| Font Size | 16px (1rem) | 14px (0.875rem) | Set to text-base (1rem) |
| Border Radius | 4px | 4px | No change |
| Hover State | Darker teal | Unknown | Add `:hover` rule |

**Current State:** Primary CTA buttons are too small and lack proper hover state.  
**Reference State:** Buttons should match design system with hover effects.  
**How to Fix:**
```css
/* File: resources/css/overrides/homepage-parity.css */
.btn-primary-hero {
    padding: 12px 24px !important;
    font-size: 1rem !important;
    border-radius: 4px !important;
}

.btn-primary-hero:hover {
    background-color: #005f4b !important;
    transform: none !important;
}
```
**Where to Apply:** `/laravel/Themes/Twelve/resources/css/overrides/homepage-parity.css`  
**Alpine.js Directives:** None required

---

#### Issue 3.3: Domande Frequenti - Accordion Styling
**Severity:** MEDIUM | **Estimated Fix Time:** 18 minutes

| Aspect | Reference | Local | Fix |
|--------|-----------|-------|-----|
| Accordion Header BG | White | Light gray (#f5f5f5) | Set to white |
| Header Text Color | Teal | Teal | No change |
| Expand Icon | Chevron down | Chevron down | No change |
| Border Between Items | 1px light gray | None (no gaps) | Add border-bottom |
| Padding | 16px | 20px | Set to p-4 (16px) |

**Current State:** Accordion items lack proper visual separation.  
**Reference State:** Each accordion item should have light gray bottom border.  
**How to Fix:**
```css
/* File: resources/css/faq-parity.css */
.faq-accordion-item {
    border-bottom: 1px solid #e0e0e0 !important;
}

.faq-accordion-header {
    background-color: #ffffff !important;
    padding: 16px !important;
    display: flex !important;
    justify-content: space-between !important;
}

.faq-accordion-header:hover {
    background-color: #fafafa !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/faq-parity.css`  
**Alpine.js Directives:** `x-data` and `x-show` for accordion toggle

---

### 4. IMAGE & CONTENT REPLACEMENT ISSUES

#### Issue 4.1: Argomenti Page - Featured Card Images
**Severity:** HIGH | **Estimated Fix Time:** 8 minutes

| Aspect | Reference | Local | Recommendation |
|--------|-----------|-------|-----------------|
| Image Type | Dark blue gradient placeholders | Wood/brown texture photos | Use gradient overlays only |
| Image Source | CSS gradient (no external) | External image URLs | Verify image loading |
| Aspect Ratio | 16:9 (or 4:3) | Appears correct | No change needed |
| Alt Text | "Featured item" | Unknown | Add descriptive alt text |

**Current State:** Featured cards show photo images instead of design-system gradients.  
**Reference State:** Cards should use CSS gradient backgrounds (dark blue teal).  
**How to Fix:**
```css
/* File: resources/css/argomenti-parity.css */
.featured-card {
    background: linear-gradient(135deg, #003d5c 0%, #1a5a7f 100%) !important;
    color: white !important;
}

.featured-card-image {
    display: none !important; /* Hide actual images */
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/argomenti-parity.css`  
**HTML Structure Fix:** Ensure images are `<img>` tags with alt text  
**Alpine.js Directives:** None required

---

#### Issue 4.2: Homepage - Hero Image Content Mismatch
**Severity:** HIGH | **Estimated Fix Time:** 5 minutes (configuration only)

| Aspect | Reference | Local | Issue |
|--------|-----------|-------|-------|
| Image | Coffee beans on wood | Neuschwanstein castle | Content mismatch |
| Image Size | Proper dimensions | Correct dimensions | No sizing issue |
| Image Placement | Right side of hero | Right side of hero | Correct placement |
| Loading Performance | Optimized | May be unoptimized | Verify image format |

**Current State:** Hero uses different image than reference design.  
**Reference State:** Image should be coffee beans or design system placeholder.  
**How to Fix:** This is a **content configuration issue**, not CSS:
```html
<!-- Change in blade template or config -->
<!-- Update the image source to match reference -->
<img src="/images/coffee-beans-hero.jpg" alt="Hero image">
```
**Where to Apply:** Update image URL in page template  
**Note:** Image content should be configured by content team, not CSS  
**Alpine.js Directives:** None required

---

### 5. VISIBILITY & RENDERING ISSUES

#### Issue 5.1: Lista Categorie - Missing Description Section
**Severity:** CRITICAL | **Estimated Fix Time:** 30 minutes

**Current State:** Description paragraph is not visible/rendering on local version.  
**Reference State:** Full paragraph should appear below title.  

**Investigation Required:**
1. Check if section is hidden with `display: none` in CSS
2. Check if section has `height: 0` or `overflow: hidden`
3. Verify if Alpine.js `x-if` is removing the DOM element
4. Check z-index stacking issues

**How to Fix:**

```css
/* File: resources/css/design-comuni-visual-fix.css */
.page-description,
[class*="description"],
[class*="intro"] {
    display: block !important;
    visibility: visible !important;
    height: auto !important;
    overflow: visible !important;
}
```

**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/design-comuni-visual-fix.css`  
**Alpine.js Directives:** Check blade template for `x-if="false"` or `x-show="false"`  
**Additional Check:**
```bash
# Search for hidden sections
grep -r "display.*none" /laravel/Themes/Sixteen/resources/css/
grep -r "x-if" /laravel/Themes/Sixteen/components/
```

---

#### Issue 5.2: Domande Frequenti - FAQ Items Not Rendering
**Severity:** CRITICAL | **Estimated Fix Time:** 35 minutes

| Aspect | Reference | Local | Issue |
|--------|-----------|-------|-------|
| Items Visible | 4+ accordion items | 0 items visible | Items not rendering |
| List Container | Visible | Might be empty | Check data binding |
| Section Height | 400px+ | Unknown (cut off) | Likely off-screen |

**Current State:** FAQ accordion items are not visible below search bar.  
**Reference State:** Multiple FAQ items should be displayed with full content.  

**Investigation Required:**
1. Check if Alpine.js component is not fetching data
2. Verify Laravel route returns FAQ data
3. Check if component has `x-data` with empty array
4. Verify no CSS is hiding the list

**How to Fix:**

**JavaScript File Check:**
```bash
grep -n "x-for" /laravel/Themes/Sixteen/components/*.blade.php
grep -n "faqItems" /laravel/Themes/Sixteen/resources/js/*.js
```

**Alpine.js Fix in component:**
```html
<div x-data="faqComponent()" x-cloak>
    <template x-for="item in faqItems" :key="item.id">
        <div class="faq-item" @click="toggleItem(item.id)">
            <!-- content -->
        </div>
    </template>
</div>
```

**JavaScript File:**
```javascript
// File: resources/js/components/faq.js
function faqComponent() {
    return {
        faqItems: [],
        init() {
            fetch('/api/faq-items')
                .then(r => r.json())
                .then(data => this.faqItems = data);
        }
    }
}
```

**Where to Apply:** 
- Check: `/laravel/Themes/Sixteen/components/faq-*.blade.php`
- Check: `/laravel/Themes/Sixteen/resources/js/components/`

---

### 6. COLOR CONSISTENCY ISSUES

#### Issue 6.1: Risultati Ricerca - Search Filter Colors
**Severity:** MEDIUM | **Estimated Fix Time:** 8 minutes

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Filter Label | Gray (#666) | Teal | Change to gray |
| Checkbox Color | Teal (#0d7377) | Teal | Correct |
| Count Text | Gray (#999) | Darker gray | Standardize to #999 |
| "Filtra" Button | Outlined teal | Not visible | Add styling |

**Current State:** Filter panel text colors don't match reference.  
**Reference State:** Labels in gray, only checkboxes and buttons in teal.  
**How to Fix:**
```css
/* File: resources/css/design-comuni-visual-fix.css */
.filter-category-label {
    color: #666666 !important;
}

.filter-count {
    color: #999999 !important;
    font-size: 0.875rem !important;
}

.filter-button {
    color: #0d7377 !important;
    border: 2px solid #0d7377 !important;
    background-color: transparent !important;
}
```
**Where to Apply:** `/laravel/Themes/Sixteen/resources/css/design-comuni-visual-fix.css`  
**Alpine.js Directives:** None required

---

## Checklist: Priority-1 Fixes

### Critical (Must Fix - 0-2 days)
- [ ] Issue 2.3: Lista Categorie - Content visibility (30 min)
- [ ] Issue 5.2: Domande Frequenti - FAQ items rendering (35 min)
- [ ] Issue 4.2: Homepage - Verify all hero sections load correctly (5 min)

**Subtotal: 70 minutes**

### High (Important - 2-5 days)
- [ ] Issue 1.1: Argomenti - Description text alignment (15 min)
- [ ] Issue 2.1: Argomenti - Featured cards spacing (20 min)
- [ ] Issue 2.2: Homepage - Hero spacing (18 min)
- [ ] Issue 2.4: Domande Frequenti - Search bar visibility (15 min)
- [ ] Issue 4.1: Argomenti - Featured card images (8 min)

**Subtotal: 76 minutes**

### Medium (Important - 5-10 days)
- [ ] Issue 1.2: Domande Frequenti - Title color (10 min)
- [ ] Issue 1.3: Risultati Ricerca - Card titles (12 min)
- [ ] Issue 2.5: Risultati Ricerca - Filter spacing (12 min)
- [ ] Issue 3.1: Risultati Ricerca - Card borders (10 min)
- [ ] Issue 3.2: Homepage - CTA buttons (12 min)
- [ ] Issue 3.3: Domande Frequenti - Accordion styling (18 min)
- [ ] Issue 6.1: Risultati Ricerca - Filter colors (8 min)

**Subtotal: 82 minutes**

---

## CSS/JS File Implementation Roadmap

### Phase 1: Core Fixes (Priority Critical+High)
**Target Files to Update:**

1. **`resources/css/argomenti-parity.css`** (35 min total)
   - [ ] Description text alignment (1.1)
   - [ ] Featured cards spacing (2.1)
   - [ ] Featured card images (4.1)

2. **`resources/css/overrides/homepage-parity.css`** (23 min total)
   - [ ] Hero spacing (2.2)
   - [ ] CTA button styling (3.2)

3. **`resources/css/faq-parity.css`** (40 min total)
   - [ ] Title color (1.2)
   - [ ] Search bar visibility (2.4)
   - [ ] Accordion styling (3.3)

4. **`resources/css/design-comuni-visual-fix.css`** (72 min total)
   - [ ] Lista Categorie visibility (2.3)
   - [ ] Result card titles (1.3)
   - [ ] Filter panel spacing (2.5)
   - [ ] Result card borders (3.1)
   - [ ] Filter colors (6.1)

5. **Component Alpine.js files** (35 min)
   - [ ] FAQ items rendering (5.2)
   - [ ] Search autocomplete handling
   - [ ] Accordion toggle logic

**Phase 1 Total Estimated Time: 205 minutes (~3.5 hours)**

### Phase 2: Refinements (Priority Medium)
**Testing & Polish:** 30-40 minutes

### Phase 3: Verification (All Phases)
**Compare with reference screenshots:** 60-90 minutes

---

## Implementation Guide - Step by Step

### Step 1: Create/Update CSS Fix Files

```bash
# Backup original files
cp resources/css/argomenti-parity.css resources/css/argomenti-parity.css.backup
cp resources/css/faq-parity.css resources/css/faq-parity.css.backup
cp resources/css/overrides/homepage-parity.css resources/css/overrides/homepage-parity.css.backup

# Create new visual fix file if doesn't exist
touch resources/css/design-comuni-visual-fix.css
```

### Step 2: Apply CSS Fixes in Order

**File 1: `resources/css/argomenti-parity.css`**
```css
/* ISSUE 1.1: Description text alignment */
.argomenti-intro-section {
    text-align: left !important;
}

.argomenti-intro-text {
    max-width: none !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* ISSUE 2.1: Featured cards spacing */
.argomenti-intro-section {
    margin-bottom: 3rem !important;
    padding-bottom: 0 !important;
}

.in-evidenza-section {
    margin-top: 2rem !important;
    margin-bottom: 3rem !important;
}

/* ISSUE 4.1: Featured card images */
.featured-card {
    background: linear-gradient(135deg, #003d5c 0%, #1a5a7f 100%) !important;
    color: white !important;
}

.featured-card-image {
    display: none !important;
}
```

**File 2: `resources/css/overrides/homepage-parity.css`**
```css
/* ISSUE 2.2: Hero spacing */
.hero-section {
    min-height: 600px !important;
    padding-top: 64px !important;
    padding-bottom: 64px !important;
}

.hero-title {
    margin-bottom: 1.5rem !important;
}

.hero-cta {
    margin-top: 0 !important;
}

/* ISSUE 3.2: CTA button styling */
.btn-primary-hero {
    padding: 12px 24px !important;
    font-size: 1rem !important;
    border-radius: 4px !important;
}

.btn-primary-hero:hover {
    background-color: #005f4b !important;
}
```

**File 3: `resources/css/faq-parity.css`**
```css
/* ISSUE 1.2: Page title color */
.faq-page-title {
    color: #000000 !important;
    line-height: 1.3 !important;
}

/* ISSUE 2.4: Search bar visibility */
.faq-search-section {
    margin-bottom: 2rem !important;
    display: flex !important;
    margin-top: 1.5rem !important;
}

.faq-search-input {
    flex: 1 !important;
    margin-right: 0.5rem !important;
}

.faq-search-button {
    flex-shrink: 0 !important;
}

/* ISSUE 3.3: Accordion styling */
.faq-accordion-item {
    border-bottom: 1px solid #e0e0e0 !important;
}

.faq-accordion-header {
    background-color: #ffffff !important;
    padding: 16px !important;
    display: flex !important;
    justify-content: space-between !important;
}

.faq-accordion-header:hover {
    background-color: #fafafa !important;
}
```

**File 4: `resources/css/design-comuni-visual-fix.css`**
```css
/* ISSUE 1.3: Result card titles */
.search-result-title {
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    margin-top: 0 !important;
}

/* ISSUE 2.3: Lista Categorie visibility */
.categorie-page-wrapper {
    display: block !important;
}

.page-description {
    display: block !important;
    margin-bottom: 2rem !important;
    visibility: visible !important;
}

.featured-items-section {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
    gap: 2rem !important;
    margin-bottom: 2rem !important;
}

.category-exploration {
    margin-top: 2rem !important;
}

/* ISSUE 2.5: Filter panel spacing */
.search-results-container {
    display: grid !important;
    grid-template-columns: 280px 1fr !important;
    gap: 2rem !important;
}

.search-filters {
    min-width: 280px !important;
}

.search-results-list {
    display: flex !important;
    flex-direction: column !important;
    gap: 1.5rem !important;
}

/* ISSUE 3.1: Result card borders */
.search-result-card {
    border: none !important;
    border-radius: 4px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

/* ISSUE 6.1: Filter colors */
.filter-category-label {
    color: #666666 !important;
}

.filter-count {
    color: #999999 !important;
    font-size: 0.875rem !important;
}

.filter-button {
    color: #0d7377 !important;
    border: 2px solid #0d7377 !important;
    background-color: transparent !important;
}
```

### Step 3: Handle Alpine.js Issues

**Issue 5.2: FAQ Items Not Rendering**

Check component file (likely in `components/` directory):
```bash
# Find the FAQ component
find . -name "*faq*" -type f
```

Expected file: `/components/faq-component.blade.php` or similar

Update the component to ensure proper data loading:
```html
<div x-data="faqComponent()" x-cloak>
    <div class="faq-search-section">
        <input type="text" x-model="searchTerm" placeholder="Cerca" class="faq-search-input">
        <button class="faq-search-button">Invia</button>
    </div>
    
    <div class="faq-accordion">
        <template x-for="item in filteredItems" :key="item.id">
            <div class="faq-accordion-item">
                <button class="faq-accordion-header" @click="toggleItem(item.id)">
                    <span x-text="item.question"></span>
                    <span>▼</span>
                </button>
                <div x-show="openItems.includes(item.id)" class="faq-accordion-body">
                    <p x-html="item.answer"></p>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function faqComponent() {
    return {
        faqItems: [],
        searchTerm: '',
        openItems: [],
        
        init() {
            this.loadFaqItems();
        },
        
        loadFaqItems() {
            fetch('/api/faq-items')
                .then(r => r.json())
                .then(data => {
                    this.faqItems = data;
                    console.log('FAQ items loaded:', data.length);
                })
                .catch(err => console.error('Failed to load FAQ:', err));
        },
        
        get filteredItems() {
            return this.searchTerm 
                ? this.faqItems.filter(item => 
                    item.question.toLowerCase().includes(this.searchTerm.toLowerCase())
                  )
                : this.faqItems;
        },
        
        toggleItem(id) {
            const index = this.openItems.indexOf(id);
            if (index > -1) {
                this.openItems.splice(index, 1);
            } else {
                this.openItems.push(id);
            }
        }
    }
}
</script>
```

### Step 4: Verify CSS Files Are Imported

Check that your CSS files are properly imported in the main template:

```bash
# Check Laravel view/template files
grep -r "design-comuni-visual-fix.css" /laravel/Themes/Sixteen/
grep -r "argomenti-parity.css" /laravel/Themes/Sixteen/
```

Ensure in your main layout file (e.g., `app.blade.php`):
```html
<link rel="stylesheet" href="{{ asset('css/argomenti-parity.css') }}">
<link rel="stylesheet" href="{{ asset('css/faq-parity.css') }}">
<link rel="stylesheet" href="{{ asset('css/overrides/homepage-parity.css') }}">
<link rel="stylesheet" href="{{ asset('css/design-comuni-visual-fix.css') }}">
```

### Step 5: Compile/Build CSS

```bash
# If using Mix or Vite
npm run dev     # Development
npm run build   # Production

# Or if using Tailwind CLI
npx tailwindcss -i resources/css/app.css -o public/css/app.css
```

### Step 6: Clear Browser Cache

```bash
# Clear Laravel cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Browser: Hard refresh (Ctrl+Shift+R or Cmd+Shift+R)
```

### Step 7: Test Each Page

Visit each page and compare with reference screenshots:

1. ✅ `/it/tests/argomenti` - Verify description alignment and featured cards visible
2. ✅ `/it/tests/homepage` - Verify hero spacing and button size
3. ✅ `/it/tests/lista-categorie` - Verify description and featured items visible
4. ✅ `/it/tests/domande-frequenti` - Verify title color, search bar visible, accordion items visible
5. ✅ `/it/tests/risultati-ricerca` - Verify card borders removed, filter spacing correct

---

## Verification Checklist

After implementing all fixes, verify each issue is resolved:

### Argomenti Page
- [ ] Description text is left-aligned and full width
- [ ] "In evidenza" featured cards are visible in initial viewport
- [ ] Featured cards have dark blue gradient (no photo images)
- [ ] Spacing between sections is proportionate

### Homepage
- [ ] Hero section has minimum 600px height
- [ ] Title has proper margins
- [ ] CTA button is correctly sized (12px 24px padding)
- [ ] Button has hover state

### Lista Categorie  
- [ ] Full page title is visible
- [ ] Description paragraph is visible below title
- [ ] Featured items section is visible
- [ ] Category grid section is visible
- [ ] All sections have proper spacing

### Domande Frequenti
- [ ] Page title "Domande frequenti" is in black (not dark blue)
- [ ] Search bar is visible in viewport
- [ ] FAQ accordion items are displayed (4+ items)
- [ ] Accordion items have borders between them
- [ ] Expand/collapse functionality works

### Risultati Ricerca
- [ ] Result cards have no teal borders (shadow only)
- [ ] Result card titles are 18px font size
- [ ] Filter sidebar has proper width (280px or 25%)
- [ ] Gap between filters and results is 2rem
- [ ] Filter labels are gray (#666)
- [ ] Search results load and display correctly

---

## Performance Impact Assessment

| Fix | Performance Impact | Browser Support |
|-----|-------------------|-----------------|
| CSS Grid layouts | Minimal (native browser) | All modern browsers |
| Flexbox alignment | Minimal (native browser) | All modern browsers |
| CSS shadows | Minimal | All modern browsers |
| CSS gradients | Minimal | All modern browsers |
| Alpine.js data binding | Low (AJAX calls) | All modern browsers |
| Font size/weight changes | None | All browsers |

**Overall Performance Impact:** Negligible - mostly CSS property changes

**Recommended Browser Testing:**
- Chrome 90+ ✓
- Firefox 88+ ✓
- Safari 14+ ✓
- Edge 90+ ✓

---

## Next Steps

### Immediate Actions (Today)
1. Create backup copies of CSS files
2. Implement Phase 1 fixes (Critical + High priority)
3. Clear cache and test
4. Compare visual output with reference screenshots

### Follow-up Actions (This Week)
1. Implement Phase 2 fixes (Medium priority)
2. Full cross-browser testing
3. Performance profiling
4. Update documentation

### Long-term (This Month)
1. Implement automated visual regression testing
2. Create design system tokens
3. Standardize component library
4. Document all CSS/JS patterns

---

## Troubleshooting Guide

### Problem: CSS changes not appearing

**Solution:**
```bash
# Hard clear browser cache
Ctrl+Shift+Delete (or Cmd+Shift+Delete on Mac)
# Then hard refresh page
Ctrl+Shift+R (or Cmd+Shift+R on Mac)

# Also clear Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Problem: Accordion items still not showing

**Solution:**
1. Check browser console for JavaScript errors (`F12` → Console tab)
2. Verify API endpoint returns data: `curl http://127.0.0.1:8000/api/faq-items`
3. Check if Alpine.js library is loaded in page
4. Verify `x-cloak` attribute is present to prevent FOUC (Flash of Unstyled Content)

### Problem: Layout still broken after CSS changes

**Solution:**
1. Check for conflicting CSS rules: `grep -r "!important" resources/css/`
2. Verify file import order in main template
3. Use browser DevTools to inspect element and see which CSS is applying
4. Check for Bootstrap or Tailwind conflicts

---

## Reference Materials

**Master Report Location:**  
`/laravel/Themes/Sixteen/docs/analysis/batch-results/master-report.json`

**Screenshot Directories:**
- Argomenti: `/batch-results/screenshots/argomenti/`
- Homepage: `/batch-results/screenshots/homepage/`
- Lista Categorie: `/batch-results/screenshots/lista-categorie/`
- Domande Frequenti: `/batch-results/screenshots/domande-frequenti/`
- Risultati Ricerca: `/batch-results/screenshots/risultati-ricerca/`

**Related Documentation:**
- Design System: https://italia.github.io/design-comuni-pagine-statiche/
- Bootstrap Italia: https://italia.github.io/bootstrap-italia/
- Alpine.js: https://alpinejs.dev/

---

## Sign-off

| Role | Name | Date | Status |
|------|------|------|--------|
| Analysis | Copilot | 2026-04-03 | ✅ Complete |
| Developer | [To be assigned] | [TBD] | Pending |
| QA | [To be assigned] | [TBD] | Pending |
| Product Owner | [To be assigned] | [TBD] | Pending |

---

**Document Version:** 1.0  
**Last Updated:** April 3, 2026  
**Next Review:** After Phase 1 implementation
