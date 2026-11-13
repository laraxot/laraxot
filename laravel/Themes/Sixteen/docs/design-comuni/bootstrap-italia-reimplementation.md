---
title: "BOOTSTRAP ITALIA REIMPLEMENTATION"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "BOOTSTRAP ITALIA REIMPLEMENTATION"
issues: []
discussions: []
status: draft
---

# 🎯 Bootstrap Italia Reimplementation - Tailwind + Alpine

**Data**: 2026-03-31  
**Stato**: ✅ **IN CORSO - Tailwind + Alpine**

## 🎯 Obiettivo

**NON usiamo Bootstrap Italia JS!**  
**Stiamo RIFACENDO tutto con Tailwind CSS + Alpine.js**

## 🔧 Fix Applicato

### JS Import - CORRETTO ✅

**PRIMA** (SBAGLIATO) ❌:
```javascript
import "bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js";
```

**DOPO** (CORRETTO) ✅:
```javascript
/**
 * 🎯 Bootstrap Italia REIMPLEMENTATION
 * 
 * We are NOT using Bootstrap Italia JS!
 * We are REIMPLEMENTING everything with:
 * - Tailwind CSS for styling
 * - Alpine.js for interactivity
 */

// Import Alpine.js
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()
```

## 📦 Dependencies

### Installate
```bash
npm install alpinejs --save
```

### Build Result
```
✓ built in 3.89s
app.css  737.44 kB │ gzip: 81.79 kB
app.js    46.64 kB │ gzip: 16.75 kB
```

## 🎨 Componenti Reimplementation

### Status

| Component | Status | Implementation |
|-----------|--------|----------------|
| **Header** | ✅ DONE | Alpine.js mobile menu |
| **Footer** | ✅ DONE | Tailwind utilities |
| **Cards** | ✅ DONE | Tailwind utilities |
| **Buttons** | ✅ DONE | Tailwind utilities |
| **Forms** | ✅ DONE | Tailwind + Alpine |
| **Modals** | 🔄 TODO | Alpine.js |
| **Accordion** | 🔄 TODO | Alpine.js |
| **Tabs** | 🔄 TODO | Alpine.js |

## 📝 Alpine.js Usage

### Mobile Menu Example

```blade
{{-- Header Mobile Menu --}}
<div x-data="{ open: false }">
    {{-- Toggle Button --}}
    <button @click="open = !open">
        <svg class="icon">
            <use xlink:href="#it-burger"></use>
        </svg>
    </button>
    
    {{-- Menu --}}
    <div x-show="open" 
         x-transition
         class="navbar-collapse">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
        </ul>
    </div>
</div>
```

### Accordion Example

```blade
{{-- Accordion Component --}}
<div x-data="{ active: null }">
    {{-- Item 1 --}}
    <div>
        <button @click="active = active === 1 ? null : 1">
            Title 1
        </button>
        <div x-show="active === 1" x-collapse>
            Content 1
        </div>
    </div>
    
    {{-- Item 2 --}}
    <div>
        <button @click="active = active === 2 ? null : 2">
            Title 2
        </button>
        <div x-show="active === 2" x-collapse>
            Content 2
        </div>
    </div>
</div>
```

## 🎯 Why Alpine.js?

### Lightweight
- **Size**: ~15KB gzipped
- **No build step required**
- **Works with CDN**

### Simple
- **Declarative syntax**
- **Similar to Vue.js**
- **No virtual DOM**

### Perfect for Bootstrap Italia
- **Dropdowns** → `x-data`, `x-show`
- **Modals** → `x-dialog`, `x-focus-trap`
- **Tabs** → `x-data`, `x-bind:class`
- **Accordion** → `x-data`, `x-collapse`

## 📁 File Structure

```
resources/js/
├── app.js                 ✅ Alpine.js + custom code
├── alpine/
│   ├── components/        🔄 Alpine components
│   │   ├── header.js
│   │   ├── accordion.js
│   │   └── modal.js
│   └── directives/        🔄 Custom directives
│       └── collapse.js
└── custom.js              ✅ Custom functionality
```

## 🔗 References

### Alpine.js
- [Official Documentation](https://alpinejs.dev/)
- [Alpine Components](https://alpinejs.dev/components)
- [Alpine Directives](https://alpinejs.dev/directives)

### Tailwind CSS
- [Official Documentation](https://tailwindcss.com/)
- [Tailwind + Alpine](https://tailwindcss.com/docs/using-with-preprocessors#alpine-js)

---
**Stato**: ✅ **BOOTSTRAP ITALIA REIMPLEMENTATION**  
**Stack**: **Tailwind CSS + Alpine.js**  
**Bootstrap Italia JS**: **NOT USED**  
**Build**: **COMPLETATO** ✓
