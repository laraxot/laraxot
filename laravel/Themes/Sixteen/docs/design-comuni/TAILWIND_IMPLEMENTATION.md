# 🎯 Design Comuni with Tailwind CSS - Implementation Guide

**Date**: 2026-03-31  
**Goal**: Replicate Design Comuni template using **ONLY Tailwind CSS**  
**Status**: ✅ In Progress

---

## 🎨 Design Philosophy

### What We're Building
- ✅ **Tailwind CSS** utility classes for all styling
- ✅ **Design Comuni** visual appearance (colors, spacing, typography)
- ✅ **Bootstrap Italia** HTML structure (for compatibility)
- ❌ **NO Bootstrap Italia CSS imports**
- ❌ **NO Bootstrap Italia dependencies**

### Why This Approach
1. **Performance**: Tailwind purges unused CSS
2. **Customization**: Easy to modify with Tailwind config
3. **Consistency**: Single CSS framework
4. **Modern**: Tailwind v4 with latest features

---

## 🎨 Color Palette (Design Comuni)

### Primary Colors
```css
/* Blu Italia - Primary brand color */
--color-primary: #0066CC;
--color-primary-dark: #003D73;
--color-primary-light: #4D9BE6;

/* Success/Error/Warning */
--color-success: #00B373;
--color-error: #D9364F;
--color-warning: #FFB300;
```

### Neutral Colors
```css
/* Grays for text and backgrounds */
--color-gray-dark: #333333;    /* Primary text */
--color-gray-medium: #666666;  /* Secondary text */
--color-gray-light: #999999;   /* Tertiary text */
--color-gray-lighter: #CCCCCC; /* Borders */
--color-gray-lightest: #F2F2F2;/* Backgrounds */
```

### Tailwind Config
```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        'design-comuni': {
          'blue-dark': '#003D73',
          'blue-primary': '#0066CC',
          'blue-light': '#4D9BE6',
          'gray-dark': '#333333',
          'gray-medium': '#666666',
          'gray-light': '#999999',
          'gray-lighter': '#CCCCCC',
          'gray-lightest': '#F2F2F2',
        }
      }
    }
  }
}
```

---

## 📐 Component Implementation

### 1. Top Bar (Header Slim)

**Reference**: Design Comuni top bar  
**Tailwind Implementation**:

```blade
{{-- Top Bar with Tailwind classes --}}
<div class="bg-[#003D73] text-white border-b border-white/20">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between py-2">
      {{-- Region Link --}}
      <a href="#" 
         class="text-white font-medium text-sm hover:text-white transition-colors lg:block">
        Nome della Regione
      </a>
      
      {{-- Right Zone --}}
      <div class="flex items-center gap-2">
        {{-- Language Dropdown --}}
        <div class="relative">
          <button class="flex items-center gap-1 text-white bg-transparent border-0 hover:bg-white/20">
            <span>ITA</span>
            <svg class="w-4 h-4">
              <use href="#it-expand"></use>
            </svg>
          </button>
        </div>
        
        {{-- Login Button --}}
        <a href="#" 
           class="inline-flex items-center gap-2 px-4 py-2 
                  bg-[#0066CC] text-white 
                  border border-[#0066CC] rounded-lg
                  hover:bg-[#003D73] transition-colors">
          <svg class="w-5 h-5">
            <use href="#it-user"></use>
          </svg>
          <span class="hidden lg:inline">Accedi all'area personale</span>
        </a>
      </div>
    </div>
  </div>
</div>
```

**Key Tailwind Classes**:
- `bg-[#003D73]` - Exact color match
- `border-white/20` - RGBA border
- `hidden lg:inline` - Responsive
- `hover:bg-[#003D73]` - Hover state

---

### 2. Main Header (Header Center)

**Reference**: Design Comuni main header  
**Tailwind Implementation**:

```blade
{{-- Main Header with Tailwind classes --}}
<div class="bg-white border-b border-gray-200">
  <div class="container mx-auto px-4 py-4">
    <div class="flex items-center justify-between">
      {{-- Brand --}}
      <div class="flex items-center gap-3">
        <svg width="82" height="82" class="icon">
          <image xlink:href="logo.svg"/>
        </svg>
        <div class="flex flex-col">
          <div class="text-xl font-bold text-[#0066CC] m-0 hover:text-[#003D73]">
            Il mio Comune
          </div>
          <div class="text-sm text-[#666666] m-0 hidden md:block">
            Un comune da vivere
          </div>
        </div>
      </div>
      
      {{-- Social Links --}}
      <div class="hidden lg:flex items-center gap-2">
        <span class="text-sm text-gray-600 mr-2">Seguici su</span>
        <ul class="flex list-none m-0 p-0 gap-2">
          <li>
            <a href="#" class="text-[#0066CC] text-sm no-underline hover:text-[#003D73]">
              Twitter
            </a>
          </li>
          <!-- more social links -->
        </ul>
      </div>
    </div>
  </div>
</div>
```

**Key Tailwind Classes**:
- `text-[#0066CC]` - Exact brand color
- `hidden md:block` - Responsive visibility
- `flex flex-col` - Flexbox layout
- `gap-2`, `gap-3` - Consistent spacing

---

### 3. Footer

