# 🎨 Design Comuni Color Palette - EXACT VALUES

**Date**: 2026-03-31  
**Source**: Italian Design System for Municipalities  
**Reference**: https://italia.github.io/design-comuni/

---

## 🎨 Official Color Palette

### Primary Colors (Blu Italia)

| Name | Hex | RGB | Usage |
|------|-----|-----|-------|
| **Blu Italia** | `#0066CC` | rgb(0, 102, 204) | Primary brand color |
| **Blu Scuro** | `#003D73` | rgb(0, 61, 115) | Header/Footer background |
| **Blu Chiaro** | `#4D9BE6` | rgb(77, 155, 230) | Hover states |
| **Blu Molto Chiaro** | `#E6F2FF` | rgb(230, 242, 255) | Light backgrounds |

### Secondary Colors

| Name | Hex | RGB | Usage |
|------|-----|-----|-------|
| **Verde Successo** | `#00B373` | rgb(0, 179, 115) | Success states |
| **Giallo Warning** | `#FFB300` | rgb(255, 179, 0) | Warning states |
| **Rosso Error** | `#D9364F` | rgb(217, 54, 79) | Error states |
| **Grigio Info** | `#5C6F82` | rgb(92, 111, 130) | Info states |

### Neutral Colors (Grigi)

| Name | Hex | RGB | Usage |
|------|-----|-----|-------|
| **Grigio Scuro** | `#333333` | rgb(51, 51, 51) | Primary text |
| **Grigio Medio** | `#666666` | rgb(102, 102, 102) | Secondary text |
| **Grigio Chiaro** | `#999999` | rgb(153, 153, 153) | Tertiary text |
| **Grigio Molto Chiaro** | `#CCCCCC` | rgb(204, 204, 204) | Borders |
| **Grigio Quasi Bianco** | `#F2F2F2` | rgb(242, 242, 242) | Backgrounds |
| **Bianco** | `#FFFFFF` | rgb(255, 255, 255) | Pure white |

---

## 📐 Header Colors (EXACT)

### Top Bar (it-header-slim-wrapper)

```css
/* Background */
background-color: #003D73;  /* Blu Scuro - NOT primary-dark */

/* Border */
border-bottom: 1px solid rgba(255, 255, 255, 0.2);

/* Text */
color: #FFFFFF;  /* White text */

/* Language buttons */
button.active {
    background-color: #FFFFFF;
    color: #003D73;
}

button.inactive {
    background-color: transparent;
    color: #FFFFFF;
}

button.inactive:hover {
    background-color: rgba(255, 255, 255, 0.2);
}
```

### Center Header (it-header-center-wrapper)

```css
/* Background */
background-color: #FFFFFF;  /* Pure white */

/* Borders */
border-bottom: 1px solid #F2F2F2;  /* Very light gray */

/* City Name */
color: #0066CC;  /* Blu Italia - NOT primary (which is darker) */

/* Tagline */
color: #666666;  /* Grigio Medio */

/* Search Bar */
input {
    border: 1px solid #CCCCCC;  /* Grigio Chiaro */
}

input:focus {
    border-color: #0066CC;  /* Blu Italia */
}

/* Social Icons */
a {
    color: #0066CC;  /* Blu Italia */
}

a:hover {
    color: #003D73;  /* Blu Scuro */
}

/* Login Button */
.btn-primary {
    background-color: #0066CC;  /* Blu Italia */
    color: #FFFFFF;
}

.btn-primary:hover {
    background-color: #003D73;  /* Blu Scuro */
}
```

### Navigation (it-nav-wrapper)

```css
/* Background */
background-color: #FFFFFF;  /* White */

/* Border */
border-bottom: 1px solid #CCCCCC;  /* Grigio Chiaro */

/* Menu Items */
.nav-link {
    color: #333333;  /* Grigio Scuro - NOT black */
}

.nav-link:hover {
    color: #0066CC;  /* Blu Italia */
}

/* Highlighted Item */
.nav-item.highlighted .nav-link {
    color: #0066CC;  /* Blu Italia */
    font-weight: 600;  /* Semi-bold, NOT bold */
}

/* Submenu */
.submenu-wrapper {
    background-color: #FFFFFF;
    border: 1px solid #CCCCCC;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.submenu-link {
    color: #333333;
}

.submenu-link:hover {
    background-color: #F2F2F2;
    color: #0066CC;
}
```

---

## 📐 Footer Colors (EXACT)

### Main Footer (it-footer-wrapper)

