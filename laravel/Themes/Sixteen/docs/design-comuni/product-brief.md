# Design Comuni Replication - Product Brief

> **BMAD Product Brief: Replica completa delle pagine Design Comuni Italia**

## 📋 Panoramica Prodotto

**Nome:** Design Comuni Replication System  
**Versione:** 1.0  
**Stato:** 🔄 In Pianificazione  
**Data:** 2026-04-01

### Obiettivo Principale

Replicare **fedelmente** tutte le 38 pagine statiche del progetto Design Comuni Italia nel tema Sixteen, partendo dalla struttura HTML (esclusi script), per poi procedere con styling Tailwind CSS e interattività JavaScript/Alpine.js.

### Visione

Creare un sistema di **componenti riutilizzabili** che permetta di:
1. Replicare qualsiasi pagina Design Comuni con HTML identico
2. Utilizzare blocchi universali (NON pagina-specifici)
3. Mantenere contenuti in JSON (CMS-driven)
4. Garantire conformità accessibilità PA

---

## 🎯 Obiettivi Strategici

### Obiettivo 1: Censimento Completo
- ✅ **Completato:** 38 pagine identificate
- 📊 Categorizzate per sezione (Generali, Amministrazione, Novità, Servizi, Vivere, Appuntamento, Assistenza, Segnalazione)
- 🔗 Mappatura relazioni tra pagine

### Obiettivo 2: Analisi Blocchi
- ⏳ **In Corso:** Identificazione blocchi riutilizzabili
- 🎯 Riconoscere pattern comuni tra pagine
- 🧩 Creare libreria componenti universali

### Obiettivo 3: Replica HTML
- ⏳ **Da Iniziare:** HTML identico (dentro `<body>`, esclusi script)
- 📐 Stessa struttura, stesse classi Bootstrap Italia
- ✅ Verifica con View Source e screenshot

### Obiettivo 4: Styling Tailwind
- ⏳ **Futuro:** Replica stili con @apply (NO import Bootstrap)
- 🎨 Palette colori Bootstrap Italia
- 📱 Responsive design

### Obiettivo 5: Interattività
- ⏳ **Futuro:** JavaScript/Alpine.js per interazioni
- ⚡ Sostituzione Bootstrap Italia JS
- 🔄 Multi-step forms, menu toggle, search

---

## 📊 Pagine Censite (38 Totale)

