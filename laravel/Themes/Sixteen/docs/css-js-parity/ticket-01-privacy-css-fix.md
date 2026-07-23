# CSS/JS Parity Fix - Segnalazione 01 Privacy

**Phase**: CSS/JS Visual Parity  
**Page**: `segnalazione-01-privacy`  
**Date**: 2026-04-09  
**Status**: 🔄 IN PROGRESS  
**HTML Parity**: 99.8% (LOCKED - DO NOT TOUCH HTML)  
**Visual Parity Target**: 90%+

---

## 📊 Baseline Analysis (2026-04-09 10:11)

### Visual Parity Issues Found

| Issue | Severity | Count | Details |
|-------|----------|-------|---------|
| **Wrong text color** | 🔴 HIGH | 430 elements | Using `rgb(34,34,34)` instead of `rgb(0,0,0)` |
| **Wrong border colors** | 🟡 MEDIUM | 76 elements | Missing `rgb(212,212,212)` |
| **Wrong secondary text** | 🟡 MEDIUM | 14 elements | Using `rgb(117,117,117)` instead of `rgb(69,90,100)` |
| **Font size mismatches** | 🟡 MEDIUM | 5 combinations | 18px, 16px, 14px missing |
| **Background opacity** | 🟢 LOW | 70 elements | Using `rgba(255,255,255,0.82)` |

### Color Mapping (Reference → Local Current → Fix)

| Reference Color | Local Current | Fix Required | Usage |
|----------------|---------------|--------------|-------|
| `rgb(0, 0, 0)` | `rgb(34, 34, 34)` | ✅ Change to `#000000` | Primary text |
| `rgb(212, 212, 212)` | Missing | ✅ Add `#d4d4d4` | Borders, dividers |
| `rgb(69, 90, 100)` | `rgb(117, 117, 117)` | ✅ Change to `#455a64` | Secondary text |
| `rgb(93, 112, 131)` | `rgb(85, 85, 85)` | ✅ Change to `#5d7083` | Tertiary text |
| `rgb(255, 255, 255)` | ✅ Correct | ✅ Keep | White backgrounds |

### Font Issues

| Missing in Local | Reference Size | Reference Weight | Sample |
|-----------------|----------------|------------------|--------|
| Titillium Web | 18px | 400 | "ITA selezionata", nav items |
| Titillium Web | 16px | 400 | "Vai ai contenuti" |
| Titillium Web | 14px | 400 | "1/3" stepper |

---

## 🔧 CSS/JS Fixes Applied

### 1. text-paragraph Color Fix
**File**: `style-apply.css:2227`  
**Issue**: Using gray-700 (#374151) instead of reference #191919  
**Fix**: Changed `color: #5C6F82` → `color: #191919`

```css
/* BEFORE */
.text-paragraph {
  @apply text-base leading-relaxed;
  font-family: 'Titillium Web', sans-serif;
  color: #5C6F82;  /* WRONG - grayish blue */
  line-height: 1.6;
}

/* AFTER */
.text-paragraph {
  @apply text-base leading-relaxed;
  font-family: 'Titillium Web', sans-serif;
  color: #191919;  /* CORRECT - near black */
  line-height: 1.6;
}
```

### 2. Alpine.js Duplicate Instance Fix
**File**: `app.js` (complete rewrite)  
**Issue**: "Detected multiple instances of Alpine running"  
**Root Cause**: 
- Static `import Alpine from 'alpinejs'` at top of file
- Livewire/Filament also loads Alpine.js via `@livewireScripts`
- Both instances tried to call `Alpine.start()`

**Solution**:
```javascript
// BEFORE: Static import - always loads Alpine
import Alpine from 'alpinejs';
Alpine.start(); // Called even when Livewire already started it!

// AFTER: Dynamic import - only loads if needed
async function bootstrapAlpine() {
    // Case 1: Alpine already provided by Livewire/Filament
    if (typeof window.Alpine !== 'undefined') {
        registerAlpineComponents(window.Alpine);
        return; // Don't import, don't start
    }

    // Case 2: Alpine not loaded - import and start ourselves
    const mod = await import('alpinejs');
    const Alpine = mod.default || mod;
    registerAlpineComponents(Alpine);
    Alpine.start();
}
bootstrapAlpine();
```

**Build Result**:
- `app-BKq_DBcR.js` (16.14 kB) - Main app, loads immediately
- `module.esm-DmcgkPTo.js` (45.78 kB) - Alpine, loads ONLY if Livewire didn't provide it

**Impact**: 
- ✅ No more "multiple instances" warning
- ✅ Smaller initial JS payload on Livewire pages
- ✅ Alpine still available on non-Livewire pages

### 2. Reference Color Variables
**File**: `segnalazione-parity.css` (NEW section to add)  
**Purpose**: Define exact Bootstrap Italia colors

```css
/* Bootstrap Italia Reference Colors */
:root {
  /* Text colors */
  --bi-text-primary: #000000;      /* rgb(0,0,0) - pure black */
  --bi-text-secondary: #455a64;    /* rgb(69,90,100) - blue-gray */
  --bi-text-tertiary: #5d7083;     /* rgb(93,112,131) - lighter blue-gray */
  --bi-text-muted: #5c6f82;        /* Original Bootstrap Italia gray */
  
  /* Border colors */
  --bi-border-light: #d4d4d4;      /* rgb(212,212,212) - light gray */
  --bi-border: #e0e0e0;            /* Standard border */
  
  /* Background colors */
  --bi-bg-light: #f5f5f5;          /* Light background */
  --bi-bg-white: #ffffff;          /* White */
}
```

### 3. Body Text Color Override
**File**: `segnalazione-parity.css`  
**Issue**: Global text color is `rgb(34,34,34)` instead of `rgb(0,0,0)`

```css
.segnalazione-privacy-page,
.page-content[data-slug="tests.segnalazione-01-privacy"] {
  color: #000000;  /* Pure black, not #222222 */
}
```

---

## 📋 Next Steps

1. ✅ **text-paragraph color fixed** (style-apply.css)
2. ⏳ **Add reference color variables** (segnalazione-parity.css)
3. ⏳ **Fix body text color** from #222222 → #000000
4. ⏳ **Add missing border colors** #d4d4d4
5. ⏳ **Fix secondary text colors** #455a64
6. ⏳ **Rebuild and screenshot comparison**
7. ⏳ **Update this doc with results**

---

## 🎯 Success Criteria

- [ ] Visual similarity > 90% (screenshot comparison)
- [ ] All text colors match reference
- [ ] All border colors match reference
- [ ] Font sizes match reference exactly
- [ ] HTML parity remains > 95% (unchanged)

---

## 📸 Screenshots Location

```
laravel/Themes/Sixteen/storage/visual-parity/segnalazione-01-privacy/
├── local.png           # Current state
├── reference.png       # Target state
└── analysis.json       # Detailed font/color analysis
```

---

**Cross-References**:
- [HTML Parity Report](../../../../bashscripts/html/output/report.md)
- [Visual Parity Script](../scripts/visual-parity.mjs)
- [Design Comuni Reference](https://github.com/italia/design-comuni-pagine-statiche)
- [Bootstrap Italia Colors](https://italia.github.io/design-comuni-pagine-statiche/)
