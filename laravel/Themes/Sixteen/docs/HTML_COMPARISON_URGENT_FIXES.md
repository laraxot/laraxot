# 🚨 HTML Body Comparison - URGENT FIXES NEEDED

> **GSD Analysis: 397 Structural Differences Found**

## 📊 Critical Results

**Date:** 2026-04-01  
**Tool:** `gsd-html-compare.php`  
**Match:** ❌ **FAIL** (397 differences)

### Statistics

| Metric | Original | Replica | Status |
|--------|----------|---------|--------|
| Total Elements | 1623 | 1226 | ❌ -397 elements |
| Matching | - | - | ❌ Poor |
| ID Mismatches | - | 47 | ❌ |
| Class Mismatches | - | 703 | ❌ |
| Tag Mismatches | - | 1022 | ❌ |

---

## 🔴 Critical Issues

### 1. Header Structure WRONG

**Original:**
```html
<header class="it-header">
  <div class="it-header-slim-wrapper">
    ...
  </div>
  <div class="it-header-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="it-header-main">
            <div class="it-brand-wrapper">
              ...
            </div>
            <nav class="it-navbar">
              ...
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
```

**Replica (WRONG):**
```html
<header class="it-header-wrapper">
  <div class="it-header-slim-wrapper">
    ...
  </div>
  <div class="it-header-center-wrapper">
    <div class="container">
      ...
    </div>
  </div>
</header>
```

**Fix Needed:**
- ❌ `it-header-wrapper` → ✅ `it-header`
- ❌ `it-header-center-wrapper` → ✅ `it-header-wrapper`
- ❌ Missing: `it-header-main`, `it-brand-wrapper`, `it-navbar`

---

### 2. Main Content ID WRONG

**Original:** `id="main-content"`  
**Replica:** `id="main-container"` (or missing)

**Fix:** Change to `id="main-content"`

---

### 3. Skip Links WRONG

**Original:**
```html
<a class="visually-hidden" href="#main-content">Vai ai contenuti</a>
<a class="visually-hidden" href="#footer">Vai al footer</a>
```

**Replica:** May have different classes or structure

**Fix:** Ensure exact match

---

### 4. Footer ID WRONG

**Original:** `id="footer"`  
**Replica:** May be missing or different

**Fix:** Ensure `id="footer"` exists

---

## ✅ Action Plan (GSD Method)

### Phase 1: Fix Header Structure (PRIORITY 🔴)

**File:** `components/sections/header.blade.php` or `components/blocks/header/*`

**Changes:**
1. Wrap entire header in `<header class="it-header">`
2. Use `it-header-wrapper` for main header section (NOT center-wrapper)
3. Add `it-header-main` wrapper inside
4. Add `it-brand-wrapper` for logo/brand
5. Add `it-navbar` for navigation

---

### Phase 2: Fix Main ID (PRIORITY 🟡)

**File:** `components/layouts/app.blade.php`

**Change:**
```blade
{{-- BEFORE --}}
<main id="main-container">

{{-- AFTER --}}
<main id="main-content">
```

---

### Phase 3: Fix Skip Links (PRIORITY 🟡)

**File:** `components/layouts/app.blade.php`

**Ensure:**
```blade
<a class="visually-hidden" href="#main-content">Vai ai contenuti</a>
<a class="visually-hidden" href="#footer">Vai al footer</a>
```

---

### Phase 4: Fix Footer ID (PRIORITY 🟢)

**File:** `components/sections/footer.blade.php` or `components/blocks/footer/main.blade.php`

**Ensure:**
```blade
<footer id="footer">
```

---

## 🎯 Verification

After fixes, run:
```bash
cd laravel
php gsd-html-compare.php
```

**Expected:** < 50 differences (minor only)

---

**Status:** 🔴 **URGENT ACTION REQUIRED**  
**Next:** Fix header structure in header section component
