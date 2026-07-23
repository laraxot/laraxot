# Homepage Blocks - Index Completo

**Pagina di Riferimento:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Implementazione Locale:** http://127.0.0.1:8001/it/tests/homepage  
**Data Analisi:** 2026-04-07

## 📊 Riepilogo Struttura

La homepage di riferimento contiene **6 blocchi principali** organizzati verticalmente:

```
<body>
  ├── Skip Links (accessibilità)
  ├── Header/Navigation (da documentare)
  │
  ├── <main id="content">
  │   ├── Block 01: Hero/Contenuti in Evidenza
  │   ├── Block 02: Calendario Eventi + Organi di Governo
  │   ├── Block 03: Argomenti in Evidenza
  │   ├── Block 04: Ricerca + Link Utili
  │   └── Block 05: Feedback Widget (Rating)
  │
  └── Block 06: Footer
```

## 🗂️ Documentazione Blocchi

| ID | Blocco | File | Priorità | Complessità | Stato |
|----|--------|------|----------|-------------|-------|
| 00 | Header/Navigation | `block-00-header-nav.md` | 🔴 Alta | Alta | ✅ Documentato |
| 01 | Hero/Contenuti in Evidenza | `block-01-hero-evidence.md` | 🔴 Alta | Media | ✅ Documentato |
| 02 | Calendario Eventi | `block-02-calendario-eventi.md` | 🔴 Alta | Alta | ✅ Documentato |
| 03 | Argomenti in Evidenza | `block-03-argomenti-evidenza.md` | 🟡 Media | Media | ✅ Documentato |
| 04 | Ricerca e Link Utili | `block-04-search-useful-links.md` | 🟡 Media | Bassa | ✅ Documentato |
| 05 | Feedback Widget | `block-05-feedback-rating.md` | 🟢 Bassa | Media | ✅ Documentato |
| 06 | Footer Principale | `block-06-footer.md` | 🟡 Media | Alta | ✅ Documentato |

## 📋 Dettaglio Blocchi

### Block 00: Header/Navigation

**Da documentare** - Struttura complessa con:
- Slim header (Regione, Lingua, Area personale, Social)
- Center wrapper (Brand, Social, Search)
- Navbar con megamenu (Amministrazione, Novità, Servizi, Vivere)

**Bootstrap Classes:** `it-header-wrapper`, `it-header-slim-wrapper`, `navbar`, `has-megamenu`  
**Tailwind:** Da implementare con `@apply` + Alpine.js per toggle menu

---

### Block 01: Hero/Contenuti in Evidenza

**Sezione principale** - Notizia in primo piano con immagine

**Caratteristiche:**
- Layout 2 colonne (testo + immagine)
- Categoria con icona + data
- Chip/tag per argomento correlato
- Link CTA "Tutte le novità"
- Responsive: mobile stack, desktop side-by-side

**File:** [block-01-hero-evidence.md](./block-01-hero-evidence.md)

**Componenti Necessari:**
- ✅ `cmp-card` (card base)
- ✅ `cmp-chip` (tag argomento)
- ✅ `cmp-link--read-more` (link con freccia)
- ⏳ Image responsive component

---

### Block 02: Calendario Eventi

**Sezione doppia** - Organi di governo + Calendario eventi

**Caratteristiche:**
- **Parte 1:** 3 card teaser (Sindaco, Giunta, Consiglio)
  - Card 1 con immagine, Cards 2-3 solo testo
  - Layout overlapping per immagini
- **Parte 2:** Calendario settimanale con eventi
  - Carousel orizzontale (Splide.js → Alpine.js)
  - Vista LUN-DOM
  - Eventi con orario e titolo linkabile

**File:** [block-02-calendario-eventi.md](./block-02-calendario-eventi.md)

**Componenti Necessari:**
- ✅ `cmp-card--teaser` (card teaser)
- ✅ `cmp-card--overlapping` (immagini overlap)
- ✅ `cmp-calendar` (calendario)
- ⏳ Alpine.js carousel (no Splide)

---

### Block 03: Argomenti in Evidenza

**Sezione con background** - Card argomenti con link correlati

**Caratteristiche:**
- Background image decorativo
- Titolo bianco su immagine
- 3+ card argomenti (Trasporto, Animali, Sport)
- **Due varianti:**
  1. Con sito tematico correlato (nested card blu)
  2. Con lista link correlati (4+ link)

**File:** [block-03-argomenti-evidenza.md](./block-03-argomenti-evidenza.md)

**Componenti Necessari:**
- ✅ `cmp-section--evidence` (sezione con bg image)
- ✅ `cmp-card--teaser` (riusato da Block 02)
- ✅ `cmp-card--blue` (nested card siti)
- ✅ `cmp-link-list` (lista link correlati)

---

### Block 04: Ricerca e Link Utili

**Sezione mista** - Barra ricerca + link rapidi

**Caratteristiche:**
- Input search con autocomplete (Alpine.js)
- Bottone "Invio" + icona lente
- 6 link utili rapidi (CIE, Residenza, Tributi, ecc.)
- Centrato, width 50% su desktop

