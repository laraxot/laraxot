# 🧠 BMAD Method - Layout Component Correction

**Date**: 2026-03-30  
**Status**: ✅ **CORRECTED**  
**Priority**: 🔴 CRITICAL

---

## 🚨 Errors Fixed

### 1. Bootstrap Italia CSS Link ❌
**WRONG**:
```html
<link rel="stylesheet" href="/themes/sixteen/bootstrap-italia/dist/css/bootstrap-italia.min.css" />
```

**CORRECT**:
```html
{{-- NO Bootstrap Italia CSS - Use Tailwind @apply only --}}
```

**Why**: We use Tailwind @apply to replicate Bootstrap Italia classes, NOT external CSS.

---

### 2. @vite Syntax ❌
**WRONG**:
```blade
@vite(['Themes/Sixteen/resources/css/app.css'])
```

**CORRECT**:
```blade
@vite(['Themes/Sixteen/resources/css/app.css'], 'themes/Sixteen')
```

**Why**: Second parameter specifies the theme path for Vite.

---

### 3. Hardcoded Header ❌
**WRONG**:
```blade
<x-blocks.header.exact-1to1
  region="Nome della Regione"
  municipality="NOME DEL COMUNE"
  ...
/>
```

**CORRECT**:
```blade
<x-section slug="header" :data="$headerData ?? []" />
```

**Why**: Use CMS-driven section component, NOT hardcoded blade.

---

## ✅ Corrected app.blade.php

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
  
  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  
  {{-- Tailwind CSS - CORRECT syntax with theme parameter --}}
  @vite(['Themes/Sixteen/resources/css/app.css'], 'themes/Sixteen')
  
  {{-- NO Bootstrap Italia CSS - Use Tailwind @apply only --}}
</head>
<body class="font-sans antialiased">
  
  {{-- Skip Links --}}
  <div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
  </div>
  
  {{-- Header via Section Component (BMAD method) --}}
  <x-section slug="header" :data="$headerData ?? []" />
  
  {{-- Main Content --}}
  <main class="container" id="main-container">
    {{ $slot }}
  </main>
  
  {{-- Footer via Section Component --}}
  <x-section slug="footer" :data="$footerData ?? []" tpl="full" />
  
  {{-- Scripts --}}
  @vite(['Themes/Sixteen/resources/js/app.js'], 'themes/Sixteen')
  
</body>
</html>
```

---

## 🤖 BMAD Method Applied

### Requirements (John - PM)
- ✅ No external Bootstrap Italia CSS
- ✅ Correct @vite syntax with theme parameter
- ✅ Use CMS-driven section components

### Architecture (Winston - Architect)
- ✅ Tailwind @apply for all Bootstrap Italia classes
- ✅ Section components for header/footer
- ✅ CMS-driven content via JSON

### Implementation (Amelia - Dev)
- ✅ Removed `<link rel="stylesheet">`
- ✅ Fixed @vite syntax
- ✅ Replaced hardcoded header with `<x-section>`

### Verification (Quinn - QA)
- [ ] Test header renders correctly
- [ ] Test footer renders correctly
- [ ] Verify no Bootstrap CSS loaded
- [ ] Verify Tailwind @apply working

---

## 📊 Impact

| Change | Before | After |
|--------|--------|-------|
| **Bootstrap CSS** | ✅ Loaded | ❌ Removed |
| **@vite Syntax** | ❌ Missing theme | ✅ With theme |
| **Header** | ❌ Hardcoded | ✅ CMS-driven |
| **Footer** | ❌ Hardcoded | ✅ CMS-driven |

---

## 🤖 OpenViking Context

```bash
openviking add-memory "app.blade.php corrected: No Bootstrap CSS link, @vite with theme parameter, <x-section> for header/footer. BMAD method applied."
```

---

**Last Updated**: 2026-03-30  
**Status**: ✅ **CORRECTED**  
**Method**: BMAD  
**Owner**: Multi-Agent Team
