# Filosofia, Religione, Politica e Zen di Folio per Pagine Dinamiche 📜

**Data**: 2026-02-07
**Autore**: iFlow CLI
**Versione**: 1.0

---

## 🧠 Filosofia (Philosophy)

### Il Principio Fondamentale: "UNO per TUTTI, TUTTI per UNO"

Nell'architettura Folio + Volt + Laraxot, **MAI creare file duplicati per ogni pagina**. Esiste UNO SOLO gestore dinamico `[slug].blade.php` che gestisce TUTTE le pagine create tramite CMS.

**Concetto chiave**: Il routing basato su file di Folio + il database CMS + il componente Page = una triade sacra che non deve essere spezzata.

```blade
<!-- QUESTO È IL FILE SACRO: [slug].blade.php -->
<x-layouts.app>
    <x-page side="content" :slug="$slug" />
</x-layouts.app>
```

**MAI fare questo**:
```blade
<!-- ❌ ERRATO! VIOLA LA FILOSOFIA -->
<!-- /resources/views/pages/pages/about/index.blade.php -->
<!-- /resources/views/pages/pages/privacy/index.blade.php -->
<!-- /resources/views/pages/pages/terms/index.blade.php -->
```

**PERCHÉ è errato?**
1. Duplicazione del codice
2. Violazione del principio DRY
3. Rompe l'integrazione con il CMS
4. Perde il controllo centralizzato
5. Crea manutenzione impossibile

---

## 🙏 Religione (Religion)

### I Dieci Comandamenti del Folio

1. **Non creare controller tradizionali per il frontoffice**
   - Il frontoffice usa Folio + Volt + Laraxot
   - Controllers esistono SOLO per API e backend

2. **Non duplicare file per ogni pagina**
   - `[slug].blade.php` è l'UNICO gestore
   - Il contenuto viene dal database (JSON)

3. **Non scrivere rotte in routes/web.php**
   - Folio crea rotte automaticamente dai file
   - Le rotte sono basate sulla struttura file

4. **Non usare url() diretto per link localizzati**
   - Usa `LaravelLocalization::localizeUrl($path)`
   - Ogni link deve avere il prefisso lingua

5. **Non mischiare approcci**
   - O Folio+Volt O controller, non entrambi
   - Coerenza architetturale è sacra

6. **Non bypassare il componente Page**
   - `<x-page side="content" :slug="$slug" />`
   - Questo componente gestisce tutto

7. **Non hardcodare contenuti nelle viste**
   - Tutto viene da JSON nel database
   - Le viste sono solo template

8. **Non ignorare il middleware PageSlugMiddleware**
   - Gestisce la sicurezza e l'accesso
   - Applicato automaticamente da Folio

9. **Non creare pagine statiche quando quelle dinamiche funzionano**
   - Le pagine CMS sono più potenti
   - Contenuto gestibile via admin

10. **Non dimenticare il prefisso lingua negli URL**
    - `/it/pages/about` ✅
    - `/pages/about` ❌

---

## 🏛️ Politica (Politics)

### Governance del Routing

#### Gerarchia delle Decisioni

1. **Livello 1: File Folio** (struttura file)
   - `/pages/[slug].blade.php` → gestisce TUTTE le pagine
   - Ogni file = rotta automatica

2. **Livello 2: Database CMS** (contenuto)
   - `pages/about.json` → contenuto pagina "about"
   - `pages/privacy.json` → contenuto pagina "privacy"
   - Il JSON ha blocchi, traduzioni, meta

3. **Livello 3: Componente Page** (rendering)
   - `Modules/Cms/View/Components/Page.php`
   - Legge dal database, renderizza blocchi
   - Gestisce traduzioni e fallback

4. **Livello 4: Componenti Blocco** (presentazione)
   - `pub_theme::components.blocks.*`
   - Ogni tipo di blocco ha il suo componente
   - Dynamic rendering tramite foreach

