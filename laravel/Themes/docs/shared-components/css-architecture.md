# CSS Architecture Documentation

## 🏗️ Struttura del CSS

Il file `/src/style.css` è organizzato in sezioni logiche per facilitare la manutenzione e comprensione del codice.

### 📋 Indice delle Sezioni

```css
/* Linee 1-17: Imports e Setup */
/* Linee 18-65: Variabili CSS e Reset */
/* Linee 66-120: Header Slim (Livello 1) */
/* Linee 121-240: Brand e Layout Centrale */
/* Linee 241-320: Social Icons e Search */
/* Linee 321-400: Navigation System */
/* Linee 401-500: Mobile Menu e Responsive */
/* Linee 501-800: Content Area e Cards */
/* Linee 801-900: Form Elements */
/* Linee 901-1100: Tabs e Interactive Components */
/* Linee 1101-1300: Footer */
/* Linee 1301-1400: Rating Component */
/* Linee 1401-1600: Typography Classes */
/* Linee 1601-1800: Utilities e Helper Classes */
```

## 🎨 Sistema di Variabili CSS

### Colori Principali
```css
:root {
  --bs-primary: #007a52;        /* Verde principale Bootstrap Italia */
  --bs-primary-dark: #00614a;   /* Verde scuro per header slim */
  --bs-secondary: #5d7083;      /* Grigio per elementi secondari */
  --bs-success: #008055;        /* Verde per stati di successo */
  --bs-blue: #006cc6;          /* Blu originale Bootstrap */
  --bs-dark: #17334f;          /* Scuro principale per testi */
}
```

### Scale di Grigi
```css
:root {
  --bs-gray-100: #f8f9fa;
  --bs-gray-200: #e9ecef;
  --bs-gray-300: #dee2e6;
  --bs-gray-400: #ced4da;
  --bs-gray-500: #adb5bd;
}
```

## 📱 Sistema Responsive

### Breakpoints Utilizzati
```css
/* Mobile First Approach */

/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) { }

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) { }

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) { }

/* Extra large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) { }
```

### Pattern Responsive Comuni
```css
/* Pattern: Hide/Show su Breakpoint */
.d-none { display: none; }
.d-lg-block { display: none; }
@media (min-width: 992px) {
  .d-lg-block { display: block; }
}

/* Pattern: Layout Flexbox Responsive */
.flex-wrapper {
  display: flex;
  flex-direction: column;
}
@media (min-width: 992px) {
  .flex-wrapper {
    flex-direction: row;
    justify-content: space-between;
  }
}
```

## 🧩 Componenti Principali

### 1. Header System
**Architettura a 3 livelli**:

```css
/* Livello 1: Header Slim */
.it-header-slim-wrapper {
  background-color: var(--bs-primary-dark);
  padding: 0.5rem 0;
}

/* Livello 2: Header Center */
.it-header-center-wrapper {
  background: var(--bs-primary);
  color: white;
  padding: 1rem 0;
}

/* Livello 3: Header Navbar */
.it-header-navbar-wrapper {
  background: var(--bs-primary);
  padding: 0.75rem 0;
}
```

### 2. Navigation System
**Pattern: Menu Unificato Desktop/Mobile**:

```css
/* Base: Hidden per default (mobile) */
.navbar-collapsable {
  position: relative;
  background: none;
}

/* Desktop: Show e Layout Orizzontale */
@media (min-width: 992px) {
  .navbar-collapsable {
    display: flex !important;
    position: static;
    width: 100%;
  }

  .navbar-collapsable .menu-wrapper {
    display: flex;
    justify-content: space-between;
  }
}
```

### 3. Card System
**Pattern: Card Uniforme con Varianti**:

```css
/* Base Card */
.card {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Varianti */
.card.has-bkg-grey {
  background-color: var(--bs-gray-100);
}

.card.shadow-sm {
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
```

### 4. Form Elements
**Pattern: Styling Consistente**:

```css
/* Base Form Control */
.form-control {
  display: block;
  width: 100%;
  padding: 0.375rem 0.75rem;
  border: 1px solid var(--bs-gray-300);
  border-radius: 0.25rem;
}

/* States */
.form-control:focus {
  border-color: var(--bs-primary);
  box-shadow: 0 0 0 2px rgba(0, 108, 198, 0.25);
}
```

## 🎯 Metodologie CSS Utilizzate

### 1. BEM-Like Naming
```css
/* Component */
.cmp-rating { }

/* Element */
.cmp-rating__card-wrapper { }

/* Modifier */
.rating-star--active { }
```

