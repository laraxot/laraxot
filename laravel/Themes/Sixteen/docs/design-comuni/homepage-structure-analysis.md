# Homepage Analysis: Design Comuni vs Current Implementation

## Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`

## Regola Fondamentale

> **L'HTML dentro `<body>` (esclusi gli script) delle pagine fixcity DEVE essere uguale
> all'HTML dentro `<body>` delle corrispondenti pagine design-comuni-pagine-statiche.**
>
> Questo vale per TUTTE le pagine, non solo argomenti.

---

## 1. Reference Homepage Structure

Il body della reference homepage contiene queste sezioni:

```html
<body>
  <!-- Skiplink -->
  <div class="skiplink">...</div>

  <!-- Header (uguale a tutte le pagine) -->
  <header class="it-header-wrapper">...</header>

  <main>
    <!-- 1. Hidden H1 for accessibility -->
    <h1 class="visually-hidden" id="main-container">Nome del comune</h1>

    <!-- 2. Head Section - News in evidenza -->
    <section id="head-section">
      <h2 class="visually-hidden">Contenuti in evidenza</h2>
      Card notizia + immagine (2 colonne)
    </section>

    <!-- 3. Calendario Section -->
    <section id="calendario">
      3 teaser cards (Sindaco, Giunta, Consiglio)
      Eventi con carousel Splide
    </section>

    <!-- 4. Argomenti in evidenza -->
    <section class="evidence-section">
      Background image
      3 topic cards (Trasporto pubblico, Animale domestico, Sport)
      "Altri argomenti" chips
      "Siti tematici" colored cards
    </section>

    <!-- 5. Link utili -->
    <section class="useful-links-section">
      Search input
      Link utili list
    </section>

    <!-- 6. Rating/Feedback -->
    <div class="bg-primary">
      <div class="cmp-rating">...</div>
    </div>

    <!-- 7. Contatta il comune -->
    <div class="bg-grey-card shadow-contacts">
      <div class="cmp-contacts">...</div>
    </div>
  </main>

  <!-- Footer (uguale a tutte le pagine) -->
  <footer class="it-footer">...</footer>
</body>
```

---

## 2. Current Implementation Problems

### 2.1 home.blade.php
- Usa `<x-pub_theme::layouts.app>` (non standard)
- Contenuto custom non conforme alla reference
- 3 CTA cards (Segnalazioni, Nuova Segnalazione, Servizi)
- Header custom con Tailwind classes invece di Bootstrap Italia

### 2.2 homepage.blade.php
- Usa `<x-layouts.main>` con Bootstrap Italia Design System
- Sezioni: Hero, Servizi in evidenza, Ultime notizie, CTA
- Non conforme alla reference structure

### 2.3 tests.homepage.json
- Referenzia `pub_theme::components.blocks.tests.intro`
- `tests.intro` non è un tipo di blocco valido
- Dovrebbe usare tipi universali: `news`, `card`, `calendar`, `topics`, `links`, `feedback`, `contact`

---

## 3. Homepage Sections Block Mapping

| # | Reference Section | Block Type | View |
|---|-------------------|------------|------|
| 0 | `<h1 class="visually-hidden">` | N/A (nel layout) | Sempre nel main |
| 1 | `head-section` (notizia) | `news` | `pub_theme::components.blocks.news.featured` |
| 2 | `calendario` (cards + calendar) | `card` + `calendar` | `pub_theme::components.blocks.card.teaser-trio` + `pub_theme::components.blocks.calendar.carousel` |
| 3 | `evidence-section` (argomenti) | `topics` + `chips` | `pub_theme::components.blocks.topics.featured` |
| 4 | `useful-links-section` | `links` + `search` | `pub_theme::components.blocks.links.search` |
| 5 | Rating | `feedback` | `pub_theme::components.blocks.feedback.rating` |
| 6 | Contatti | `contact` | `pub_theme::components.blocks.contact.card` |

---

## 4. CSS Classes Mancanti in app.css

Dall'analisi della reference homepage, queste classi Bootstrap Italia NON sono ancora definite in `app.css`:

| Classe | Uso | Stato |
|--------|-----|-------|
| `section-muted` | Background grigio sezione | ❌ Mancante |
| `card-teaser` | Card compatta con read-more | ❌ Mancante |
| `card-teaser-wrapper` | Wrapper griglia teaser | ❌ Mancante |
| `card-teaser-wrapper-equal` | Altezza uguale | ❌ Mancante |
| `card-teaser-block-3` | 3 colonne | ❌ Mancante |
| `card-overlapping` | Cards sovrapposte | ❌ Mancante |
| `card-teaser-image` | Card con immagine | ❌ Mancante |
| `read-more` | Link "Vai alla pagina" | ❌ Mancante |
| `chip` / `chip-simple` | Tag/chip | ❌ Mancante |
| `category-top` | Categoria in cima alla card | ❌ Mancante |
| `card-bg-blue` | Card sfondo blu | ❌ Mancante |
| `card-bg-warning` | Card sfondo giallo | ❌ Mancante |
| `card-bg-dark` | Card sfondo scuro | ❌ Mancante |
| `evidence-section` | Sezione argomenti evidenza | ❌ Mancante |
| `cmp-input-search` | Search input compatto | ❌ Mancante |
| `section` | Sezione generica | ❌ Mancante |
| `pb-90`, `pb-lg-50` | Spacing Bootstrap Italia | ❌ Mancante |
| `pt-lg-60`, `pt-30`, `pt-lg-30` | Spacing Bootstrap Italia | ❌ Mancante |
| `mb-10`, `pb-10` | Spacing Bootstrap Italia | ❌ Mancante |
| `text-paragraph-medium` | Typography | ❌ Mancante |
| `text-paragraph-card` | Typography card | ❌ Mancante |
| `u-grey-light` | Colore grigio chiaro | ❌ Mancante |
| `sito-tematico` | Titolo sito tematico | ❌ Mancante |

---

## 5. Piano di Correzione

### Step 1: Aggiungere CSS mancante
Aggiungere in `app.css` le classi mancanti usando `@apply`.

### Step 2: Creare homepage.json config
Creare `config/local/fixcity/database/content/pages/homepage.json` con blocchi universali.

### Step 3: Creare/aggiornare blade blocks
Creare i block blade mancanti:
- `components/blocks/news/featured.blade.php`
- `components/blocks/card/teaser-trio.blade.php`
- `components/blocks/calendar/carousel.blade.php`
- `components/blocks/topics/featured.blade.php`
- `components/blocks/links/search.blade.php`

### Step 4: Aggiornare homepage.blade.php
Convertire al pattern Folio + Volt con `<x-page>`.

### Step 5: Build e test
```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```

### Step 6: Verificare che HTML output matchi reference

---

**Ultimo aggiornamento**: 2026-03-30

### 🔗 Documenti Correlati
- [HOMEPAGE_HTML_ANALYSIS.md](./HOMEPAGE_HTML_ANALYSIS.md) — Analisi HTML dettagliata con diff strutturale (2026-04-07, 81% similarità)
- [homepage-structure-diff-2026-04-02.md](./homepage-structure-diff-2026-04-02.md) — Diff strutturale precedente
- [homepage-gap-analysis.md](./homepage-gap-analysis.md) — Analisi gap homepage
- [homepage-parity-report.md](./homepage-parity-report.md) — Report parity homepage
