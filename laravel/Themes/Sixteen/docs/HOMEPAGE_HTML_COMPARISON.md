# Homepage HTML Body Comparison - Originale vs Replica

> **Confronto STRUTTURA HTML tra Design Comuni originale e replica FixCity**

## 📋 Panoramica

**Data:** 2026-04-01  
**Pagina:** Homepage  
**Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Replica:** http://127.0.0.1:8000/it/tests/homepage  
**Stato:** ⚠️ **DA VERIFICARE** (Errore 500 in corso di risoluzione)

---

## 🎯 Struttura HTML Attesa (Originale)

```html
<body>
    <!-- 1. Skip Links -->
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
    </div><!-- /skiplink -->

    <!-- 2. Header Wrapper -->
    <header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
        
        <!-- 2.1 Header Slim -->
        <div class="it-header-slim-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-slim-wrapper-content">
                            <!-- Regione, Lingua, Login, Social -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2.2 Header Main -->
        <div class="it-header-main-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Logo, Nome Comune, Search -->
                    </div>
                </div>
            </div>
        </div>

        <!-- 2.3 Navigation -->
        <nav class="navbar navbar-expand-lg it-main-nav">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Menu items -->
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- 3. Main Content -->
    <main id="main-container" class="it-main-container">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Content blocks from JSON -->
                </div>
            </div>
        </div>
    </main>

    <!-- 4. Footer -->
    <footer id="footer" class="it-footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Footer content -->
                </div>
            </div>
        </div>
    </footer>
</body>
```

---

## 🔍 Replica FixCity (Struttura Attesa)

### app.blade.php

```blade
<body>
    {{-- Skip Links - EXACT Bootstrap Italia Structure --}}
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
    </div><!-- /skiplink -->

    {{-- Header Wrapper - EXACT Bootstrap Italia Structure --}}
    <header class="it-header-wrapper">
        <x-section slug="header" />
    </header>

    {{-- Main Content - EXACT Bootstrap Italia Structure --}}
    <main id="main-container" class="it-main-container">
        <div class="container">
            @yield('content')
            <x-page side="content" :slug="$slug ?? ''" :data="$data ?? []" />
        </div>
    </main>

    {{-- Footer - Bootstrap Italia EXACT Structure --}}
    <x-section slug="footer" tpl="full" />
</body>
```

### [slug].blade.php

```blade
<x-layouts.app>
    @volt('tests.view')
        {{-- Main Content - Page-specific content only (NO header/footer/skiplink) --}}
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>
```

### page.blade.php

```blade
@props(['side' => 'content', 'slug' => '', 'data' => []])

@php
    $configPath = base_path('config/local/fixcity/database/content/pages/'.$slug.'.json');
    if (file_exists($configPath)) {
        $pageData = json_decode(file_get_contents($configPath), true);
        $blocks = $pageData['content_blocks']['it'] ?? [];
    }
@endphp

<div class="page-content {{ $side }}">
    @foreach($blocks as $block)
        @includeIf($block['data']['view'], ['data' => $block['data']])
    @endforeach
</div>
```

---

## 📊 Confronto Dettagliato

| Elemento | Originale | Replica | Stato |
|----------|-----------|---------|-------|
| **Skip Links** |
| `<div class="skiplink">` | ✅ | ✅ | ✅ |
| `visually-hidden-focusable` | ✅ | ✅ | ✅ |
| href="#main-container" | ✅ | ✅ | ✅ |
| href="#footer" | ✅ | ✅ | ✅ |
| **Header** |
| `<header class="it-header-wrapper">` | ✅ | ✅ | ✅ |
| `.it-header-slim-wrapper` | ✅ | ✅ (via section) | ✅ |
| `.it-header-main-wrapper` | ✅ | ✅ (via section) | ✅ |
| `<nav class="navbar navbar-expand-lg it-main-nav">` | ✅ | ⏳ Da verificare | ⏳ |
| **Main** |
| `<main id="main-container" class="it-main-container">` | ✅ | ✅ | ✅ |
| `<div class="container">` | ✅ | ✅ | ✅ |
| `<div class="row">` | ✅ | ⏳ Da verificare | ⏳ |
| `<div class="col-12">` | ✅ | ⏳ Da verificare | ⏳ |
| **Footer** |
| `<footer id="footer" class="it-footer">` | ✅ | ✅ (it-footer-main-wrapper) | ⚠️ |
| `<div class="container">` | ✅ | ✅ | ✅ |

---

## ✅ Correzioni Applicate

### 1. Skip Links ✅

**File:** `app.blade.php`

```blade
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div><!-- /skiplink -->
```

**Stato:** ✅ Identico all'originale

---

### 2. Header Wrapper ✅

