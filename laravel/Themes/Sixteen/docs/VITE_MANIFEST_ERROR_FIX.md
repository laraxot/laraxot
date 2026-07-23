# Vite Manifest Error Fix - @vite() Second Parameter

**Date:** 2026-04-01  
**Status:** ✅ **COMPLETE**  
**Priority:** 🔴 **Critical**  

---

## 🎯 Error

```
Vite manifest not found at: /var/www/_bases/base_fixcity_fila5/public_html/build/manifest.json
```

---

## 🔍 Root Cause

### ❌ WRONG

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Problem:** Missing second parameter

**Laravel looks for:**
```
public_html/build/manifest.json  ← WRONG LOCATION
```

---

## ✅ Solution

### CORRECT

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
```

**Why it works:**
- Second parameter: `'themes/Sixteen'`
- Laravel looks for: `public_html/themes/Sixteen/manifest.json`
- ✅ CORRECT LOCATION

---

## 📚 Why Second Parameter is Required

### Theme Build Architecture

```
Main Laravel App:
  resources/css/app.css
  → Vite builds to public_html/build/manifest.json
  → @vite([...]) works without second param

Theme (Sixteen):
  laravel/Themes/Sixteen/resources/css/app.css
  → Vite builds to themes/Sixteen/public/manifest.json
  → npm run copy to public_html/themes/Sixteen/manifest.json
  → @vite([...], 'themes/Sixteen') NEEDS second param
```

### File Locations

```
public_html/
├── build/
│   └── manifest.json  (Main Laravel app)
└── themes/
    └── Sixteen/
        └── manifest.json  (Theme build - CORRECT!)
```

---

## 🔧 How to Fix

### Step 1: Find All @vite() Calls

```bash
grep -r "@vite" laravel/Themes/Sixteen/resources/views/ --include="*.blade.php"
```

### Step 2: Add Second Parameter

**Before:**
```blade
@vite(['resources/css/app.css'])
```

**After:**
```blade
@vite(['resources/css/app.css'], 'themes/Sixteen')
```

### Step 3: Rebuild Assets

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen
npm run build
npm run copy
```

---

## 📋 Files Fixed

### app.blade.php

**Before:**
```blade
<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])  ← WRONG
</head>
```

**After:**
```blade
<x-layouts.main>  ← Extends main.blade.php (has correct @vite)
    <x-section slug="header" />
    {{ $slot }}
    <x-section slug="footer" />
</x-layouts.main>
```

### main.blade.php

**Already correct:**
```blade
<head>
    @vite(['resources/css/app.css'], 'themes/Sixteen')  ← CORRECT
</head>
<body>
    @vite(['resources/js/app.js'], 'themes/Sixteen')  ← CORRECT
</body>
```

---

## 📊 Impact

### Before Fix

| File | @vite() | Manifest Path | Status |
|------|---------|---------------|--------|
| main.blade.php | `@vite([...], 'themes/Sixteen')` | themes/Sixteen/ | ✅ Correct |
| app.blade.php | `@vite([...])` | build/ | ❌ Wrong |

### After Fix

| File | @vite() | Manifest Path | Status |
|------|---------|---------------|--------|
| main.blade.php | `@vite([...], 'themes/Sixteen')` | themes/Sixteen/ | ✅ Correct |
| app.blade.php | extends main | themes/Sixteen/ | ✅ Correct |

---

## 📚 Documentation Created

1. **VITE_SECOND_PARAMETER_GUIDE.md** - Complete guide
2. **VITE_MANIFEST_FIX_COMPLETE.md** - Fix summary
3. **This file** - Quick reference

---

## 🎯 Key Takeaways

### Rule 1: Theme Assets ALWAYS Need Second Parameter

```blade
{{-- Theme CSS --}}
@vite(['resources/css/app.css'], 'themes/Sixteen')

{{-- Theme JS --}}
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

### Rule 2: Main App Assets Don't Need Second Parameter

```blade
{{-- Main Laravel app --}}
@vite(['resources/css/app.css'])
```

### Rule 3: Build in Theme Directory

```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```

### Rule 4: Check Manifest Exists

```bash
ls public_html/themes/Sixteen/manifest.json
```

---

## 🔗 Related Documentation

- [Vite Second Parameter Guide](VITE_SECOND_PARAMETER_GUIDE.md)
- [Vite Manifest Fix Complete](VITE_MANIFEST_FIX_COMPLETE.md)
- [Layout Architecture](layout-architecture.md)
- [Build Commands Guide](build-commands-guide.md)

---

## ✅ Checklist

- [x] Root cause identified
- [x] Documentation created
- [x] app.blade.php fixed (extends main.blade.php)
- [x] main.blade.php verified (already correct)
- [x] Memories saved
- [x] Bidirectional links added

---

**Status:** ✅ **COMPLETE**  
**Last Updated:** 2026-04-01  
**Next Review:** When adding new themes  

🐮 **Vite Manifest Error Fixed & Documented!**
