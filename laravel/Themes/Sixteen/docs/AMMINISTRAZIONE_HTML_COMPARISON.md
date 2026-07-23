# Amministrazione Page - HTML Body Comparison

> **Confronto HTML tra originale Design Comuni e replica FixCity**

## 📋 Panoramica

**Data:** 2026-04-01  
**Pagina:** Amministrazione  
**Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/amministrazione.html  
**Replica:** http://127.0.0.1:8000/it/tests/amministrazione  
**Stato:** 🔴 **DIFFERENZE CRITICHE RILEVATE**

---

## 🔍 Differenze Strutturali

### 1. Skip Links

**Originale:**
```html
<a class="it-skip-link" href="#main-content">Vai ai contenuti</a>
<a class="it-skip-link" href="#footer">Vai al footer</a>
```

**Replica:**
```html
<a class="skiplink" href="#main-content">Vai al contenuto principale</a>
```

**Differenze:**
- ❌ Classe: `it-skip-link` vs `skiplink`
- ❌ Testo: "Vai ai contenuti" vs "Vai al contenuto principale"
- ❌ Secondo link mancante (footer)

**Correzione Richiesta:**
```blade
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>
```

---

### 2. Header Wrapper

**Originale:**
```html
<div class="it-header-wrapper">
    <div class="it-header-slim-wrapper">
        ...
    </div>
    <div class="it-header-main">
        ...
    </div>
    <nav class="navbar navbar-expand-lg it-main-nav">
        ...
    </nav>
</div>
```

**Replica:**
```html
<header class="it-header">
    <div class="it-header-slim">
        ...
    </div>
    <div class="it-nav-wrapper">
        <div class="it-header-center">
            ...
        </div>
        <nav class="it-header-nav">
            ...
        </nav>
    </div>
</header>
```

**Differenze:**
- ❌ Tag: `<div>` vs `<header>` (entrambi accettabili)
- ❌ Classe wrapper: `it-header-wrapper` vs `it-header`
- ❌ Slim wrapper: `it-header-slim-wrapper` vs `it-header-slim`
- ❌ Main wrapper: `it-header-main` vs `it-header-center`
- ❌ Nav: `navbar navbar-expand-lg it-main-nav` vs `it-header-nav`

**Correzione Richiesta:**
```blade
<div class="it-header-wrapper">
    <div class="it-header-slim-wrapper">
        ...
    </div>
    <div class="it-header-main-wrapper">
        ...
    </div>
    <nav class="navbar navbar-expand-lg it-main-nav">
        ...
    </nav>
</div>
```

---

### 3. Main Container

**Originale:**
```html
<main id="main-content" class="it-main-container">
    <!-- Breadcrumb -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="it-breadcrumb">
                    <ol>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Amministrazione</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                ...
            </div>
        </div>
    </div>
</main>
```

**Replica:**
```html
<main id="main-content" class="it-main">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Breadcrumb -->
                <nav class="breadcrumb" aria-label="Percorso di navigazione">
                    <ol class="breadcrumb-list">
                        ...
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8">
                <!-- Content -->
            </div>
            <div class="col-12 col-lg-4">
                <!-- Sidebar -->
            </div>
        </div>
    </div>
</main>
```

**Differenze:**
- ❌ Classe main: `it-main-container` vs `it-main`
- ❌ Breadcrumb: struttura diversa
- ❌ Layout: replica ha sidebar (col-lg-8 + col-lg-4), originale no

**Correzione Richiesta:**
```blade
<main id="main-container" class="it-main-container">
    <!-- Breadcrumb in container separato -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="it-breadcrumb">
                    <ol>
                        <li><a href="/">Home</a></li>
                        <li><a href="/it/tests/amministrazione">Amministrazione</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Content in container separato -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-page side="content" :slug="$pageSlug" :data="$data" />
            </div>
        </div>
    </div>
</main>
```

---

### 4. Breadcrumb

**Originale:**
```html
<nav class="it-breadcrumb">
    <ol>
        <li><a href="#">Home</a></li>
        <li><a href="#">Amministrazione</a></li>
    </ol>
</nav>
```

**Replica:**
```html
<nav class="breadcrumb" aria-label="Percorso di navigazione">
    <ol class="breadcrumb-list">
        <li><a href="/">Home</a></li>
        <li><span>Amministrazione</span></li>
    </ol>
</nav>
```

**Differenze:**
- ❌ Classe nav: `it-breadcrumb` vs `breadcrumb`
- ❌ Classe ol: nessuna vs `breadcrumb-list`
- ❌ Secondo elemento: `<a>` vs `<span>`

**Correzione Richiesta:**
```blade
<nav class="it-breadcrumb">
    <ol>
        <li><a href="/">Home</a></li>
        <li><a href="/it/tests/amministrazione">Amministrazione</a></li>
    </ol>
</nav>
```

---

### 5. Footer

