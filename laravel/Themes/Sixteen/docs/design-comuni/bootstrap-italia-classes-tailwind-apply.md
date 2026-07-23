# 🎨 Bootstrap Italia Classes + Tailwind @apply

**Data**: 2026-03-31  
**Stato**: ✅ **INTEGRATO**

## 🎯 Concetto Chiave

**HTML**: Classi Bootstrap Italia (per compatibilità struttura)  
**CSS**: Tailwind `@apply` (per styling personalizzato)

## 📊 Perché Questo Approccio

### Vantaggi

1. ✅ **HTML Identico** - Stessa struttura dell'originale
2. ✅ **CSS Tailwind** - Più manutenibile e personalizzabile
3. ✅ **Nessuna Dipendenza** - No Bootstrap Italia CSS
4. ✅ **Utility Classes** - Tailwind utilities disponibili
5. ✅ **Customization** - Facile modificare colori, spacing, etc.

## 🔧 Implementazione

### HTML (Bootstrap Italia Classes)

```html
<header class="it-header-wrapper">
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <!-- Content -->
        </div>
      </div>
    </div>
  </div>
</header>
```

### CSS (Tailwind @apply)

```css
/* In resources/css/design-comuni.css */

.it-header-wrapper {
  @apply relative bg-primary text-white;
}

.it-header-slim-wrapper {
  @apply bg-primary-dark py-2 text-sm;
}

.container {
  @apply w-full mx-auto px-3;
  max-width: 1200px;
}

.row {
  @apply flex flex-wrap -mx-3;
}

.col-12 {
  @apply flex-none w-full max-w-full px-3;
}
```

## 📁 File Structure

```
laravel/Themes/Sixteen/
├── resources/css/
│   └── design-comuni.css    ← Tailwind @apply styles
├── resources/views/
│   └── components/blocks/   ← Block views con classi BI
└── public/
    └── themes/sixteen/      ← Assets compilati
```

## 🎨 Classi Bootstrap Italia Supportate

### Layout

| Classe | Tailwind @apply |
|--------|----------------|
| `.container` | `@apply w-full mx-auto px-3; max-width: 1200px;` |
| `.row` | `@apply flex flex-wrap -mx-3;` |
| `.col-12` | `@apply flex-none w-full max-w-full px-3;` |
| `.col-lg-6` | `@media (min-width: 992px) { @apply flex-none w-1/2; }` |
| `.col-lg-4` | `@media (min-width: 992px) { @apply flex-none w-1/3; }` |

### Header

| Classe | Tailwind @apply |
|--------|----------------|
| `.it-header-wrapper` | `@apply relative bg-primary text-white;` |
| `.it-header-slim-wrapper` | `@apply bg-primary-dark py-2 text-sm;` |
| `.it-header-center-wrapper` | `@apply bg-primary py-4;` |
| `.it-header-navbar-wrapper` | `@apply bg-primary py-3;` |

### Cards

| Classe | Tailwind @apply |
|--------|----------------|
| `.card` | `@apply bg-white border border-gray-200 rounded-lg shadow-sm;` |
| `.card-body` | `@apply p-0;` |
| `.card-title` | `@apply text-xl font-semibold mb-2;` |
| `.card-text` | `@apply text-gray-600;` |

### Components

| Classe | Tailwind @apply |
|--------|----------------|
| `.btn` | `@apply inline-flex items-center justify-center px-6 py-3 rounded cursor-pointer;` |
| `.btn-primary` | `@apply bg-primary text-white;` |
| `.chip` | `@apply inline-block px-3 py-1 rounded text-sm;` |
| `.breadcrumb` | `@apply flex items-center p-0 m-0 list-none text-sm;` |

## 📝 Esempio Completo

### Block View