### Sezione 1: Pagine Generali (9 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 1 | Homepage | [/sito/homepage.html](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html) | `/it/tests/homepage` | 🔴 Alta |
| 2 | FAQ | [/sito/domande-frequenti.html](https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html) | `/it/tests/domande-frequenti` | 🟡 Media |
| 3 | Ricerca | [/sito/risultati-ricerca.html](https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html) | `/it/tests/risultati-ricerca` | 🟡 Media |
| 4 | Argomenti (Lista) | [/sito/argomenti.html](https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html) | `/it/tests/argomenti` | 🔴 Alta |
| 5 | Argomento (Dettaglio) | [/sito/argomento.html](https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html) | `/it/tests/argomento/{slug}` | 🟡 Media |
| 6 | Lista Risorse | [/sito/lista-risorse.html](https://italia.github.io/design-comuni-pagine-statiche/sito/lista-risorse.html) | `/it/tests/lista-risorse` | 🟢 Bassa |
| 7 | Lista Categorie | [/sito/lista-categorie.html](https://italia.github.io/design-comuni-pagine-statiche/sito/lista-categorie.html) | `/it/tests/lista-categorie` | 🟢 Bassa |
| 8 | Lista Mista | [/sito/lista-risorse-categorie.html](https://italia.github.io/design-comuni-pagine-statiche/sito/lista-risorse-categorie.html) | `/it/tests/lista-mista` | 🟢 Bassa |
| 9 | Mappa Sito | [/sito/mappa-sito.html](https://italia.github.io/design-comuni-pagine-statiche/sito/mappa-sito.html) | `/it/tests/mappa-sito` | 🟢 Bassa |

---

### Sezione 2: Amministrazione (2 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 10 | Amministrazione (Lista) | [/sito/amministrazione.html](https://italia.github.io/design-comuni-pagine-statiche/sito/amministrazione.html) | `/it/tests/amministrazione` | 🟡 Media |
| 11 | Documenti e Dati | [/sito/documenti-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/documenti-dati.html) | `/it/tests/documenti-dati` | 🟡 Media |

---

### Sezione 3: Novità (2 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 12 | Novità (Lista) | [/sito/novita.html](https://italia.github.io/design-comuni-pagine-statiche/sito/novita.html) | `/it/tests/novita` | 🟡 Media |
| 13 | Novità Dettaglio | [/sito/novita-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/novita-dettaglio.html) | `/it/tests/novita/{slug}` | 🟡 Media |

---

### Sezione 4: Servizi (3 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 14 | Servizi (Lista) | [/sito/servizi.html](https://italia.github.io/design-comuni-pagine-statiche/sito/servizi.html) | `/it/tests/servizi` | 🟡 Media |
| 15 | Servizi Categoria | [/sito/servizi-categoria.html](https://italia.github.io/design-comuni-pagine-statiche/sito/servizi-categoria.html) | `/it/tests/servizi/{categoria}` | 🟢 Bassa |
| 16 | Servizio Dettaglio | [/sito/servizio-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/servizio-dettaglio.html) | `/it/tests/servizio/{slug}` | 🟡 Media |

---

### Sezione 5: Vivere il Comune (2 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 17 | Eventi (Lista) | [/sito/eventi.html](https://italia.github.io/design-comuni-pagine-statiche/sito/eventi.html) | `/it/tests/eventi` | 🟡 Media |
| 18 | Evento Dettaglio | [/sito/evento-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/evento-dettaglio.html) | `/it/tests/evento/{slug}` | 🟡 Media |

---

### Sezione 6: Prenotazione Appuntamento (7 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 19 | Step 1 - Ufficio | [/sito/appuntamento-01-ufficio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-01-ufficio.html) | `/it/tests/appuntamento/1/ufficio` | 🟡 Media |
| 20 | Step 1 - Luogo | [/sito/appuntamento-01-ufficio-luogo.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-01-ufficio-luogo.html) | `/it/tests/appuntamento/1/luogo` | 🟡 Media |
| 21 | Step 2 - Data/Ora | [/sito/appuntamento-02-data-orario.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-02-data-orario.html) | `/it/tests/appuntamento/2/data-orario` | 🟡 Media |
| 22 | Step 3 - Dettagli | [/sito/appuntamento-03-dettagli.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-03-dettagli.html) | `/it/tests/appuntamento/3/dettagli` | 🟡 Media |
| 23 | Step 4 - Non Autenticato | [/sito/appuntamento-04-richiedente.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-04-richiedente.html) | `/it/tests/appuntamento/4/dati` | 🟡 Media |
| 24 | Step 4 - Autenticato | [/sito/appuntamento-04-richiedente-autenticato.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-04-richiedente-autenticato.html) | `/it/tests/appuntamento/4/verifica` | 🟢 Bassa |
| 25 | Step 5 - Riepilogo | [/sito/appuntamento-05-riepilogo.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-05-riepilogo.html) | `/it/tests/appuntamento/5/riepilogo` | 🟡 Media |
| 26 | Step 6 - Conferma | [/sito/appuntamento-06-conferma.html](https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-06-conferma.html) | `/it/tests/appuntamento/6/conferma` | 🟡 Media |

---

### Sezione 7: Richiesta Assistenza (2 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 27 | Step 1 - Dati | [/sito/assistenza-01-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/assistenza-01-dati.html) | `/it/tests/assistenza/1/dati` | 🟢 Bassa |
| 28 | Step 2 - Conferma | [/sito/assistenza-02-conferma.html](https://italia.github.io/design-comuni-pagine-statiche/sito/assistenza-02-conferma.html) | `/it/tests/assistenza/2/conferma` | 🟢 Bassa |

---

### Sezione 8: Segnalazione Disservizio (7 pagine)

| # | Pagina | URL Originale | URL Locale | Priorità |
|---|--------|---------------|------------|----------|
| 29 | Disservizio Dettaglio | [/sito/segnalazione-dettaglio.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html) | `/it/tests/segnalazione/dettaglio` | 🟢 Bassa |
| 30 | Step 1 - Privacy | [/sito/segnalazione-01-privacy.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html) | `/it/tests/segnalazione/1/privacy` | 🟢 Bassa |
| 31 | Step 2 - Dati | [/sito/segnalazione-02-dati.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html) | `/it/tests/segnalazione/2/dati` | 🟢 Bassa |
| 32 | Step 3 - Riepilogo | [/sito/segnalazione-03-riepilogo.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html) | `/it/tests/segnalazione/3/riepilogo` | 🟢 Bassa |
| 33 | Step 4 - Conferma | [/sito/segnalazione-04-conferma.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html) | `/it/tests/segnalazione/4/conferma` | 🟢 Bassa |
| 34 | Area Personale | [/sito/segnalazione-area-personale.html](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html) | `/it/tests/segnalazione/area-personale` | 🟢 Bassa |
| 35 | Elenco Segnalazioni | [/sito/ticket-list.html](https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html) | `/it/tests/segnalazioni/elenco` | 🟢 Bassa |

---

## 🧩 Blocchi Identificati (Preliminare)

### Blocchi Strutturali (Presenti in TUTTE le pagine)

| Blocco | Utilizzo | Descrizione |
|--------|----------|-------------|
| `header.main` | 38/38 pagine | Header completo (top bar, brand, navigation) |
| `footer.main` | 38/38 pagine | Footer completo (contatti, sitemap, legal) |
| `navigation.breadcrumb` | 35/38 pagine | Breadcrumb navigation |
| `navigation.primary-menu` | 38/38 pagine | Menu principale |
| `navigation.secondary-menu` | 30/38 pagine | Menu secondario contestuale |

---

### Blocchi Contenuto (Variano per pagina)

#### Pagine Generali

| Pagina | Blocchi Richiesti |
|--------|-------------------|
| Homepage | `hero.newscard`, `card-grid.governance`, `list.events`, `topics-grid.featured`, `card-grid.thematic-sites`, `form.feedback` |
| Argomenti | `topics-grid.all`, `topics-grid.featured`, `form.feedback` |
| FAQ | `list.faq`, `form.search`, `form.contact` |
| Ricerca | `list.search-results`, `pagination`, `filters` |
| Mappa Sito | `sitemap.tree`, `quick-links` |

#### Pagine Lista

| Pagina | Blocchi Richiesti |
|--------|-------------------|
| Lista Risorse | `list.resources`, `pagination`, `filters` |
| Lista Categorie | `list.categories`, `pagination` |
| Lista Mista | `list.mixed`, `list.categories`, `pagination` |
| Amministrazione | `list.administration`, `card-grid.organi` |
| Novità | `list.news`, `pagination`, `filters` |
| Servizi | `list.services`, `list.categories`, `pagination` |
| Eventi | `list.events`, `calendar-view`, `pagination` |

#### Pagine Dettaglio

| Pagina | Blocchi Richiesti |
|--------|-------------------|
| Argomento Dettaglio | `detail.topic`, `list.related-content`, `card-grid.resources` |
| Novità Dettaglio | `detail.news`, `share-buttons`, `related-news` |
| Servizio Dettaglio | `detail.service`, `requirements`, `costs`, `timeline`, `cta.apply` |
| Evento Dettaglio | `detail.event`, `calendar-add`, `location-map`, `registration` |

#### Multi-Step Forms

| Workflow | Blocchi Richiesti |
|----------|-------------------|
| Appuntamento (7 step) | `stepper.horizontal`, `form.selection`, `form.datetime`, `form.personal-data`, `form.review`, `confirmation.success` |
| Assistenza (2 step) | `stepper.horizontal`, `form.assistance`, `form.review`, `confirmation.success` |
| Segnalazione (4 step) | `stepper.horizontal`, `form.privacy`, `form.location`, `form.details`, `form.review`, `confirmation.success` |

---

## 🎯 Strategia di Implementazione

### Fase 1: Analisi e Censimento (COMPLETA ✅)

**Obiettivo:** Identificare tutte le pagine e i blocchi

**Task Completati:**
- ✅ Censimento 38 pagine
- ✅ Categorizzazione per sezione
- ✅ Identificazione preliminare blocchi
- ✅ Mappatura relazioni

**Documentazione:**
- [Product Brief](./product-brief.md) ← Questo documento
- [Pages Census](./pages-census.md)
- [Blocks Analysis](./blocks-analysis.md)

---

### Fase 2: Analisi Dettagliata Blocchi (IN CORSO 🔄)

**Obiettivo:** Analizzare ogni pagina e identificare blocchi riutilizzabili

**Task:**
1. 🔄 Scaricare HTML di tutte le 38 pagine
2. 🔄 Analizzare struttura HTML (dentro `<body>`)
3. 🔄 Identificare pattern comuni
4. 🔄 Creare matrice blocchi per pagina
5. 🔄 Definire blocchi universali

**Output Attesi:**
- [ ] HTML archive di tutte le pagine
- [ ] Matrice blocchi (pagina × blocco)
- [ ] Libreria blocchi universali definita
- [ ] Documentazione blocchi

---

### Fase 3: Replica HTML (PRIORITÀ 🔴)

**Obiettivo:** Replicare HTML identico per tutte le pagine

**Approccio:**
1. Iniziare da Homepage (priorità alta)
2. Procedere con pagine Generali (Argomenti, FAQ, Ricerca)
3. Continuare con pagine Lista (Amministrazione, Novità, Servizi, Eventi)
4. Completare con pagine Dettaglio
5. Infine multi-step forms

**Per Ogni Pagina:**
```markdown
1. Scaricare HTML originale (View Source)
2. Estrarre contenuto dentro <body> (esclusi script)
3. Creare JSON content con blocchi identificati
4. Implementare blocchi mancanti
5. Verificare HTML identico (diff check)
6. Salvare screenshot
7. Documentare differenze
```

**Criteri di Accettazione:**
- ✅ HTML dentro `<body>` identico (esclusi script)
- ✅ Stesse classi Bootstrap Italia
- ✅ Stessa struttura gerarchica
- ✅ Screenshot confrontati
- ✅ Documentazione aggiornata

---

### Fase 4: Styling Tailwind CSS (FUTURO 🟡)

**Obiettivo:** Replicare stili Bootstrap Italia con Tailwind @apply

**Approccio:**
1. Analizzare CSS Bootstrap Italia utilizzato
2. Creare palette colori in Tailwind config
3. Implementare classi con @apply in style-apply.css
4. Verificare rendering visivo identico
5. Testare responsive

**Per Ogni Blocco:**
```markdown
1. Identificare classi Bootstrap utilizzate
2. Tradurre in Tailwind @apply
3. Testare rendering
4. Verificare responsive
5. Documentare mapping classi
```

**Criteri di Accettazione:**
- ✅ Rendering visivo identico
- ✅ Colori conformi palette Bootstrap Italia
- ✅ Responsive design funzionante
- ✅ Accessibilità WCAG AA

---

### Fase 5: Interattività JavaScript (FUTURO 🟢)

**Obiettivo:** Sostituire Bootstrap Italia JS con Alpine.js

**Approccio:**
1. Identificare funzionalità JS Bootstrap Italia
2. Implementare con Alpine.js
3. Testare interazioni
4. Verificare accessibilità keyboard

**Funzionalità da Implementare:**
- [ ] Mobile menu toggle
- [ ] Search autocomplete
- [ ] Multi-step form navigation
- [ ] Date/time picker
- [ ] File upload
- [ ] Modal/dialog
- [ ] Accordion (FAQ)
- [ ] Tabs
- [ ] Carousel

**Criteri di Accettazione:**
- ✅ Tutte le interazioni funzionanti
- ✅ Accessibilità keyboard (tab, enter, escape)
- ✅ Performance ottimizzate
- ✅ No console errors

---

## 📐 Architettura Tecnica

### Stack Tecnologico

```
Frontend:
├── Laravel Folio (file-based routing)
├── Livewire Volt (class-based components)
├── Tailwind CSS v4 (styling con @apply)
├── Alpine.js (interattività)
└── Vite (build system)

Backend:
├── Laravel 12
├── JSON content storage
└── CMS-driven pages

Build:
├── outDir: './public' (Vite)
├── npm run copy (sync to public_html)
└── @vite([...], 'themes/Sixteen') (asset loading)
```

### Directory Structure

```
laravel/Themes/Sixteen/
├── resources/
│   ├── views/
│   │   ├── components/
│   │   │   ├── blocks/           ← Blocchi universali
│   │   │   │   ├── hero/
│   │   │   │   ├── card-grid/
│   │   │   │   ├── topics-grid/
│   │   │   │   ├── list/
│   │   │   │   ├── form/
│   │   │   │   ├── navigation/
│   │   │   │   ├── detail/
│   │   │   │   └── stepper/
│   │   │   └── sections/         ← Sections (header, footer)
│   │   │       ├── header/
│   │   │       └── footer/
│   │   └── pages/
│   │       └── tests/
│   │           ├── [slug].blade.php  ← Route dinamica
│   │           └── index.blade.php
│   ├── css/
│   │   └── style-apply.css       ← Tailwind @apply
│   └── js/
│       └── app.js                ← Alpine.js
└── config/
    └── local/
        └── fixcity/
            └── database/
                └── content/
                    └── pages/        ← JSON content
                        ├── tests.homepage.json
                        ├── tests.argomenti.json
                        └── ...
```

### Routing Strategy

**File-based routing con Laravel Folio:**

```php
// Route: /it/tests/{slug}
// File: Themes/Sixteen/resources/views/pages/tests/[slug].blade.php

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

{{-- Layout Hierarchy (DRY + KISS) --}}
{{-- [slug].blade.php → app.blade.php → main.blade.php --}}
<x-layouts.app>
    @volt('tests.view')
        {{-- SOLO contenuto specifico (NO header/footer/skiplink) --}}
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>
```

**Perché [slug].blade.php NON ha header/footer:**

- ✅ **DRY:** Header/footer scritti UNA volta in `app.blade.php`
- ✅ **KISS:** Page template gestisce SOLO routing e contenuto
- ✅ **Manutenibilità:** Modifica header/footer in 1 file solo (38 pagine aggiornate automaticamente)

**Documentazione:** [Layout Architecture](../architecture/layout-architecture.md)

**JSON Content:**

```json
{
    "slug": "tests.homepage",
    "title": "Homepage",
    "blocks": [
        {
            "type": "hero",
            "data": {
                "view": "pub_theme::components.blocks.hero.newscard",
                "title": "Contenuti in Evidenza",
                "date": "2024-01-15",
                "description": "...",
                "link": "/..."
            }
        }
    ]
}
```

---

## 📊 Metriche e KPI

### Metriche di Completamento

| Metrica | Target | Attuale | Stato |
|---------|--------|---------|-------|
| Pagine censite | 38 | 38 | ✅ 100% |
| Blocchi identificati | ~50 | ~20 | 🔄 40% |
| Pagine replicate (HTML) | 38 | 0 | ⏳ 0% |
| Blocchi implementati | ~50 | ~10 | 🔄 20% |
| Test coverage | 80% | 0% | ⏳ 0% |

### Metriche di Qualità

| Metrica | Target | Metodo Verifica |
|---------|--------|-----------------|
| HTML identico | 100% | Diff check View Source |
| Stili conformi | 100% | Screenshot comparison |
| Accessibilità | WCAG AA | Audit automatico |
| Performance | < 3s load | Lighthouse |
| Responsive | 100% pages | Multi-device test |

---

## 🎯 Criteri di Accettazione

### Per Ogni Pagina

```markdown
## HTML Structure
- [ ] HTML dentro <body> identico (esclusi script)
- [ ] Stesse classi Bootstrap Italia
- [ ] Stessa gerarchia elementi
- [ ] View Source diff: 0 differenze strutturali

## Documentation
- [ ] Screenshot originale salvato
- [ ] Screenshot replica salvato
- [ ] Analisi differenze documentata
- [ ] JSON content creato
- [ ] Blocchi documentati

## Quality
- [ ] PHPStan: Level 10 OK
- [ ] Pint: Formattazione OK
- [ ] Accessibilità: WCAG AA
- [ ] Responsive: Mobile/Tablet/Desktop
```

### Per Ogni Blocco

```markdown
## Implementation
- [ ] File Blade creato: components/blocks/<tipo>/<blade>.blade.php
- [ ] Dati JSON definiti
- [ ] Styling Tailwind @apply
- [ ] Responsive testato

## Documentation
- [ ] Documentazione blocco creata
- [ ] Esempio JSON fornito
- [ ] Classi Tailwind documentate
- [ ] Link bidirezionali

## Quality
- [ ] Riutilizzabile (NON pagina-specifico)
- [ ] Testato con dati reali
- [ ] Accessibilità OK
- [ ] Performance OK
```

---

## 📚 Documentazione Richiesta

### Documenti Principali

| Documento | Scopo | Stato |
|-----------|-------|-------|
| Product Brief | Panoramica progetto | ✅ Creato |
| Pages Census | Elenco completo pagine | ✅ Creato |
| Blocks Analysis | Analisi blocchi per pagina | 🔄 In Corso |
| Implementation Plan | Piano dettagliato implementazione | ⏳ Da Creare |
| Progress Tracker | Tracking avanzamento | ⏳ Da Creare |

### Documenti per Pagina

Per ogni pagina replicata:
- [ ] Pagina documentation (es: `pages/homepage.md`)
- [ ] Screenshot comparison (`screenshots/homepage/`)
- [ ] JSON content example
- [ ] Blocks usage documentation

### Documenti per Blocco

Per ogni blocco universale:
- [ ] Blocco documentation (es: `blocks/hero/newscard.md`)
- [ ] Esempio JSON
- [ ] Classi Tailwind utilizzate
- [ ] Test cases

---

## 🚀 Roadmap

### Sprint 1: Analisi (Settimana 1-2)

**Obiettivo:** Completare analisi blocchi

**Task:**
- [ ] Scaricare HTML tutte le 38 pagine
- [ ] Analizzare struttura HTML
- [ ] Identificare blocchi per pagina
- [ ] Creare matrice blocchi
- [ ] Definire blocchi universali

**Output:**
- [ ] HTML archive
- [ ] Matrice blocchi completa
- [ ] Libreria blocchi definita (~50 blocchi)

---

### Sprint 2: Homepage (Settimana 3)

**Obiettivo:** Replicare Homepage (HTML identico)

**Task:**
- [ ] Scaricare HTML homepage originale
- [ ] Analizzare blocchi richiesti (6 blocchi)
- [ ] Implementare blocchi mancanti
- [ ] Creare JSON content
- [ ] Verificare HTML identico
- [ ] Salvare screenshot
- [ ] Documentare

**Output:**
- [ ] Homepage HTML identico
- [ ] 6 blocchi implementati
- [ ] Documentazione completa

---

### Sprint 3: Pagine Generali (Settimana 4-5)

**Obiettivo:** Replicare pagine Generali (9 pagine)

**Task:**
- [ ] Argomenti (lista)
- [ ] Argomento (dettaglio)
- [ ] FAQ
- [ ] Ricerca
- [ ] Mappa sito
- [ ] Liste (risorse, categorie, mista)

**Output:**
- [ ] 9 pagine replicate (HTML)
- [ ] ~15 blocchi implementati
- [ ] Documentazione completa

---

### Sprint 4: Pagine Lista (Settimana 6-7)

**Obiettivo:** Replicare pagine Lista (7 pagine)

**Task:**
- [ ] Amministrazione
- [ ] Documenti e Dati
- [ ] Novità (lista)
- [ ] Servizi (lista)
- [ ] Servizi (categoria)
- [ ] Eventi (lista)

**Output:**
- [ ] 7 pagine replicate (HTML)
- [ ] ~10 blocchi implementati
- [ ] Documentazione completa

---

### Sprint 5: Pagine Dettaglio (Settimana 8-9)

**Obiettivo:** Replicare pagine Dettaglio (5 pagine)

**Task:**
- [ ] Novità Dettaglio
- [ ] Servizio Dettaglio
- [ ] Evento Dettaglio
- [ ] Argomento Dettaglio

**Output:**
- [ ] 5 pagine replicate (HTML)
- [ ] ~8 blocchi implementati
- [ ] Documentazione completa

---

### Sprint 6: Multi-Step Forms (Settimana 10-12)

**Obiettivo:** Replicare multi-step forms (11 pagine)

**Task:**
- [ ] Appuntamento (7 step)
- [ ] Assistenza (2 step)
- [ ] Segnalazione (4 step)

**Output:**
- [ ] 11 pagine replicate (HTML)
- [ ] ~10 blocchi stepper/form implementati
- [ ] Documentazione completa

---

### Sprint 7: Styling Tailwind (Settimana 13-16)

**Obiettivo:** Implementare stili Tailwind per tutte le pagine

**Task:**
- [ ] Analizzare CSS Bootstrap Italia utilizzato
- [ ] Creare palette colori Tailwind
- [ ] Implementare @apply per ogni blocco
- [ ] Verificare rendering identico
- [ ] Testare responsive

**Output:**
- [ ] Tutte le pagine con styling Tailwind
- [ ] Palette colori documentata
- [ ] Mapping classi Bootstrap → Tailwind

---

### Sprint 8: Interattività (Settimana 17-20)

**Obiettivo:** Implementare interattività con Alpine.js

**Task:**
- [ ] Mobile menu toggle
- [ ] Search autocomplete
- [ ] Multi-step form navigation
- [ ] Date/time picker
- [ ] File upload
- [ ] Modal/dialog
- [ ] Accordion, Tabs, Carousel

**Output:**
- [ ] Tutte le interazioni funzionanti
- [ ] Accessibilità keyboard
- [ ] Performance ottimizzate

---

## 🔗 Collegamenti Documentazione

### Documenti Correlati

- [BMAD Method](https://github.com/bmad-code-org/BMAD-METHOD)
- [Design Comuni GitHub](https://github.com/italia/design-comuni-pagine-statiche)
- [Design Comuni Demo](https://italia.github.io/design-comuni-pagine-statiche/)
- [Replikate.txt](./prompts/replikate.txt)
- [Quick Start Guide](./QUICK_START.md)

### Indici

- [Main Index](../00-index.md)
- [Design Comuni Index](./00-index.md)
- [Pages Index](./pages/00-index.md)
- [Blocks Index](./blocks/00-index.md)

---

## 📝 Note Importanti

### Regole Assolute

> 1. **HTML Identico:** Dentro `<body>` (esclusi script) deve essere IDENTICO
> 2. **Blocchi Universali:** MAI blocchi pagina-specifici
> 3. **JSON Content:** SEMPRE CMS-driven, MAI hardcoded in Blade
> 4. **Tailwind @apply:** MAI import Bootstrap, SEMPRE @apply
> 5. **Documentazione:** SEMPRE bidirezionale (min 3 cross-reference)

### Principi Guida

> **DRY:** Unico `[slug].blade.php` per tutte le pagine  
> **KISS:** Struttura semplice, esempi concreti  
> **Zen:** Blocchi universali, riutilizzabili, NON specifici

### Agent Collaboration

> Questo progetto coinvolve multipli agenti AI:
> - `gsd-codebase-mapper`: Mappatura codebase
> - `gsd-ui-researcher`: Ricerca pattern UI
> - `gsd-ui-checker`: Verifica conformità
> - `gsd-executor`: Esecuzione piano
> - `gsd-verifier`: Verifica finale
> - `gsd-nyquist-auditor`: Test coverage

---

**Data Creazione:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ Approvato  
**Prossima Revisione:** Dopo Fase 2 (Analisi Blocchi)

---

*"UNO [slug].blade.php per TUTTE le pagine"*  
*"JSON per contenuti, Blade per struttura"*  
*"Blocchi universali, NON pagina-specifici"*  
*"DRY + KISS"*
