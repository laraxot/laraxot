---
title: "Componenti Layout"
type: concept
tags: [layout, components]
created: 2026-07-14
updated: 2026-07-14
qmd: "layout-components componenti layout"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Componenti Layout

## 📐 Struttura Base

### Container
```html
<div class="container">
  <!-- Contenuto -->
</div>

<div class="container-fluid">
  <!-- Contenuto a larghezza piena -->
</div>
```

### Grid System
```html
<div class="row">
  <div class="col-md-6">
    <!-- Colonna 1 -->
  </div>
  <div class="col-md-6">
    <!-- Colonna 2 -->
  </div>
</div>
```

## 🎯 Layout Specifici

### Header
```html
<header class="header">
  <div class="header-brand">
    <img src="logo.png" alt="Logo">
    <h1>Nome Applicazione</h1>
  </div>
  <nav class="header-nav">
    <!-- Menu -->
  </nav>
  <div class="header-actions">
    <!-- Azioni -->
  </div>
</header>
```

### Sidebar
```html
<aside class="sidebar">
  <div class="sidebar-header">
    <h2>Menu</h2>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <li class="active">
        <a href="#">
          <i class="fas fa-home"></i>
          Dashboard
        </a>
      </li>
      <!-- Altri elementi menu -->
    </ul>
  </nav>
</aside>
```

### Footer
```html
<footer class="footer">
  <div class="footer-content">
    <div class="footer-section">
      <h3>Contatti</h3>
      <!-- Contatti -->
    </div>
    <div class="footer-section">
      <h3>Link Utili</h3>
      <!-- Link -->
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2024 Nome Applicazione</p>
  </div>
</footer>
```

## 📱 Responsive

### Breakpoints
```scss
$breakpoints: (
  'xs': 0,
  'sm': 576px,
  'md': 768px,
  'lg': 992px,
  'xl': 1200px,
  'xxl': 1400px
);
```

### Media Queries
```scss
@mixin media-up($breakpoint) {
  @if map-has-key($breakpoints, $breakpoint) {
    @media (min-width: map-get($breakpoints, $breakpoint)) {
      @content;
    }
  }
}

// Utilizzo
.element {
  @include media-up('md') {
    // Stili per schermi md e superiori
  }
}
```

## 🎨 Utility

### Spacing
```html
<div class="m-3 p-3">Margine e padding</div>
<div class="mt-2 mb-4">Margine top e bottom</div>
<div class="px-4">Padding orizzontale</div>
```

### Display
```html
<div class="d-flex">Flexbox</div>
<div class="d-grid">Grid</div>
<div class="d-none d-md-block">Nascosto su mobile</div>
```

### Position
```html
<div class="position-relative">
  <div class="position-absolute top-0 end-0">
    <!-- Elemento posizionato -->
  </div>
</div>
```

## 🔗 Collegamenti
- [Componenti Base](./base-components.md)
- [Performance](./standards/performance.md)
- [Accessibilità](./standards/accessibility.md)
# Componenti Layout

## 📐 Struttura Base

### Container
```html
<div class="container">
  <!-- Contenuto -->
</div>

<div class="container-fluid">
  <!-- Contenuto a larghezza piena -->
</div>
```

### Grid System
```html
<div class="row">
  <div class="col-md-6">
    <!-- Colonna 1 -->
  </div>
  <div class="col-md-6">
    <!-- Colonna 2 -->
  </div>
</div>
```

## 🎯 Layout Specifici

### Header
```html
<header class="header">
  <div class="header-brand">
    <img src="logo.png" alt="Logo">
    <h1>Nome Applicazione</h1>
  </div>
  <nav class="header-nav">
    <!-- Menu -->
  </nav>
  <div class="header-actions">
    <!-- Azioni -->
  </div>
</header>
```

### Sidebar
```html
<aside class="sidebar">
  <div class="sidebar-header">
    <h2>Menu</h2>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <li class="active">
        <a href="#">
          <i class="fas fa-home"></i>
          Dashboard
        </a>
      </li>
      <!-- Altri elementi menu -->
    </ul>
  </nav>
</aside>
```

### Footer
```html
<footer class="footer">
  <div class="footer-content">
    <div class="footer-section">
      <h3>Contatti</h3>
      <!-- Contatti -->
    </div>
    <div class="footer-section">
      <h3>Link Utili</h3>
      <!-- Link -->
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2024 Nome Applicazione</p>
  </div>
</footer>
```

## 📱 Responsive

### Breakpoints
```scss
$breakpoints: (
  'xs': 0,
  'sm': 576px,
  'md': 768px,
  'lg': 992px,
  'xl': 1200px,
  'xxl': 1400px
);
```

### Media Queries
```scss
@mixin media-up($breakpoint) {
  @if map-has-key($breakpoints, $breakpoint) {
    @media (min-width: map-get($breakpoints, $breakpoint)) {
      @content;
    }
  }
}

// Utilizzo
.element {
  @include media-up('md') {
    // Stili per schermi md e superiori
  }
}
```

## 🎨 Utility

### Spacing
```html
<div class="m-3 p-3">Margine e padding</div>
<div class="mt-2 mb-4">Margine top e bottom</div>
<div class="px-4">Padding orizzontale</div>
```

### Display
```html
<div class="d-flex">Flexbox</div>
<div class="d-grid">Grid</div>
<div class="d-none d-md-block">Nascosto su mobile</div>
```

### Position
```html
<div class="position-relative">
  <div class="position-absolute top-0 end-0">
    <!-- Elemento posizionato -->
  </div>
</div>
```

## 🔗 Collegamenti
- [Componenti Base](./base-components.md)
- [Performance](./standards/performance.md)
- [Accessibilità](./standards/accessibility.md)