### 2. Utility Classes
```css
/* Spacing */
.mt-5 { margin-top: 1.25rem; }
.pb-3 { padding-bottom: 0.75rem; }

/* Display */
.d-flex { display: flex; }
.d-none { display: none; }

/* Typography */
.title-large { font-size: 2rem; font-weight: 600; }
```

### 3. Component-First Architecture
```css
/* 1. Component Base */
.navbar { }

/* 2. Component Elements */
.navbar-nav { }
.navbar-brand { }

/* 3. Component Modifiers */
.navbar-expand-lg { }

/* 4. Component States */
.navbar-nav .nav-link:hover { }
```

## 🔧 Pattern di Implementazione

### 1. Flexbox Layout Pattern
```css
.flex-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}

/* Responsive Flex */
@media (min-width: 992px) {
  .flex-container {
    flex-wrap: nowrap;
  }
}
```

### 2. Grid Layout Pattern
```css
.grid-container {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

@media (min-width: 768px) {
  .grid-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 992px) {
  .grid-container {
    grid-template-columns: repeat(3, 1fr);
  }
}
```

### 3. State Management Pattern
```css
/* Base State */
.interactive-element {
  transition: all 0.2s ease;
}

/* Hover State */
.interactive-element:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Active State */
.interactive-element:active {
  transform: translateY(0);
}

/* Focus State */
.interactive-element:focus {
  outline: 2px solid var(--bs-primary);
  outline-offset: 2px;
}
```

## 📐 Spacing System

### Scale utilizzata (basata su Bootstrap)
```css
/* 0.25rem = 4px */
.spacing-1 { margin: 0.25rem; }

/* 0.5rem = 8px */
.spacing-2 { margin: 0.5rem; }

/* 0.75rem = 12px */
.spacing-3 { margin: 0.75rem; }

/* 1rem = 16px */
.spacing-4 { margin: 1rem; }

/* 1.25rem = 20px */
.spacing-5 { margin: 1.25rem; }

/* 1.5rem = 24px */
.spacing-6 { margin: 1.5rem; }
```

## 🎨 Typography System

### Font Stack
```css
body {
  font-family: "Titillium Web", -apple-system, BlinkMacSystemFont,
               "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
```

### Scala Tipografica
```css
.title-xxxlarge { font-size: 3rem; }    /* 48px */
.title-xxlarge { font-size: 2.5rem; }   /* 40px */
.title-xlarge { font-size: 2rem; }      /* 32px */
.title-large { font-size: 1.75rem; }    /* 28px */
.title-medium { font-size: 1.5rem; }    /* 24px */
.title-small { font-size: 1.25rem; }    /* 20px */
.title-xsmall { font-size: 1rem; }      /* 16px */
```

## 🚀 Performance Optimization

### CSS Performance Best Practices Implementate

1. **Selettori Efficienti**:
```css
/* ✅ Efficiente */
.component .element { }

/* ❌ Evitato */
div > ul > li > a { }
```

2. **Raggruppamento Proprietà**:
```css
.element {
  /* Layout */
  display: flex;
  position: relative;

  /* Box Model */
  width: 100%;
  margin: 1rem;
  padding: 0.5rem;

  /* Typography */
  font-size: 1rem;
  color: var(--bs-dark);

  /* Visual */
  background: white;
  border: 1px solid var(--bs-gray-300);

  /* Animation */
  transition: all 0.2s ease;
}
```

3. **CSS Custom Properties per Performance**:
```css
/* Definizione una volta, uso multiplo */
:root {
  --primary-color: #007a52;
  --transition-base: all 0.2s ease;
  --shadow-base: 0 2px 4px rgba(0,0,0,0.1);
}

.component {
  color: var(--primary-color);
  transition: var(--transition-base);
  box-shadow: var(--shadow-base);
}
```

## 🔍 Debug e Troubleshooting

### Common Debug Patterns
```css
/* Debugging Layout */
.debug * {
  outline: 1px solid red !important;
}

/* Debugging Flexbox */
.debug-flex {
  background: rgba(255, 0, 0, 0.1) !important;
}

/* Debugging Grid */
.debug-grid {
  background: rgba(0, 255, 0, 0.1) !important;
}
```

Questa architettura CSS è progettata per essere:
- **Manutenibile**: Struttura logica e commentata
- **Scalabile**: Pattern riutilizzabili e modulari
- **Performante**: Selettori ottimizzati e proprietà raggruppate
- **Responsive**: Mobile-first con breakpoint logici
- **Accessibile**: Focus state e contrasti appropriati