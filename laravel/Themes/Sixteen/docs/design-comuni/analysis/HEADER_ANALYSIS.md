# Header Analysis - Design Comuni vs FixCity

**Date:** 2026-04-01  
**Status:** 🔄 **In Progress**  
**Priority:** 🔴 **Critical**  

---

## 📊 Comparison Overview

### Reference
**URL:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Screenshot:** `screenshots/homepage/reference-header.png`

### Current Implementation
**URL:** http://fixcity.local/it/tests/homepage  
**Screenshot:** `screenshots/homepage/current-header.png`

---

## 🔍 Visual Differences

### 1. Colors

| Element | Reference | Current | Status |
|---------|-----------|---------|--------|
| Header Slim Background | `#0066B3` (Blue) | `#007a52` (Green) | ❌ Different |
| Header Main Background | `#007a52` (Green) | `#007a52` (Green) | ✅ Match |
| Text Color (Slim) | White | White | ✅ Match |
| Text Color (Main) | White | White | ✅ Match |

**Fix Required:** Header slim should use `#0066B3` (Bootstrap Italia Blue)

---

### 2. Logo Visibility

| Issue | Reference | Current | Status |
|-------|-----------|---------|--------|
| Logo Displayed | ✅ Yes (82x82px) | ❌ Not visible | ❌ Missing |
| Logo Alt Text | "Logo Comune" | N/A | ⏳ Pending |
| Logo Position | Left aligned | N/A | ⏳ Pending |

**Fix Required:**
1. Add logo SVG to `/themes/Sixteen/images/logo.svg`
2. Ensure logo is displayed in header-main block
3. Logo dimensions: 82x82px

---

### 3. Brand Text

| Element | Reference | Current | Status |
|---------|-----------|---------|--------|
| Municipality Name | "Nome del Comune" | ❌ Not visible | ❌ Missing |
| Tagline | "Slogan del Comune" | ❌ Not visible | ❌ Missing |
| Font Size (Name) | 2xl (24px) | N/A | ⏳ Pending |
| Font Weight (Name) | Bold | N/A | ⏳ Pending |

**Fix Required:**
1. Display municipality name in header-main
2. Display tagline below name
3. Use Titillium Web font, 2xl, bold

---

### 4. Navigation

| Element | Reference | Current | Status |
|---------|-----------|---------|--------|
| Menu Items | Visible | ❌ Different structure | ❌ Different |
| Hover Effect | Green underline | N/A | ⏳ Pending |
| Mobile Hamburger | Visible | ❌ Different | ❌ Different |

**Fix Required:**
1. Replicate exact navigation structure
2. Add hover effects with Tailwind @apply
3. Fix mobile hamburger menu

---

### 5. Social Icons

| Element | Reference | Current | Status |
|---------|-----------|---------|--------|
| Label "Seguici su" | ✅ Visible | ❌ Missing | ❌ Missing |
| Icons | Facebook, Twitter, Instagram | ❌ Different | ❌ Different |
| Icon Size | 28x28px | N/A | ⏳ Pending |
| Icon Color | White | N/A | ⏳ Pending |

**Fix Required:**
1. Add "Seguici su" label
2. Use correct social icons from SVG library
3. Style with Tailwind @apply

---

### 6. Search Link

| Element | Reference | Current | Status |
|---------|-----------|---------|--------|
| Search Icon | ✅ Visible (circle) | ❌ Different | ❌ Different |
| Background | White circle | N/A | ⏳ Pending |
| Size | 48x48px | N/A | ⏳ Pending |

**Fix Required:**
1. Add search icon in white circle
2. Style with Tailwind @apply

---

## 🛠️ Implementation Plan

### Step 1: Fix Header Slim

**File:** `laravel/Themes/Sixteen/resources/views/components/blocks/header/slim.blade.php`