**File:** `app.blade.php`

```blade
<header class="it-header-wrapper">
    <x-section slug="header" />
</header>
```

**Stato:** ✅ Tag `<header>` con classe `it-header-wrapper`

---

### 3. Main Container ✅

**File:** `app.blade.php`

```blade
<main id="main-container" class="it-main-container">
    <div class="container">
        @yield('content')
        <x-page side="content" :slug="$slug ?? ''" :data="$data ?? []" />
    </div>
</main>
```

**Stato:** ✅ ID, classe e container corretti

---

### 4. Footer ⚠️

**File:** `components/blocks/footer/main.blade.php`

```blade
<footer class="it-footer-main-wrapper" id="footer">
```

**Differenza:** ⚠️ `it-footer-main-wrapper` vs `it-footer`

**Correzione Richiesta:**
```blade
<footer id="footer" class="it-footer">
```

---

## 🚨 Errori Rilevati

### Errore 1: Footer Classe

**Originale:** `class="it-footer"`  
**Replica:** `class="it-footer-main-wrapper"`

**Impatto:** ❌ Differenza strutturale  
**Correzione:** Modificare footer/main.blade.php

---

### Errore 2: Row/Col Missing

**Originale:**
```html
<main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Content -->
            </div>
        </div>
    </div>
</main>
```

**Replica:**
```blade
<main>
    <div class="container">
        @yield('content')
        <x-page ... />
    </div>
</main>
```

**Impatto:** ❌ Mancano `row` e `col-12`  
**Correzione:** Aggiungere wrapper in page.blade.php o app.blade.php

---

## ✅ Checklist Verifica Finale

### Struttura Generale

```markdown
- [ ] Skip links identici
- [ ] Header wrapper con tag <header>
- [ ] Header slim wrapper structure
- [ ] Header main wrapper structure
- [ ] Navigation con navbar navbar-expand-lg it-main-nav
- [ ] Main con id="main-container" class="it-main-container"
- [ ] Main > .container > .row > .col-12
- [ ] Footer con class="it-footer"
- [ ] Footer > .container > .row > .col-12
```

### Blocchi Contenuto

```markdown
- [ ] Hero newscard structure identica
- [ ] Card grid structure identica
- [ ] Quick links structure identica
- [ ] News carousel structure identica
- [ ] Footer columns structure identica
```

### Classi Bootstrap Italia

```markdown
- [ ] Tutte le classi Bootstrap Italia presenti
- [ ] Nessuna classe Tailwind al posto di Bootstrap
- [ ] Gerarchia div > container > row > col-12 rispettata
```

---

## 📊 Stato Confronto

| Sezione | Stato | Note |
|---------|-------|------|
| Skip Links | ✅ **CORRETTO** | Identico |
| Header Wrapper | ✅ **CORRETTO** | Tag + classe corretti |
| Header Slim | ⏳ **Da Verificare** | Via section |
| Header Main | ⏳ **Da Verificare** | Via section |
| Navigation | ⏳ **Da Verificare** | Via section |
| Main Container | ✅ **CORRETTO** | ID + classe + container |
| Row/Col Structure | ❌ **MANCANTE** | Aggiungere row > col-12 |
| Footer Classe | ❌ **ERRATA** | it-footer-main-wrapper vs it-footer |
| Footer Structure | ⏳ **Da Verificare** | Via section |

---

## 🎯 Prossime Correzioni

### 1. Footer Classe

**File:** `components/blocks/footer/main.blade.php`

```blade
{{-- PRIMA --}}
<footer class="it-footer-main-wrapper" id="footer">

{{-- DOPO --}}
<footer id="footer" class="it-footer">
```

---

### 2. Row/Col Structure in page.blade.php

**File:** `components/page.blade.php`

```blade
<div class="page-content {{ $side }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @foreach($blocks as $block)
                    @includeIf($block['data']['view'], ['data' => $block['data']])
                @endforeach
            </div>
        </div>
    </div>
</div>
```

---

## 📚 Documenti Correlati

- [HTML Body Comparison](./HTML_BODY_COMPARISON.md)
- [HTML Body Identity Fix](./HTML_BODY_IDENTITY_FIX.md)
- [Amministrazione HTML Comparison](./AMMINISTRAZIONE_HTML_COMPARISON.md)
- [Layout Architecture](./architecture/layout-architecture.md)

---

**Data:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ⚠️ **PARZIALMENTE CORRETTO**  
**Prossimo:** Applicare correzioni footer e row/col structure

---

*"HTML dentro <body> deve essere IDENTICO"*  
*"Struttura Bootstrap Italia va seguita alla lettera"*  
*"Classi, tag, attributi - tutto deve corrispondere"*