**File:** [block-04-search-useful-links.md](./block-04-search-useful-links.md)

**Componenti Necessari:**
- ✅ `cmp-search` (barra ricerca)
- ✅ `cmp-search__autocomplete` (Alpine.js)
- ✅ `cmp-link-list` (riusato da Block 03)
- ⏳ Debounce implementation (300ms)

---

### Block 05: Feedback Widget

**Rating pagina** - Stelle + survey multi-step

**Caratteristiche:**
- **Step 0:** 5 stelle cliccabili
- **Step 1:** Domande approfondimento
  - Positive (rating 4-5): radio buttons
  - Negative (rating 1-3): checkbox
- **Step 2:** Textarea commenti (max 200 chars)
- **Step 3:** Messaggio ringraziamento

**File:** [block-05-feedback-rating.md](./block-05-feedback-rating.md)

**Componenti Necessari:**
- ✅ `cmp-feedback` (container widget)
- ✅ `cmp-feedback__star` (singola stella SVG)
- ✅ `cmp-feedback__steps` (step navigation)
- ✅ `cmp-radio-list` (lista opzioni)
- ⏳ Alpine.js state management

---

### Block 06: Footer

**Navigazione finale** - Link, contatti, legal, social

**Caratteristiche:**
- 7 sezioni verticali
- 4 colonne desktop (3+6+3 md grid)
- Logo UE + Brand comune
- 15+ categorie di servizio
- Contatti URP completi
- 4 link legali (required AGID)
- 6 social icons
- Bottom bar (Media policy, Sitemap)

**File:** [block-06-footer.md](./block-06-footer.md)

**Componenti Necessari:**
- ✅ `cmp-footer` (container principale)
- ✅ `cmp-footer__col` (colonne)
- ✅ `cmp-footer__list` (liste link)
- ✅ `cmp-footer__brand` (logo + nome)
- ✅ `cmp-footer__social` (social icons)
- ✅ `cmp-footer__legal` (link legali)

## 🎯 Priorità Implementazione

### Fase 1: Fondamentali (Week 1-2)

1. ✅ Block 01: Hero (notizia in evidenza)
2. ✅ Block 06: Footer (navigazione completa)
3. ⏳ Block 00: Header (da documentare)

### Fase 2: Core (Week 3-4)

4. ✅ Block 04: Search + Link utili (semplice)
5. ✅ Block 03: Argomenti in evidenza (card riusabili)
6. ⏳ Block 02: Calendario (complesso, carousel)

### Fase 3: Avanzate (Week 5-6)

7. ✅ Block 05: Feedback widget (Alpine.js)
8. ⏳ Integrazione CMS (contenuti dinamici)
9. ⏳ Test accessibilità (WCAG 2.1 AA)

## 🔗 Risorse Correlate

- [Design Comuni Blocks Analysis](../../design-comuni/design-comuni-block-analysis.md)
- [HTML Comparison Tools](../../../../../../bashscripts/docs/html-comparison/README.md)
- [Page Routing Architecture](../../architecture/PAGE_ROUTING_ARCHITECTURE.md)
- [Component System](../../components/)

## 📝 Note

- **Agnosticità:** Tutti i blocchi devono essere componenti Blade riusabili
- **CMS Integration:** Contenuti da JSON (non hardcoded)
- **Alpine.js:** Sostituire Bootstrap JS (carousel, autocomplete, toggle)
- **Accessibilità:** WCAG 2.1 AA required per AGID
- **Forward-Only:** Migliorare, non resettare

## 📊 Analisi Confronto (2026-04-07)

Eseguito `bashscripts/html/compare-html.sh` — risultati:

| Metrica | Reference | Locale |
|---------|-----------|--------|
| Righe struttura body | 1634 | 3632 |
| Blocchi class-based | 9 | 0 |
| Similitudine CSS | — | 3.3% |

### ⚠️ ID Semantici Mancanti nel Locale

Tutti gli ID chiave mancano da `http://127.0.0.1:8001/it/tests/homepage`:

- ❌ `#main-container` — skiplink target (h1)
- ❌ `#head-section` — hero section
- ❌ `#calendario` — governance + calendario
- ❌ `#rating` — feedback widget
- ❌ `#search-modal` — modale ricerca
- ❌ `#footer` — footer

**Causa**: La pagina locale usa Tailwind ma non ha ancora assegnato gli `id` semantici ai blocchi.

**Fix**: Aggiungere gli `id` alle blade dei blocchi (vedi files `block-XX-*.md`).

## 🚀 Prossimi Passi

1. ✅ Documentati tutti i blocchi (block-00 → block-06)
2. ✅ Script confronto migliorato con rilevamento ID agnostico
3. ⏳ **PRIORITÀ**: Aggiungere `id` semantici ai blocchi Blade locali
   - `section#head-section` → `components.blocks.hero.homepage`
   - `section#calendario` → `components.blocks.governance.cards`
   - `div#rating` → `components.blocks.feedback.rating`
   - `footer#footer` → layout footer
4. ⏳ Re-run comparison dopo fix → target: ID semantici = 6/6
5. ⏳ Lavorare su CSS/JS dopo che la struttura HTML è corretta