**Originale:**
```html
<footer class="it-footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4">
                <h3>NOME DEL COMUNE</h3>
                <div class="footer-links">
                    ...
                </div>
            </div>
            ...
        </div>
        <div class="row">
            <div class="col-12">
                <div class="it-footer-bottom">
                    ...
                </div>
            </div>
        </div>
    </div>
</footer>
```

**Replica:**
```html
<footer class="it-footer">
    <div class="it-footer-main">
        <div class="container">
            ...
        </div>
    </div>
    <div class="it-footer-small-prints">
        <div class="container">
            ...
        </div>
    </div>
</footer>
```

**Differenze:**
- ❌ Struttura: replica ha wrapper aggiuntivi (`it-footer-main`, `it-footer-small-prints`)
- ✅ Classe footer: `it-footer` (corretta)

**Correzione Richiesta:**
```blade
<footer class="it-footer">
    <div class="container">
        <div class="row">
            <!-- Footer columns -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="it-footer-bottom">
                    ...
                </div>
            </div>
        </div>
    </div>
</footer>
```

---

## 📊 Tabella Comparativa Completa

| Elemento | Originale | Replica | Stato |
|----------|-----------|---------|-------|
| **Skip Links** |
| Classe | `it-skip-link` | `skiplink` | ❌ |
| Link 1 | "Vai ai contenuti" | "Vai al contenuto principale" | ❌ |
| Link 2 | ✅ Presente | ❌ Mancante | ❌ |
| **Header** |
| Wrapper | `it-header-wrapper` | `it-header` | ❌ |
| Slim | `it-header-slim-wrapper` | `it-header-slim` | ❌ |
| Main | `it-header-main` | `it-header-center` | ❌ |
| Nav | `navbar navbar-expand-lg it-main-nav` | `it-header-nav` | ❌ |
| **Main** |
| ID | `main-content` | `main-content` | ✅ |
| Classe | `it-main-container` | `it-main` | ❌ |
| **Breadcrumb** |
| Nav classe | `it-breadcrumb` | `breadcrumb` | ❌ |
| OL classe | nessuna | `breadcrumb-list` | ❌ |
| Link attivo | `<a>` | `<span>` | ❌ |
| **Footer** |
| Classe | `it-footer` | `it-footer` | ✅ |
| Struttura | Semplice | Wrapper aggiuntivi | ❌ |

---

## ✅ Correzioni da Applicare

### 1. app.blade.php

```blade
{{-- Skip Links - EXACT Bootstrap Italia Structure --}}
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>

{{-- Header Wrapper - EXACT Bootstrap Italia Structure --}}
<div class="it-header-wrapper">
    <x-section slug="header" />
</div>

{{-- Main Content - EXACT Bootstrap Italia Structure --}}
<main id="main-container" class="it-main-container">
    @yield('content')
</main>
```

---

### 2. amministrazione.blade.php (o [slug].blade.php)

```blade
{{-- Breadcrumb - EXACT Structure --}}
<div class="container">
    <div class="row">
        <div class="col-12">
            <nav class="it-breadcrumb">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/it/tests/amministrazione">Amministrazione</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Content - Single Column (NO sidebar) --}}
<div class="container">
    <div class="row">
        <div class="col-12">
            <x-page side="content" :slug="$pageSlug" :data="$data" />
        </div>
    </div>
</div>
```

---

### 3. footer/main.blade.php

```blade
<footer class="it-footer">
    <div class="container">
        <div class="row gy-4">
            <!-- Footer columns -->
        </div>
        <div class="row mt-5 pt-4 border-top">
            <div class="col-12">
                <div class="it-footer-bottom">
                    <!-- Bottom content -->
                </div>
            </div>
        </div>
    </div>
</footer>
```

---

## 🎯 Prossimi Passi

### Priorità Alta

1. **Correggere Skip Links** in app.blade.php
2. **Correggere Header Wrapper** classes
3. **Correggere Main Container** classe
4. **Correggere Breadcrumb** structure
5. **Correggere Footer** structure

### Priorità Media

6. Verificare tutte le pagine tests.* seguano la stessa struttura
7. Creare blocco `breadcrumb` dedicato
8. Aggiornare documentazione

---

## 📚 Documenti Correlati

- [HTML Body Comparison](./HTML_BODY_COMPARISON.md)
- [HTML Body Identity Fix](./HTML_BODY_IDENTITY_FIX.md)
- [Layout Architecture](./architecture/layout-architecture.md)

---

**Data:** 2026-04-01  
**Versione:** 1.0  
**Stato:** 🔴 **DIFFERENZE CRITICHE RILEVATE**  
**Prossimo:** Applicare correzioni strutturali

---

*"HTML dentro <body> deve essere IDENTICO"*  
*"Struttura Bootstrap Italia va seguita alla lettera"*  
*"Classi, tag, attributi - tutto deve corrispondere"*