```blade
<div class="it-header-slim-wrapper" style="background-color: #0066B3;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-header-slim-wrapper-content">
                    {{-- Left: Navigation links --}}
                    <div class="it-header-slim-left-zone">
                        @foreach($links ?? [] as $link)
                            <a href="{{ $link['url'] }}" class="text-white text-sm">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                    
                    {{-- Right: Language + User access --}}
                    <div class="it-header-slim-right-zone">
                        {{-- Language dropdown --}}
                        <div class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle">
                                {{ $language['current'] ?? 'ITA' }}
                                <x-filament::icon icon="heroicon-o-chevron-down" class="w-4 h-4" />
                            </button>
                            <div class="dropdown-menu">
                                @foreach($language['available'] ?? ['ITA', 'ENG'] as $lang)
                                    <a href="#" class="dropdown-item">{{ $lang }}</a>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- User access --}}
                        <a href="/login" class="btn-icon btn-full">
                            <span class="rounded-icon">
                                <x-filament::icon icon="heroicon-o-user" class="w-6 h-6" />
                            </span>
                            <span class="ms-1">Accedi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

### Step 2: Fix Header Main

**File:** `laravel/Themes/Sixteen/resources/views/components/blocks/header/main.blade.php`

```blade
<div class="it-brand-wrapper">
    {{-- Logo --}}
    <a href="/" class="flex items-center gap-3 no-underline text-white">
        <img src="{{ $logo['src'] ?? '/themes/Sixteen/images/logo.svg' }}" 
             alt="{{ $logo['alt'] ?? 'Logo Comune' }}"
             class="it-brand-logo w-[82px] h-[82px] flex-shrink-0" />
        
        {{-- Brand Text --}}
        <div class="it-brand-text flex flex-col">
            <h1 class="it-brand-title text-2xl font-bold text-white mb-0">
                {{ $title ?? 'Nome del Comune' }}
            </h1>
            @if(isset($tagline))
                <p class="it-brand-tagline text-sm text-white/90 mb-0">
                    {{ $tagline }}
                </p>
            @endif
        </div>
    </a>
</div>

{{-- Right Zone: Social + Search --}}
<div class="it-right-zone flex items-center gap-6">
    {{-- Social Icons --}}
    <div class="it-socials flex items-center gap-2">
        <span class="text-sm text-white mr-3 font-normal">Seguici su:</span>
        <ul class="flex gap-1 list-none m-0 p-0">
            @foreach($social ?? [] as $platform)
                <li>
                    <a href="{{ $platform['url'] }}" class="text-white p-1 inline-flex">
                        <x-filament::icon 
                            :icon="'heroicon-'.($platform['platform'] ?? 'o-globe')" 
                            class="w-7 h-7"
                        />
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    
    {{-- Search --}}
    <div class="it-search-wrapper flex items-center gap-2">
        <span class="text-sm text-white">Cerca</span>
        <a href="{{ $search['action'] ?? '/search' }}" 
           class="search-link bg-white text-primary p-3 rounded-full w-12 h-12 inline-flex items-center justify-center">
            <x-filament::icon icon="heroicon-o-magnifying-glass" class="w-6 h-6" />
        </a>
    </div>
</div>
```

---

## 📸 Screenshots

### Reference Header (Design Comuni)

![Reference Header](screenshots/homepage/reference-header.png)

### Current Header (FixCity)

![Current Header](screenshots/homepage/current-header.png)

### Side-by-Side Comparison

![Comparison](screenshots/homepage/header-comparison.png)

---

## ✅ Checklist

### Header Slim

- [ ] Background color `#0066B3`
- [ ] Navigation links visible
- [ ] Language dropdown working
- [ ] User access button styled
- [ ] Text color white

### Header Main

- [ ] Logo displayed (82x82px)
- [ ] Municipality name visible
- [ ] Tagline visible
- [ ] Social icons with "Seguici su" label
- [ ] Search icon in white circle
- [ ] All text white

### CSS (Tailwind @apply)

- [ ] `.it-header-slim-wrapper` styled
- [ ] `.it-brand-wrapper` styled
- [ ] `.it-brand-logo` styled
- [ ] `.it-brand-title` styled
- [ ] `.it-socials` styled
- [ ] `.search-link` styled

---

**Last Updated:** 2026-04-01  
**Next Review:** After header fix  
**Owner:** Frontend Team  

🐮 **Header Analysis Complete!**