```blade
{{-- blocks/hero/homepage.blade.php --}}
<section id="head-section">
  <h2 class="visually-hidden">Contenuti in evidenza</h2>
  <div class="container">
    <div class="row">
      {{-- News Card --}}
      <div class="col-lg-6 order-2 order-lg-1">
        <div class="card mb-5">
          <div class="card-body pb-5 px-0">
            <div class="category-top">
              <svg class="icon icon-sm">
                <use xlink:href="{{ asset('.../sprites.svg#it-calendar') }}"></use>
              </svg>
              <span class="title-xsmall-semi-bold fw-semibold">Notizie</span>
              <span class="data fw-normal">18 mag 2022</span>
            </div>
            <a href="#" class="text-decoration-none">
              <h3 class="card-title">Titolo notizia</h3>
            </a>
            <p class="mb-4 pt-3 lora">Estratto notizia...</p>
            <a class="chip chip-simple" href="#">
              <span class="chip-label">Tag</span>
            </a>
          </div>
        </div>
      </div>
      
      {{-- Hero Image --}}
      <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
        <img src="..." class="img-fluid">
      </div>
    </div>
  </div>
</section>
```

### CSS (design-comuni.css)

```css
/* Hero Section */
#head-section {
  @apply py-8;
}

/* Card Styles */
.card {
  @apply bg-white border border-gray-200 rounded-lg shadow-sm;
}

.card-body {
  @apply p-0;
}

.card-title {
  @apply text-xl font-semibold mb-2 text-gray-900;
}

.card-text {
  @apply text-gray-600;
}

/* Category Top */
.category-top {
  @apply flex items-center gap-2 mb-2;
}

/* Icon */
.icon {
  @apply w-4 h-4 fill-current;
}

.icon-sm {
  @apply w-3.5 h-3.5;
}

/* Chip */
.chip {
  @apply inline-block px-3 py-1 rounded text-sm no-underline;
}

.chip-simple {
  @apply bg-transparent text-primary hover:bg-primary hover:text-white transition-colors;
}

/* Typography */
.title-xsmall-semi-bold {
  @apply text-xs font-semibold;
}

.fw-semibold {
  @apply font-semibold;
}

.fw-normal {
  @apply font-normal;
}

/* Utilities */
.visually-hidden {
  @apply sr-only;
}

.text-decoration-none {
  @apply no-underline;
}

.img-fluid {
  @apply w-full h-auto;
}

/* Responsive */
@media (min-width: 992px) {
  .col-lg-6 {
    @apply flex-none w-1/2;
  }
  
  .order-lg-1 {
    @apply order-1;
  }
  
  .order-lg-2 {
    @apply order-2;
  }
  
  .px-lg-3 {
    @apply px-3;
  }
}
```

## 🔍 Come Funziona

### 1. HTML Bootstrap Italia
```html
<div class="card mb-5">
  <div class="card-body">
    <h3 class="card-title">Titolo</h3>
  </div>
</div>
```

### 2. Tailwind Compila
```css
.card {
  @apply bg-white border border-gray-200 rounded-lg shadow-sm;
}
```

### 3. Output CSS
```css
.card {
  background-color: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
```

## 📊 Benefits

### Prima (Bootstrap Italia CSS)
```
❌ Dipendenza da Bootstrap Italia
❌ CSS non personalizzabile
❌ Difficile override
❌ File CSS grande
```

### Dopo (Tailwind @apply)
```
✅ Nessuna dipendenza
✅ CSS personalizzabile
✅ Facile override
✅ CSS ottimizzato
✅ Utility classes disponibili
```

## 📚 References

### Documentation
- [Tailwind @apply](https://tailwindcss.com/docs/reusing-styles#extracting-classes-with-apply)
- [Bootstrap Italia Classes](https://italia.github.io/bootstrap-italia/docs/)

### Project Files
- `resources/css/design-comuni.css` - Tailwind styles
- `resources/views/components/blocks/` - Block views

---

**Stato**: ✅ **INTEGRATO - Bootstrap Italia Classes + Tailwind @apply**  
**HTML**: **Classi Bootstrap Italia**  
**CSS**: **Tailwind @apply**  
**Vantaggio**: **Compatibilità + Flessibilità**