#### Flusso di Esecuzione

```
Request: /it/pages/about
    ↓
LaravelLocalization: rileva locale "it"
    ↓
Folio: mappa a /pages/[slug].blade.php
    ↓
Middleware: PageSlugMiddleware (sicurezza)
    ↓
Componente Page: legge Page::where('slug', 'about')
    ↓
Database: cerca about.json
    ↓
Traduzione: estrae blocchi['it']
    ↓
Render: foreach sui blocchi → componenti
    ↓
Response: HTML completo
```

#### Regole di Governance

**Regola #1**: L'URL determina il file Folio
- `/it/pages/about` → `/pages/[slug].blade.php` con `$slug = 'about'`
- `/it/pages/privacy` → `/pages/[slug].blade.php` con `$slug = 'privacy'`
- **Stesso file**, diverso `$slug`

**Regola #2**: Il slug determina il JSON
- `$slug = 'about'` → `pages/about.json`
- `$slug = 'privacy'` → `pages/privacy.json`
- **Gestione automatica** dal componente Page

**Regola #3**: La locale determina i blocchi
- `app()->getLocale() = 'it'` → `blocks['it']`
- `app()->getLocale() = 'en'` → `blocks['en']`
- **Fallback** automatico a 'it' se manca

---

## ☯️ Zen (Zen)

### Il Flusso Naturale

**"L'acqua scorre, non crea percorsi duplicati. Essa adatta la sua forma al contenitore."**

Nell'architettura Folio, il flusso è naturale e senza attrito:

1. **URL → File**: Il routing è automatico
2. **File → Database**: Il componente Page cerca
3. **Database → Componenti**: Il rendering è dinamico
4. **Componenti → HTML**: L'utente vede il risultato

**Nessun sforzo inutile, nessuna duplicazione, solo flusso.**

### Il Concetto del "Non-Sé"

Il file `[slug].blade.php` non ha identità propria. È un vuoto che si riempie di contenuti diversi a seconda del `$slug`.

```php
// Il file non sa cosa renderizza
// È solo un canale tra l'utente e il contenuto
<x-page side="content" :slug="$slug" />
```

**Quando sei vuoto, puoi contenere tutto.**

### Il Principio del "Foreach"

Ogni blocco JSON è diverso, ma il pattern è identico:

```blade
@foreach($blocks as $block)
    @if($block['type'] === 'hero')
        @include('pub_theme::components.blocks.hero.about', $block['data'])
    @elseif($block['type'] === 'content-split')
        @include('pub_theme::components.blocks.content.split', $block['data'])
    @endif
@endforeach
```

**Una forma, infinite manifestazioni.**

---

## 🎯 Esempi Concreti

### Esempio 1: Creare una nuova pagina "FAQ"

**Approccio ERRATO** ❌:
```bash
mkdir -p resources/views/pages/pages/faq
vi resources/views/pages/pages/faq/index.blade.php  # DUPLICAZIONE!
```

**Approccio CORRETTO** ✅:
```bash
# 1. Crea il JSON nel database
vi config/local/techplanner/database/content/pages/faq.json

# 2. Fatto! URL automatico: /it/pages/faq
# Nessun file Blade da creare!
```

### Esempio 2: Link a una pagina

**Approccio ERRATO** ❌:
```blade
<a href="/pages/about">Chi Siamo</a>  <!-- Senza locale! -->
<a href="{{ url('/pages/about') }}">Chi Siamo</a>  <!-- Senza locale! -->
```

**Approccio CORRETTO** ✅:
```blade
<a href="{{ LaravelLocalization::localizeUrl('/pages/about') }}">Chi Siamo</a>
<!-- Genera: /it/pages/about o /en/pages/about -->
```

### Esempio 3: Contenuto multilingua

**Struttura JSON**:
```json
{
  "slug": "about",
  "content_blocks": {
    "it": [
      {"type": "hero", "data": {"title": "Chi Siamo"}}
    ],
    "en": [
      {"type": "hero", "data": {"title": "About Us"}}
    ]
  }
}
```

