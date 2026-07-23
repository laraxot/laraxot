# Layout Architecture - DRY + KISS

> **Perché `[slug].blade.php` NON deve contenere header, footer e skiplink**

## 📋 Panoramica

Questo documento spiega l'architettura dei layout nel tema Sixteen, seguendo i principi **DRY** (Don't Repeat Yourself) e **KISS** (Keep It Simple, Stupid).

---

## 🎯 Obiettivo

Separare chiaramente:
- **Layout (struttura comune)**: Header, Footer, Skip links, HTML boilerplate
- **Pagine (contenuto specifico)**: Solo il contenuto unico di ogni pagina

---

## 🏗️ Architettura Layout

### Gerarchia Layout

```
main.blade.php (Base layout - HTML boilerplate)
    ↓
app.blade.php (Public layout - Header, Footer, Skip links)
    ↓
[slug].blade.php (Page template - Solo contenuto specifico)
```

---

## 📄 File Layout

### 1. `main.blade.php` - Base Layout

**File:** `laravel/Themes/Sixteen/resources/views/layouts/main.blade.php`

**Scopo:** HTML boilerplate di base

**Contenuto:**
- `<!DOCTYPE html>`
- `<head>` con meta tag e Vite assets
- `<body>` con `@yield('body')`
- Stack per scripts

**Perché esiste:**
- Fornisce struttura HTML minima
- Carica assets (CSS/JS) una volta sola
- Permette ad altri layout di estenderlo

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
</head>
<body>
    @yield('body')
</body>
</html>
```

---

### 2. `app.blade.php` - Public Layout

**File:** `laravel/Themes/Sixteen/resources/views/layouts/app.blade.php`

**Scopo:** Layout pubblico per tutte le pagine front-end

**Contenuto:**
- Estende `main.blade.php`
- Skip links (accessibilità WCAG AA)
- Header (`<x-section slug="header" />`)
- Main container (`<main id="main-container">`)
- Footer (`<x-section slug="footer" tpl="full" />`)

**Perché esiste:**
- **DRY:** Header, Footer e Skip links sono scritti UNA volta sola
- **KISS:** Tutte le pagine pubbliche hanno stessa struttura
- **Manutenibilità:** Modifica header/footer in un solo punto

```blade
@extends('pub_theme::layouts.main')

@section('body')
    {{-- Skip Links --}}
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
    </div>

    {{-- Header --}}
    <x-section slug="header" />

    {{-- Main Content --}}
    <main id="main-container">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-section slug="footer" tpl="full" />
@endsection
```

---

### 3. `[slug].blade.php` - Page Template

**File:** `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`

**Scopo:** Template dinamico per TUTTE le pagine

**Contenuto:**
- Logica Folio + Volt
- Solo contenuto specifico della pagina (`<x-page>`)

**Perché NON ha header/footer:**
- ✅ **DRY:** Header/footer sono già in `app.blade.php`
- ✅ **KISS:** Questo file gestisce SOLO il routing e il contenuto
- ✅ **Separazione compiti:** Layout ≠ Contenuto

```blade
<?php
name('tests.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $slug = '';
    public string $pageSlug = '';
    public array $data = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests.'.$slug;
        $this->data = ['slug' => $slug];
    }
};
?>

<x-layouts.app>
    @volt('tests.view')
        {{-- SOLO contenuto specifico --}}
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>
```

---

## ❌ ERRORE: Elementi Strutturali in `[slug].blade.php`

### Cosa NON fare

```blade
{{-- ❌ SBAGLIATO: Header/Footer in [slug].blade.php --}}
<x-layouts.app>
    @volt('tests.view')
    <div>
        {{-- Skip Links --}}
        <div class="skiplink">
            <a href="#main-container">Vai ai contenuti</a>
        </div>

        {{-- Header --}}
        <x-section slug="header" />

        {{-- Main Content --}}
        <main id="main-container">
            <x-page side="content" :slug="$pageSlug" :data="$data" />
        </main>

        {{-- Footer --}}
        <x-section slug="footer" />
    </div>
    @endvolt
