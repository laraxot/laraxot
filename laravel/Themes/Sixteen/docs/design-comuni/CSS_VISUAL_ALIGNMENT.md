# 🎨 CSS Visual Alignment Plan

**Date**: 2026-03-30  
**Status**: 🟡 In Progress  
**Priority**: 🔴 P0

---

## 📸 Screenshot Analysis

### Upstream (AGID) vs FixCity

| Section | Upstream | FixCity | Gap | Priority |
|---------|----------|---------|-----|----------|
| **Header Slim** | Blue #0066B3, white text | ⚠️ Check colors | 🔴 | P0 |
| **Header Main** | White bg, black text | ⚠️ Check | 🔴 | P0 |
| **Navbar** | Blue #003366 | ⚠️ Check | 🔴 | P0 |
| **Logo** | 80x80 circular | ⚠️ Check size | 🟡 | P1 |
| **Brand Title** | 1.5rem, bold, black | ⚠️ Check | 🟡 | P1 |
| **Brand Tagline** | 0.875rem, gray #5d7083 | ⚠️ Check | 🟡 | P1 |
| **Search Button** | Blue circle | ⚠️ Check | 🟡 | P1 |

---

## 🔧 CSS Fixes Required

### 1. Header Slim Colors

**Upstream**:
```css
.it-header-slim-wrapper {
  background-color: #0066B3 !important;  /* Deep Blue */
  color: #FFFFFF !important;
  padding-top: 8px;
  padding-bottom: 8px;
}
```

**Fix with Tailwind @apply**:
```css
.it-header-slim-wrapper {
  @apply bg-[#0066B3] text-white py-2;
}
```

---

### 2. Header Main Colors

**Upstream**:
```css
.it-header-main-wrapper {
  background-color: #FFFFFF !important;  /* White */
  padding-top: 16px;
  padding-bottom: 16px;
}

.it-brand-title {
  color: #000000 !important;  /* Black */
  font-size: 1.5rem;
  font-weight: 700;
}

.it-brand-tagline {
  color: #5d7083 !important;  /* Gray */
  font-size: 0.875rem;
}
```

**Fix with Tailwind @apply**:
```css
.it-header-main-wrapper {
  @apply bg-white py-4;
}

.it-brand-title {
  @apply text-black text-[1.5rem] font-bold;
}

.it-brand-tagline {
  @apply text-[#5d7083] text-[0.875rem];
}
```

---

### 3. Navbar Colors

**Upstream**:
```css
.it-nav-wrapper {
  background-color: #003366 !important;  /* Dark Blue */
}

.navbar-nav .nav-link {
  color: #FFFFFF !important;
  padding: 12px 24px;
  font-weight: 600;
}

.navbar-nav .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}
```

**Fix with Tailwind @apply**:
```css
.it-nav-wrapper {
  @apply bg-[#003366];
}

.navbar-nav .nav-link {
  @apply text-white px-6 py-3 font-semibold hover:bg-white/10;
}
```

---

### 4. Logo Size

**Upstream**:
```css
.it-logo {
  width: 80px !important;
  height: 80px !important;
  border-radius: 50% !important;  /* Circular */
  object-fit: cover;
}
```

**Fix with Tailwind @apply**:
```css
.it-logo {
  @apply w-[80px] h-[80px] rounded-full;
  object-fit: cover;
}
```

---

### 5. Search Button

**Upstream**:
```css
.search-link {
  background-color: #0066B3 !important;
  color: #FFFFFF !important;
  width: 48px;
  height: 48px;
  border-radius: 50% !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
```

**Fix with Tailwind @apply**:
```css
.search-link {
  @apply bg-[#0066B3] text-white w-12 h-12 rounded-full inline-flex items-center justify-center;
}
```

---

## 📊 Spacing Verification

### Header Slim
- **Padding**: py-2 (8px top/bottom) ✅
- **Gap**: gap-4 (16px) ✅

### Header Main
- **Padding**: py-4 (16px top/bottom) ✅
- **Logo-Text Gap**: gap-4 (16px) ✅
- **Title-Tagline Gap**: gap-1 (4px) ✅

### Navbar
- **Nav Link Padding**: px-6 py-3 (24px horizontal, 12px vertical) ✅
- **Nav Gap**: gap-0 ✅

---

## 🎨 Color Palette (Exact Values)

```css
:root {
  /* Header Colors */
  --header-slim-bg: #0066B3;      /* Deep Blue */
  --header-main-bg: #FFFFFF;       /* White */
  --navbar-bg: #003366;            /* Dark Blue */
  
  /* Text Colors */
  --text-primary: #000000;         /* Black */
  --text-secondary: #5d7083;       /* Gray */
  --text-on-primary: #FFFFFF;      /* White */
  
  /* Accent Colors */
  --accent-blue: #0066B3;
  --accent-dark: #003366;
}
```

---

## 🔍 Visual Verification Checklist

### Header
- [ ] Header Slim background: #0066B3 (Deep Blue)
- [ ] Header Slim text: #FFFFFF (White)
- [ ] Header Main background: #FFFFFF (White)
- [ ] Brand title: #000000 (Black), 1.5rem, bold
- [ ] Brand tagline: #5d7083 (Gray), 0.875rem
- [ ] Logo: 80x80px, circular
- [ ] Search button: #0066B3, circular, 48x48px

### Navbar
- [ ] Navbar background: #003366 (Dark Blue)
- [ ] Nav links: #FFFFFF (White)
- [ ] Nav links hover: rgba(255,255,255,0.1)
- [ ] Nav link padding: 24px horizontal, 12px vertical

### Spacing
- [ ] Header Slim padding: 8px top/bottom
- [ ] Header Main padding: 16px top/bottom
- [ ] Logo-Text gap: 16px
- [ ] Title-Tagline gap: 4px

---

## 🤖 OpenViking Context

```bash
openviking add-memory "CSS visual alignment in progress. Using screenshots to match colors, spacing, typography exactly. Tailwind @apply with exact hex values from upstream."
```

---

**Last Updated**: 2026-03-30  
**Status**: 🟡 In Progress  
**Next**: Apply CSS fixes, verify with screenshots  
**Owner**: Multi-Agent Team