```css
/* Background */
background-color: #003D73;  /* Blu Scuro - SAME as top bar */

/* Text */
color: #FFFFFF;  /* White */

/* Links */
a {
    color: rgba(255, 255, 255, 0.8);  /* White with 80% opacity */
}

a:hover {
    color: #FFFFFF;  /* Pure white */
}

/* Section Titles */
h3 {
    color: #FFFFFF;
    font-weight: 600;
}

/* Contact Info */
.contact-info p {
    color: rgba(255, 255, 255, 0.8);
}

/* Legal Links */
.legal-links a {
    color: rgba(255, 255, 255, 0.6);  /* White with 60% opacity */
    font-size: 0.875rem;
}

/* Social Icons */
.social-links a {
    color: #FFFFFF;
}

.social-links a:hover {
    color: #4D9BE6;  /* Blu Chiaro */
}
```

---

## 🔍 Common Mistakes to Avoid

### ❌ WRONG (Current Implementation)

```css
/* Top Bar - WRONG */
background-color: var(--bs-primary-dark);  /* Too dark/black */
background-color: #000033;  /* Too dark */

/* Header - WRONG */
color: var(--primary);  /* May be wrong shade */
text-primary  /* Bootstrap class may not match */

/* Footer - WRONG */
background-color: var(--primary-dark);  /* May be wrong */
background-color: #001a33;  /* Too dark */
```

### ✅ CORRECT (Design Comuni)

```css
/* Top Bar - CORRECT */
background-color: #003D73;  /* Blu Scuro */

/* Header - CORRECT */
color: #0066CC;  /* Blu Italia for city name */
color: #666666;  /* Grigio Medio for tagline */

/* Footer - CORRECT */
background-color: #003D73;  /* Blu Scuro - SAME as top bar */
```

---

## 🎨 Tailwind CSS Mapping

### Exact Tailwind Classes

```css
/* Custom colors in tailwind.config.js */
colors: {
    'design-comuni': {
        'blue-dark': '#003D73',    /* Header/Footer bg */
        'blue-primary': '#0066CC', /* Primary blue */
        'blue-light': '#4D9BE6',   /* Hover states */
        'blue-lighter': '#E6F2FF', /* Light bg */
        
        'gray-dark': '#333333',    /* Primary text */
        'gray-medium': '#666666',  /* Secondary text */
        'gray-light': '#999999',   /* Tertiary text */
        'gray-lighter': '#CCCCCC', /* Borders */
        'gray-lightest': '#F2F2F2',/* Backgrounds */
    }
}
```

### Tailwind Utility Classes

```blade
{{-- Top Bar - CORRECT --}}
<div class="bg-[#003D73] text-white">

{{-- Header City Name - CORRECT --}}
<h1 class="text-[#0066CC]">

{{-- Header Tagline - CORRECT --}}
<p class="text-[#666666]">

{{-- Footer - CORRECT --}}
<footer class="bg-[#003D73] text-white">

{{-- Footer Links - CORRECT --}}
<a class="text-white/80 hover:text-white">
```

---

## 📊 Color Comparison Table

| Element | WRONG Color | CORRECT Color | Difference |
|---------|-------------|---------------|------------|
| **Top Bar BG** | `#000033` (too dark) | `#003D73` | Much lighter |
| **Header City** | `#003D73` (too dark) | `#0066CC` | Brighter blue |
| **Header Tagline** | `#999999` (too light) | `#666666` | Darker gray |
| **Footer BG** | `#001a33` (too dark) | `#003D73` | Much lighter |
| **Footer Links** | `#CCCCCC` (too light) | `rgba(255,255,255,0.8)` | White with opacity |

---

## ✅ Action Items

### Immediate Fixes

1. **Top Bar Background**
   - FROM: `var(--bs-primary-dark)` or `#000033`
   - TO: `#003D73`

2. **Header City Name**
   - FROM: `text-primary` (Bootstrap)
   - TO: `#0066CC`

3. **Header Tagline**
   - FROM: `text-gray-600` (Bootstrap gray)
   - TO: `#666666`

4. **Footer Background**
   - FROM: `var(--primary-dark)` or `#001a33`
   - TO: `#003D73`

5. **Footer Links**
   - FROM: `text-gray-300` or `#CCCCCC`
   - TO: `text-white/80` (white with 80% opacity)

---

**Status**: 🟡 **NEEDS CORRECTION**  
**Next**: Update blade components with exact colors  
**Priority**: P0 (Critical - Visual mismatch)

**Color palette documented! 🎨**
