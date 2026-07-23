---
type: overview
theme: Sixteen
sources:
  - ../../../docs/design_comuni_italia_integration.md
  - ../../../docs/design-comuni-compliance.md
  - ../../../docs/bootstrap-italia-implementation.md
  - ../../../docs/sixteen-agid-naming-rules.md
confidence: high
updated: 2026-04-15
---

# Sixteen Theme — Overview

> **Ruolo**: Tema frontend per Comuni Italiani — Bootstrap Italia + AGID compliance + Design Comuni pagine statiche.

## Identità del Tema

Il tema Sixteen è il layer visivo per siti istituzionali della Pubblica Amministrazione italiana.

- **Base**: Bootstrap Italia (framework UI ufficiale PA)
- **Standard**: AGID Guidelines + WCAG 2.1 Level AA
- **Target**: Comuni italiani — allineato a `design-comuni-pagine-statiche`
- **Stack**: Tailwind CSS (colori AGID) + Bootstrap Italia SCSS + Vite

## Struttura Pagine (Design Comuni)

Il tema implementa le pagine canoniche del modello Design Comuni Italia:

| Sezione | Pagine principali |
|---------|------------------|
| **Generali** | Homepage, Mappa del sito, Ricerca, 404 |
| **Amministrazione** | Pagina admin, Organi di governo, Aree, Uffici, Persone, Documenti |
| **Novità** | Lista notizie, Dettaglio notizia/comunicato/avviso, Eventi, Calendario |
| **Servizi** | Lista servizi, Servizi per categoria, Scheda servizio, Come fare per |
| **Segnalazioni** | Tickets list, Dettaglio ticket (estensione civic FixCity) |

## Componenti Implementati

### Accessibilità (AGID obbligatorio)

- Skip-to-content links
- ARIA labels e landmarks (main, complementary, navigation)
- Focus indicators (`.agid-focus` class)
- Keyboard navigation completa
- Screen reader support
- Dark mode con contrasto WCAG

### Struttura Pagina AGID

```blade
<!-- Struttura canonica AGID -->
<a href="#main-content" class="sr-only sr-only-focusable">
    Salta al contenuto principale
</a>

<header role="banner">...</header>

<nav aria-label="Breadcrumb">
    <ol>
        <li><a href="/">Home</a></li>
        <li aria-current="page">Pagina corrente</li>
    </ol>
</nav>

<main id="main-content" role="main">
    <h1>Titolo pagina</h1>
    <!-- contenuto -->
</main>

<aside role="complementary">...</aside>
<footer role="contentinfo">...</footer>
```

### Componenti Mancanti (Gap Analysis)

| Componente | Priorità | Note |
|-----------|---------|------|
| Interactive Map (Leaflet.js) | IN CORSO | Runtime `MapPicker` attivo nel wizard segnalazioni, in hardening continuo su drag/fullscreen/layer |
| Filter Components | ALTA | Ricerca avanzata servizi/documenti |
| Timeline Component | MEDIA | Storico pratiche/atti |
| Accordion AGID | MEDIA | FAQ e documenti espandibili |

## Naming Rules (AGID)

Il tema Sixteen segue convenzioni di naming strict per l'AGID compliance:

- Componenti prefissati con `agid-` per componenti specifici PA
- Classi CSS Bootstrap Italia intatte (non sostituite con utility Tailwind)
- Tailwind usato solo per estensioni custom e colori brand

```
x-sixteen::agid.header        → header istituzionale
x-sixteen::agid.footer        → footer con loghi obbligatori
x-sixteen::agid.breadcrumb    → breadcrumb ARIA
x-sixteen::agid.card-service  → scheda servizio
x-sixteen::agid.card-news     → scheda notizia
```

## Build System

- **Bundler**: Vite
- **CSS**: Bootstrap Italia SCSS + Tailwind CSS
- **JS**: Vanilla JS + Bootstrap Italia JS (no Vue/React)
- **Icons**: Bootstrap Icons + icone custom PA

## Route Principali (Folio)

```
/it/           → homepage
/it/servizi    → lista servizi
/it/notizie    → lista notizie
/it/eventi     → lista eventi
/it/tickets    → segnalazioni civiche (FixCity)
/it/amministrazione → area istituzionale
```

## Cross-References

- [[../../../../../../laravel/Modules/Cms/docs/wiki/overviews/cms-module|Cms Module]] — gestisce Page e content blocks renderizzati da questo tema
- [[../../../../../../laravel/Modules/UI/docs/wiki/overviews/ui-module|UI Module]] — design tokens di base (sovrascritta da Bootstrap Italia)
- [[../../../../../../laravel/Modules/Lang/docs/wiki/overviews/lang-module|Lang Module]] — routing localizzato, traduzione componenti
- [[../../../../../../laravel/Modules/Fixcity/docs/wiki/index|Fixcity Module]] — logica segnalazioni civiche (ticket system)
- [[../../../../../../laravel/Modules/Geo/docs/wiki/concepts/map-picker-runtime-asset-governance.md|MapPicker Runtime Governance]] — regole runtime per marker, overlay controlli e stabilita mappa nel wizard
- [[../../../../../../laravel/Themes/TwentyOne/docs/wiki/overviews/twentyone-theme|TwentyOne Theme]] — tema alternativo non-PA

## Raw Sources Prioritari

- `docs/design_comuni_italia_integration.md` — analisi completa Design Comuni Italia
- `docs/design-comuni-compliance.md` — stato implementazione, gap analysis
- `docs/bootstrap-italia-implementation.md` — integrazione Bootstrap Italia
- `docs/sixteen-agid-naming-rules.md` — naming convention AGID
- `docs/accessibility_implementation_guide.md` — accessibilità WCAG
- `docs/agid-layout-usage-rules.md` — regole layout AGID
- `docs/complete-theme-analysis.md` — analisi completa componenti
