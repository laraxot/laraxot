# HTML Body Comparison - Design Comuni vs FixCity

> **Obiettivo:** Rendere l'HTML dentro `<body>` (esclusi script) IDENTICO tra originale e replica

## 📋 Panoramica

**Data:** 2026-04-01  
**Stato:** 🔄 In Analisi  
**Tipo:** Confronto strutturale HTML

---

## 🎯 Pagina Analizzata: Homepage

### URL di Riferimento

- **Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
- **Replica:** http://127.0.0.1:8000/it/tests/homepage

---

## 📊 Struttura HTML Attesa

### Originale Design Comuni

```html
<body>
    <!-- 1. Skip Links -->
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
    </div>

    <!-- 2. Header Wrapper -->
    <header class="it-header-wrapper">
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
        <div class="it-nav-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="it-nav-list">
                            <!-- Menu items -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. Main Content -->
    <main id="main-container">
        <div class="container">
            <!-- Content blocks from JSON -->
        </div>
    </main>

    <!-- 4. Footer -->
    <footer id="footer" class="it-footer">
        <div class="container">
            <!-- Footer columns -->
        </div>
    </footer>
</body>
```

---

## 🔍 Elementi da Verificare

### 1. Skip Links

**Originale:**
```html
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>
```

**Replica (app.blade.php):**
```blade
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>
```

**Stato:** ✅ **IDENTICO**

---

### 2. Header

**Originale:**
```html
<header class="it-header-wrapper">
    <div class="it-header-slim-wrapper">
        ...
    </div>
    <div class="it-header-main-wrapper">
        ...
    </div>
    <div class="it-nav-wrapper">
        ...
    </div>
</header>
```

**Replica (header/slim.blade.php + header/main.blade.php):**
```blade
<!-- Header Slim -->
<div class="it-header-slim-wrapper">
    ...
</div>

<!-- Header Main -->
<div class="it-header-main-wrapper">
    ...
</div>

<!-- Navigation -->
<div class="it-nav-wrapper">
    ...
</div>
```

**Differenza:** ❌ Manca tag `<header>` wrapper

**Correzione Richiesta:**
```blade
{{-- app.blade.php --}}
<header class="it-header-wrapper">
    <x-section slug="header" />
</header>
```

---

### 3. Main Content

**Originale:**
```html
<main id="main-container">
    <div class="container">
        <!-- Content -->
    </div>
</main>
```

**Replica (app.blade.php):**
```blade
<main id="main-container">
    @yield('content')
</main>
```

**Differenza:** ❌ Manca `<div class="container">`

**Correzione Richiesta:**
```blade
<main id="main-container">
    <div class="container">
        @yield('content')
    </div>
</main>
```

---

### 4. Footer

**Originale:**
```html
<footer id="footer" class="it-footer">
    <div class="container">
        <!-- Footer content -->
    </div>
</footer>
```

**Replica (footer/main.blade.php):**
```blade
<footer id="footer">
    <div class="container">
        <!-- Footer content -->
    </div>
</footer>
```

**Differenza:** ❌ Manca classe `it-footer`

**Correzione Richiesta:**
```blade
<footer id="footer" class="it-footer">
    ...
</footer>
```

---

## ✅ Checklist Correzioni

### app.blade.php

```markdown
- [ ] Aggiungere tag <header class="it-header-wrapper"> wrapper
- [ ] Aggiungere <div class="container"> dentro <main>
- [ ] Verificare che footer abbia class="it-footer"
```

### Blocchi Individuali

```markdown
## Header Slim
- [ ] Verificare struttura: .it-header-slim-wrapper > .container > .row > .col-12
- [ ] Verificare classi Bootstrap Italia

## Header Main
- [ ] Verificare struttura: .it-header-main-wrapper > .container > .row > .col-12
- [ ] Verificare classi Bootstrap Italia

## Navigation
- [ ] Verificare struttura: .it-nav-wrapper > .container > .row > .col-12
- [ ] Verificare classi Bootstrap Italia
```

---

## 📊 Confronto Dettagliato

### Struttura Generale

| Elemento | Originale | Replica | Stato |
|----------|-----------|---------|-------|
| `<!DOCTYPE html>` | ✅ | ✅ | ✅ |
| `<html lang="it">` | ✅ | ⚠️ (en) | ⚠️ |
| `<body>` | ✅ | ✅ | ✅ |
| Skiplink | ✅ | ✅ | ✅ |
| `<header class="it-header-wrapper">` | ✅ | ❌ | ❌ |
| `.it-header-slim-wrapper` | ✅ | ✅ | ✅ |
| `.it-header-main-wrapper` | ✅ | ✅ | ✅ |
| `.it-nav-wrapper` | ✅ | ✅ | ✅ |
| `<main id="main-container">` | ✅ | ✅ | ✅ |
| `<main> .container` | ✅ | ❌ | ❌ |
| `<footer id="footer" class="it-footer">` | ✅ | ⚠️ (manca it-footer) | ⚠️ |

---

## 🎯 Prossimi Passi

### 1. Correggere app.blade.php

```blade
@extends('pub_theme::layouts.main')

@section('body')
    {{-- Skip Links --}}
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
    </div>

    {{-- Header Wrapper --}}
    <header class="it-header-wrapper">
        <x-section slug="header" />
    </header>

    {{-- Main Content --}}
    <main id="main-container">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <x-section slug="footer" tpl="full" />
@endsection
```

---

### 2. Verificare Singoli Blocchi

Ogni blocco (header-slim, header-main, navigation, footer) deve avere:
- ✅ Stesse classi Bootstrap Italia
- ✅ Stessa struttura gerarchica
- ✅ Stessi attributi ARIA

---

### 3. Test di Conformità

```bash
# Scarica HTML originale e replica
curl -s "https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html" > /tmp/original.html
curl -s "http://127.0.0.1:8000/it/tests/homepage" > /tmp/replica.html

# Confronta struttura (esclusi script e contenuti dinamici)
diff <(grep -E '<(body|header|main|footer|div|nav|section)' /tmp/original.html) \
     <(grep -E '<(body|header|main|footer|div|nav|section)' /tmp/replica.html)
```

---

## 📚 Documenti Correlati

- [Layout Architecture](./architecture/layout-architecture.md)
- [Blocks Implementation](./BLOCKS_IMPLEMENTATION.md)
- [Homepage 404 Fix](./HOMEPAGE_404_FIX.md)
- [Folio Mount Error Fix](./FOLIO_MOUNT_ERROR_FIX.md)

---

**Data:** 2026-04-01  
**Versione:** 1.0  
**Stato:** 🔄 **In Analisi**

---

*"HTML dentro <body> deve essere IDENTICO"*  
*"Stesse classi, stessa struttura, stessi attributi"*  
*"DRY + KISS + Bootstrap Italia Compliance"*