</x-layouts.app>
```

### Perché è Sbagliato

1. **Viola DRY:** Ripeti header/footer in ogni pagina
2. **Viola KISS:** Complessità inutile nel page template
3. **Manutenibilità:** Se modifichi header, devi modificare 38 file
4. **Separazione compiti:** Page template deve gestire SOLO routing e contenuto

---

## ✅ CORRETTO: Solo Contenuto in `[slug].blade.php`

### Cosa Fare

```blade
{{-- ✅ CORRETTO: Solo contenuto specifico --}}
<x-layouts.app>
    @volt('tests.view')
        {{-- SOLO contenuto della pagina --}}
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>
```

### Perché è Corretto

1. **DRY:** Header/footer scritti UNA volta in `app.blade.php`
2. **KISS:** Page template semplice e focalizzato
3. **Manutenibilità:** Modifica header/footer in un solo file
4. **Separazione compiti:** Ogni file fa UNA cosa sola

---

## 🎯 Vantaggi Architettura

### 1. DRY (Don't Repeat Yourself)

**Prima (SBAGLIATO):**
```
[slug].blade.php (38 pagine)
├── Skip links (ripetuto 38 volte)
├── Header (ripetuto 38 volte)
└── Footer (ripetuto 38 volte)

Totale: 114 duplicazioni
```

**Dopo (CORRETTO):**
```
app.blade.php
├── Skip links (1 volta)
├── Header (1 volta)
└── Footer (1 volta)

[slug].blade.php (38 pagine)
└── Solo contenuto specifico

Totale: 0 duplicazioni
```

---

### 2. KISS (Keep It Simple, Stupid)

**Prima (COMPLESSO):**
```blade
[slug].blade.php
├── Logica Folio + Volt
├── Skip links
├── Header
├── Main content
└── Footer

Responsabilità: 5 (TROPPÉ!)
```

**Dopo (SEMPLICE):**
```blade
[slug].blade.php
└── Logica Folio + Volt + Contenuto

Responsabilità: 1 (FOCALIZZATO!)
```

---

### 3. Manutenibilità

**Scenario:** Devi modificare l'header (aggiungi social link)

**Prima (38 modifiche):**
```bash
# Devi modificare 38 file
git checkout -b update-header
# Modifica [slug].blade.php × 38
# Commit 38 file
# Rischio: Dimentichi qualche file, crei inconsistenze
```

**Dopo (1 modifica):**
```bash
# Modifichi 1 file solo
git checkout -b update-header
# Modifica app.blade.php
# Commit 1 file
# Vantaggio: Tutte le 38 pagine aggiornate automaticamente
```

---

### 4. Separazione Compiti

| File | Responsabilità | Cosa Gestisce |
|------|----------------|---------------|
| `main.blade.php` | HTML Boilerplate | `<!DOCTYPE>`, `<head>`, assets |
| `app.blade.php` | Layout Pubblico | Header, Footer, Skip links |
| `[slug].blade.php` | Routing + Contenuto | Logica Folio, contenuto pagina |

---

## 🔄 Flusso Rendering

### Sequenza Corretta

```
1. Request: /it/tests/homepage
   ↓
2. Folio: routes to [slug].blade.php
   ↓
3. [slug].blade.php: mount() + Volt logic
   ↓
4. Extends: <x-layouts.app> (app.blade.php)
   ↓
5. app.blade.php: extends <x-layouts.main> (main.blade.php)
   ↓
6. main.blade.php: renders HTML boilerplate
   ↓
7. app.blade.php: adds Skip links, Header, Footer
   ↓
8. [slug].blade.php: adds page-specific content
   ↓
9. Response: HTML completo al browser
```

---

## 📊 Confronto Architetture

### Architettura SBAGLIATA

```
┌─────────────────────────────────────────┐
│  [slug].blade.php (38 files)            │
│  ┌─────────────────────────────────┐    │
│  │ Skip Links (×38)                │    │
│  │ Header (×38)                    │    │
│  │ Main Content                    │    │
│  │ Footer (×38)                    │    │
│  └─────────────────────────────────┘    │
└─────────────────────────────────────────┘

