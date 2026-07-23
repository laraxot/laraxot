# GSD Plan: HTML Structure Parity Fix

**Goal**: Reach 95%+ HTML structural match between AGID and FixCity homepage

**Current Status**: 13.51% match (FAIL)
**Target**: 95%+ match

---

## 🎯 Critical Issues (Must Fix)

### 1. Header Attributes Mismatch ❌
- **AGID**: `header.it-header-wrapper[data-bs-target][lass][style]`
- **FixCity**: `header.it-header-wrapper[data-bs-target][lass][role]`
- **Fix**: Remove `role="banner"`, add `style=""`

### 2. Dropdown Menu ID ❌
- **AGID**: No ID on dropdown-menu
- **FixCity**: `id="languages"`
- **Fix**: Remove `id="languages"`

### 3. SVG Icon Attributes ❌
- **AGID**: `use[xlink:href]`
- **FixCity**: `use[href]`
- **Fix**: Use `xlink:href` instead of `href`

### 4. Image vs SVG ❌
- **AGID**: `svg.icon[...]` + `use[xlink:href]`
- **FixCity**: `img.icon[alt][src]`
- **Fix**: Use inline SVG sprites, not `<img>` tags

### 5. Missing 724 Elements ❌
- Header navbar complete structure
- Hero section
- Services section
- Administration section
- News section
- Events section
- Footer sections

---

## 📋 Wave Execution Plan

### Wave 1: Header Structure (CRITICAL)
**Files**:
- `resources/views/components/blocks/header/slim.blade.php`
- `resources/views/components/blocks/header/main.blade.php`
- `resources/views/components/blocks/header/navbar.blade.php`

**Tasks**:
1. ✅ Remove `role="banner"` from header wrapper
2. ✅ Add `style=""` to header wrapper
3. ✅ Remove `id="languages"` from dropdown
4. ✅ Change `href` to `xlink:href` in `<use>` tags
5. ✅ Replace `<img>` with inline SVG sprites

### Wave 2: Content Sections (HIGH)
**Files**:
- `resources/views/components/blocks/hero/homepage.blade.php`
- `resources/views/components/blocks/services/homepage.blade.php`
- `resources/views/components/blocks/administration/homepage.blade.php`
- `resources/views/components/blocks/news/homepage.blade.php`
- `resources/views/components/blocks/events/homepage.blade.php`

**Tasks**:
1. Verify all sections exist
2. Match class names exactly
3. Match attribute names exactly
4. Match element order

### Wave 3: Footer Structure (HIGH)
**File**: `resources/views/components/blocks/footer/main.blade.php`

**Tasks**:
1. Match footer structure exactly
2. Match all links
3. Match social icons

### Wave 4: Verification (MEDIUM)
**Tasks**:
1. Run html_parity_check.py
2. Verify 95%+ match
3. Document any remaining differences

---

## ✅ Execution Checklist

### Wave 1: Header
- [ ] Fix header attributes
- [ ] Fix SVG icons
- [ ] Fix dropdown IDs
- [ ] Re-run parity check

### Wave 2: Content
- [ ] Verify hero section
- [ ] Verify services section
- [ ] Verify administration section
- [ ] Verify news section
- [ ] Verify events section

### Wave 3: Footer
- [ ] Verify footer structure
- [ ] Verify all links
- [ ] Verify social icons

### Wave 4: Verify
- [ ] Run automated check
- [ ] Document results
- [ ] Update docs

---

**Next Action**: Execute Wave 1 - Header Structure Fix