**Reference**: Design Comuni footer  
**Tailwind Implementation**:

```blade
{{-- Footer with Tailwind classes --}}
<footer class="bg-[#003D73] text-white pt-12 pb-8">
  <div class="container mx-auto px-4">
    {{-- Logo and Brand --}}
    <div class="flex flex-col items-center md:items-start gap-4 mb-8">
      <img src="logo-eu.svg" alt="UE" class="h-12 w-auto mb-4">
      <div class="flex items-center gap-3 text-white no-underline">
        <svg class="w-10 h-10">
          <use href="#it-pa"></use>
        </svg>
        <h2 class="text-xl font-bold m-0">Nome del Comune</h2>
      </div>
    </div>
    
    {{-- Footer Columns --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      {{-- Administration --}}
      <div class="mb-8 md:mb-0">
        <h4 class="text-lg font-semibold mb-4 text-white">Amministrazione</h4>
        <ul class="list-none p-0 m-0 space-y-2">
          <li>
            <a href="#" class="text-white/80 no-underline text-sm hover:text-white hover:underline">
              Organi di governo
            </a>
          </li>
          <!-- more links -->
        </ul>
      </div>
      
      {{-- Services (wider column) --}}
      <div class="md:col-span-2">
        <h4 class="text-lg font-semibold mb-4 text-white">Categorie di servizio</h4>
        <div class="grid grid-cols-2 gap-4">
          <ul class="list-none p-0 m-0 space-y-2">
            <!-- links -->
          </ul>
          <ul class="list-none p-0 m-0 space-y-2">
            <!-- links -->
          </ul>
        </div>
      </div>
      
      {{-- News --}}
      <div>
        <h4 class="text-lg font-semibold mb-4 text-white">Novità</h4>
        <ul class="list-none p-0 m-0 space-y-2">
          <li>
            <a href="#" class="text-white/80 no-underline text-sm hover:text-white hover:underline">
              Notizie
            </a>
          </li>
          <!-- more links -->
        </ul>
      </div>
    </div>
  </div>
</footer>
```

**Key Tailwind Classes**:
- `bg-[#003D73]` - Exact footer color
- `text-white/80` - RGBA text opacity
- `grid grid-cols-1 md:grid-cols-4` - Responsive grid
- `space-y-2` - Vertical spacing

---

## 🔧 Tailwind Configuration

### tailwind.config.js

```js
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'design-comuni': {
          'blue-dark': '#003D73',
          'blue-primary': '#0066CC',
          'blue-light': '#4D9BE6',
          'gray-dark': '#333333',
          'gray-medium': '#666666',
          'gray-light': '#999999',
          'gray-lighter': '#CCCCCC',
          'gray-lightest': '#F2F2F2',
          'success': '#00B373',
          'error': '#D9364F',
          'warning': '#FFB300',
        }
      },
      fontFamily: {
        sans: ['Titillium Web', 'system-ui', 'sans-serif'],
        mono: ['Roboto Mono', 'monospace'],
      },
      spacing: {
        '128': '32rem',
      },
    },
  },
  plugins: [],
}
```

---

## 📋 Implementation Checklist

### Components to Create

- [x] **Top Bar** - Header slim with region link, language, login
- [x] **Main Header** - Brand, logo, social links
- [x] **Navigation** - Menu with submenus
- [x] **Hero** - City banner
- [x] **Featured Content** - News card
- [x] **Government Bodies** - 3 cards
- [x] **Events Calendar** - 7-day grid
- [x] **Topics** - 4 topic cards
- [x] **Thematic Sites** - 3 sites
- [x] **Feedback** - 5-star rating
- [x] **Contact** - Contact options
- [x] **Footer** - Multi-column footer

### CSS Files

- [x] `bootstrap-italia-classes.css` - Class replicas via @apply
- [x] `design-comuni.css` - Component styles
- [x] `agid-colors.css` - Color variables
- [x] `app.css` - Main entry (NO external imports)

---

## ✅ Benefits of This Approach

### Performance
- ✅ **Smaller CSS bundle** - Tailwind purges unused classes
- ✅ **No external dependencies** - Everything is local
- ✅ **Optimized for production** - Minified automatically

### Developer Experience
- ✅ **Consistent syntax** - All Tailwind
- ✅ **Easy customization** - Change in config
- ✅ **Responsive built-in** - `md:`, `lg:` prefixes
- ✅ **Dark mode support** - `dark:` variants

### Design Accuracy
- ✅ **Exact colors** - `#003D73`, `#0066CC`, etc.
- ✅ **Exact spacing** - Design Comuni spacing scale
- ✅ **Exact typography** - Titillium Web font
- ✅ **Exact layout** - Bootstrap Italia structure

---

## 🎯 Next Steps

1. **Build CSS**: `npm run build` in theme directory
2. **Test in browser**: Verify visual match
3. **Capture screenshots**: Compare with reference
4. **Optimize**: Purge unused classes
5. **Document**: Create component library

---

**Status**: ✅ **TAILWIND-ONLY IMPLEMENTATION**  
**Bootstrap Italia CSS**: ❌ REMOVED  
**Tailwind @apply**: ✅ ALL STYLING  
**Goal**: 100% Design Comuni match with Tailwind

**Pure Tailwind CSS implementation! 🎨✨**