Problemi:
❌ 114 duplicazioni
❌ Manutenibilità scarsa
❌ Rischio inconsistenze
```

---

### Architettura CORRETTA

```
┌─────────────────────────────────────────┐
│  main.blade.php (1 file)                │
│  ┌─────────────────────────────────┐    │
│  │ HTML Boilerplate                │    │
│  │ Vite Assets                     │    │
│  └─────────────────────────────────┘    │
└─────────────────────────────────────────┘
              ↑
              ↑ extends
              ↓
┌─────────────────────────────────────────┐
│  app.blade.php (1 file)                 │
│  ┌─────────────────────────────────┐    │
│  │ Skip Links                      │    │
│  │ Header                          │    │
│  │ Footer                          │    │
│  └─────────────────────────────────┘    │
└─────────────────────────────────────────┘
              ↑
              ↑ extends
              ↓
┌─────────────────────────────────────────┐
│  [slug].blade.php (38 files)            │
│  ┌─────────────────────────────────┐    │
│  │ Page-specific Content ONLY      │    │
│  └─────────────────────────────────┘    │
└─────────────────────────────────────────┘

Vantaggi:
✅ 0 duplicazioni
✅ Manutenibilità ottima
✅ Consistenza garantita
```

---

## 🎯 Principi Applicati

### 1. Single Responsibility Principle (SOLID)

Ogni layout ha UNA responsabilità sola:

- `main.blade.php`: HTML boilerplate
- `app.blade.php`: Layout pubblico (header/footer)
- `[slug].blade.php`: Routing + contenuto pagina

### 2. DRY (Don't Repeat Yourself)

Header/footer scritti UNA volta sola in `app.blade.php`, usati da 38 pagine.

### 3. KISS (Keep It Simple, Stupid)

Ogni file fa UNA cosa sola, nel modo più semplice possibile.

### 4. Separation of Concerns

- **Layout:** Struttura comune (header, footer)
- **Page Template:** Routing e contenuto specifico
- **Components:** Blocchi riutilizzabili (dentro `<x-page>`)

---

## 📚 Documenti Correlati

### Documentazione Interna

- [Replikate.txt](./prompts/replikate.txt) - Guida operativa
- [Product Brief](./design-comuni/product-brief.md) - Panoramica progetto
- [Pages Census](./design-comuni/pages-census.md) - Censimento 38 pagine
- [Blocks System](./design-comuni/blocks/00-index.md) - Blocchi universali

### Documenti Esterni

- [Laravel Blade Components](https://laravel.com/docs/blade#layouts)
- [Laravel Folio](https://laravel.com/docs/folio)
- [Livewire Volt](https://livewire.laravel.com/docs/volt)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)

---

## ✅ Checklist Verifica

### Per Nuovi Layout

```markdown
- [ ] Estende layout corretto (main o app)
- [ ] Ha UNA responsabilità sola
- [ ] Non duplica elementi di altri layout
- [ ] Usa @yield o @slot per contenuto dinamico
- [ ] Documentato con link bidirezionali
```

### Per Nuove Pagine

```markdown
- [ ] Usa <x-layouts.app> (NON header/footer inline)
- [ ] Contiene SOLO logica Folio + Volt + contenuto
- [ ] NON ha skip links, header, footer
- [ ] Estende layout corretto
- [ ] Documentata con link bidirezionali
```

---

## 🎓 Lezioni Apprese

### Architettura

1. **Layout gerarchici:** `main` → `app` → `page`
2. **DRY:** Elementi comuni UNA volta sola
3. **KISS:** Ogni file fa UNA cosa sola

### Manutenibilità

1. **Modifica singola:** Cambi header? 1 file solo
2. **Consistenza:** Tutte le pagine uguali
3. **Testing:** Testi layout una volta, valido per tutte

### Separazione Compiti

1. **Layout:** Struttura comune
2. **Page Template:** Routing + contenuto
3. **Components:** Blocchi riutilizzabili

---

**Data Creazione:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ Completato  
**Aggiornato:** Dopo correzione `[slug].blade.php`

---

*"UNO [slug].blade.php per TUTTE le pagine"*  
*"Header/Footer in app.blade.php (UNA volta sola)"*  
*"Solo contenuto in [slug].blade.php"*  
*"DRY + KISS + Separation of Concerns"*