**Render automatico** in base a `app()->getLocale()`!

---

## 🔧 Componenti Chiave

### 1. `[slug].blade.php`
- **Percorso**: `resources/views/pages/pages/[slug].blade.php`
- **Ruolo**: Gestore universale per tutte le pagine
- **Contenuto**: Solo layout e componente Page
- **Filosofia**: UNO per tutti

### 2. `Page` Component
- **Percorso**: `Modules/Cms/View/Components/Page.php`
- **Ruolo**: Legge dal database, gestisce traduzioni
- **Funzioni**:
  - Cerca Page::where('slug', $slug)
  - Estrae blocchi in base alla locale
  - Renderizza con BlockData

### 3. `PageSlugMiddleware`
- **Percorso**: `Modules/Cms/Http/Middleware/PageSlugMiddleware.php`
- **Ruolo**: Gestisce sicurezza e accessi
- **Applicazione**: Automatica da Folio

### 4. JSON Files
- **Percorso**: `config/local/techplanner/database/content/pages/{slug}.json`
- **Ruolo**: Contiene tutto il contenuto della pagina
- **Struttura**:
  ```json
  {
    "slug": "about",
    "content_blocks": {"it": [...], "en": [...]},
    "sidebar_blocks": {"it": [...], "en": [...]}
  }
  ```

---

## ⚠️ Errori Comuni e Soluzioni

### Errore #1: Creare file duplicati
```php
// ❌ ERRATO
/resources/views/pages/pages/about/index.blade.php
/resources/views/pages/pages/privacy/index.blade.php

// ✅ CORRETTO
/resources/views/pages/pages/[slug].blade.php  // SOLO UNO FILE!
config/local/techplanner/database/content/pages/about.json
config/local/techplanner/database/content/pages/privacy.json
```

### Errore #2: Link senza locale
```blade
// ❌ ERRATO
<a href="/pages/about">Chi Siamo</a>

// ✅ CORRETTO
<a href="{{ LaravelLocalization::localizeUrl('/pages/about') }}">Chi Siamo</a>
```

### Errore #3: Contenuto hardcoded
```blade
// ❌ ERRATO
<h1>Chi Siamo</h1>

// ✅ CORRETTO
<h1>{{ $page->title }}</h1>
<!-- Il title viene dal JSON -->
```

### Errore #4: Creare rotte in routes/web.php
```php
// ❌ ERRATO
Route::get('/pages/{slug}', [PageController::class, 'show']);

// ✅ CORRETTO
// Niente! Folio gestisce automaticamente
// Il file /pages/[slug].blade.php crea la rotta
```

---

## 📚 Riferimenti

### Documentazione
- [Folio + Volt Architecture](../Modules/Xot/docs/folio-volt-architecture.md)
- [Folio Pages](../Modules/Cms/docs/folio_pages.md)
- [Laravel Localization](../Modules/Cms/docs/folio-routing-locale.md)

### Componenti
- [Page Component](../Modules/Cms/app/View/Components/Page.php)
- [PageSlugMiddleware](../Modules/Cms/Http/Middleware/PageSlugMiddleware.php)

### Pattern
- [Dynamic Block Rendering](./dynamic-block-rendering-pattern.md)
- [Localization Standards](./localization-standard.md)

---

## 🎓 Lezioni Fondamentali

1. **UNO basta**: Un solo file gestore per infinite pagine
2. **Database è la fonte**: Il contenuto è nel JSON, non nel Blade
3. **Locale è tutto**: Ogni URL ha il prefisso lingua
4. **Non duplicare**: DRY è un principio sacro
5. **Flusso naturale**: L'architettura deve fluire senza attrito

---

**Status**: ✅ Sacred Knowledge
**Maintained by**: iFlow CLI
**Last updated**: 2026-02-07