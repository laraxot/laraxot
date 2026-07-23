---
title: "Blocks folder vocabulary — Flowbite / Tailwind UI"
type: concept
confidence: high
created: 2026-06-10
updated: 2026-06-10
tags: [cms, blocks, naming, flowbite, tailwind, sixteen, vocabulary]
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/194
discussions:
  - https://github.com/laraxot/base_fixcity_fila5/discussions/195
related:
  - ../wiki/rules/cms-block-naming-tailwind-flowbite.md
  - ../wiki/rules/no-italian-component-names.md
  - ../../../../../docs/wiki/rules/cms-block-naming-tailwind-flowbite.md
---

# Vocabolario cartelle `components/blocks/`

## Principio

Ogni **sottocartella** di `laravel/Themes/Sixteen/resources/views/components/blocks/` deve essere un **pattern UI riconoscibile** da:

- [Flowbite Blocks](https://flowbite.com/blocks/)
- [Tailwind CSS UI Blocks](https://tailwindcss.com/plus/ui-blocks)

Il nome cartella è **inglese**, **kebab-case**, **senza dominio** (`ticket`, `segnalazione`, `pratica`).  
Il dominio FixCity vive in `data` del blocco CMS, in `lang/`, e nelle route slug — non nel path della view.

## Struttura file

```
blocks/<categoria-flowbite-o-tailwind>/<variante>.blade.php
```

- **categoria**: una voce dell'allowlist sotto
- **variante**: snake-case o kebab-case descrittivo (`default`, `2col`, `with-breadcrumbs`, `star-rating`)
- Vietati file `.blade.php` sparsi nella root di `blocks/` (debt legacy: migrare in una categoria)

## Allowlist (sorgente ufficiale)

### Marketing (Tailwind UI — Page Sections)

| Cartella | Cosa ci va | Reference |
|----------|------------|-----------|
| `hero` | H1, sottotitolo, breadcrumb, hero image/video | [Hero sections](https://tailwindcss.com/plus/ui-blocks/marketing/sections/heroes) |
| `features` | Griglie benefit, icone, value proposition | [Feature sections](https://tailwindcss.com/plus/ui-blocks/marketing/sections/feature-sections) |
| `cta` | Call-to-action con titolo + bottone | [CTA sections](https://tailwindcss.com/plus/ui-blocks/marketing/sections/cta-sections) |
| `pricing` | Tabelle prezzi, piani | [Pricing](https://tailwindcss.com/plus/ui-blocks/marketing/sections/pricing) |
| `stats` | Numeri, KPI, contatori | [Stats](https://tailwindcss.com/plus/ui-blocks/marketing/sections/stats) |
| `testimonials` | Recensioni, quote | [Testimonials](https://tailwindcss.com/plus/ui-blocks/marketing/sections/testimonials) |
| `newsletter` | Iscrizione newsletter | [Newsletter](https://tailwindcss.com/plus/ui-blocks/marketing/sections/newsletter-sections) |
| `faq` | Accordion domande frequenti (sezione pagina) | [FAQs](https://tailwindcss.com/plus/ui-blocks/marketing/sections/faq-sections) |
| `blog` | Liste articoli, card news | [Blog sections](https://flowbite.com/blocks/marketing/blog/) |
| `team` | Team, organigramma | [Team sections](https://flowbite.com/blocks/marketing/team/) |
| `content` | Sezioni testo, related, prose | [Content sections](https://flowbite.com/blocks/marketing/content/) |
| `banners` | Banner promozionali | [Banners](https://flowbite.com/blocks/marketing/banner/) |

### Layout & shell (Application UI)

| Cartella | Cosa ci va | Reference |
|----------|------------|-----------|
| `layout` | Shell pagina, wrapper main, stacked/sidebar | [Application shells](https://tailwindcss.com/plus/ui-blocks/application-ui/application-shells/stacked) |
| `grid` | Colonne 2/3/4, pannelli affiancati | [Multi-column / panels](https://tailwindcss.com/plus/ui-blocks/application-ui/application-shells/multi-column) |
| `sidebar` | Colonna laterale fissa | [Sidebar layouts](https://tailwindcss.com/plus/ui-blocks/application-ui/application-shells/sidebar) |
| `sections` | Sezioni generiche full-width | Flowbite *Content sections* |

### Navigazione

| Cartella | Cosa ci va | Reference |
|----------|------------|-----------|
| `header` | Header sito, top bar | [Headers](https://flowbite.com/blocks/marketing/header/) |
| `footer` | Footer, link legali | [Footers](https://tailwindcss.com/plus/ui-blocks/marketing/sections/footers) |
| `navigation` | Navbar, menu principale | [Navbars](https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/navbars) |
| `vertical-navigation` | Menu verticale, link icona | [Vertical navigation](https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/vertical-navigation) |
| `breadcrumb` | Breadcrumb | [Breadcrumb](https://flowbite.com/docs/components/breadcrumb/) |
| `tabs` | Tab mappa/elenco, pannelli | [Tabs](https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/tabs) |
| `pagination` | Paginazione liste | [Pagination](https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/pagination) |
| `steps` | Stepper wizard, progress | [Stepper](https://flowbite.com/docs/components/stepper/) |
| `timeline` | Timeline eventi | [Timeline](https://flowbite.com/docs/components/timeline/) |

### Dati & input

| Cartella | Cosa ci va | Reference |
|----------|------------|-----------|
| `form` / `forms` | Campi, layout form (preferire `forms` se più file) | [Form layouts](https://tailwindcss.com/plus/ui-blocks/application-ui/forms/form-layouts) |
| `contact` | Form contatti, card contatto | [Contact forms](https://flowbite.com/blocks/marketing/contact/) |
| `search` | Ricerca sito, risultati | Flowbite *Faceted search* |
| `filters` | Sidebar filtri, checkbox categorie | [Category filters](https://tailwindcss.com/plus/ui-blocks/ecommerce/components/category-filters) |
| `table` | Tabelle dati, header/footer tabella | [Tables](https://tailwindcss.com/plus/ui-blocks/application-ui/lists/tables) |
| `list` | Liste stacked, elenchi | [Stacked lists](https://tailwindcss.com/plus/ui-blocks/application-ui/lists/stacked-lists) |
| `details` | Description list, coppie label/valore | [Description lists](https://tailwindcss.com/plus/ui-blocks/application-ui/data-display/description-lists) |

### Componenti UI atomici

| Cartella | Cosa ci va | Reference |
|----------|------------|-----------|
| `card` / `cards` | Card singola, product card | [Cards](https://flowbite.com/docs/components/card/) |
| `buttons` | Bottoni standalone | [Buttons](https://flowbite.com/docs/components/buttons/) |
| `alerts` | Alert, toast, cookie bar | [Alerts](https://tailwindcss.com/plus/ui-blocks/application-ui/overlays/alerts) |
| `accordion` | Accordion standalone | [Accordion](https://flowbite.com/docs/components/accordion/) |
| `feedback` | Rating stelle, survey | Flowbite *Social proof* / rating |
| `rating` | Alias ammesso per rating (preferire `feedback` per nuovi) | — |
| `utilities` | Badge, divider, empty state, helper | Tailwind *Elements* |
| `widget` | Bridge Filament/widget CMS semplice | Application UI shells |

### Contenuto PA / liste

| Cartella | Cosa ci va | Reference |
|----------|------------|-----------|
| `about` | Chi siamo | Marketing *Content* |
| `booking` | Prenotazioni appuntamento | [Service forms](https://flowbite.com/blocks/) |
| `confirmation` | Conferma operazione | CRUD *Success message* |
| `event` / `events` | Calendario, eventi singoli o lista | [Event schedule](https://flowbite.com/blocks/marketing/event-schedule/) |
| `news` | Notizie comunali | Blog sections |
| `services` / `service` | Schede servizio | Feature / Product cards |
| `topics` | Argomenti, tassonomie | Category previews |
| `listing` | Liste generiche | Product lists |
| `resources` | Download, documenti | Content sections |
| `links` | Link rapidi | List / vertical-navigation |
| `quick-links` | Link in evidenza | List variant |
| `heading` | Page heading senza hero full | [Page headings](https://tailwindcss.com/plus/ui-blocks/application-ui/headings/page-headings) |
| `paragraph` | Blocchi testo | Content |
| `info` | Box informativi | Alerts / content |
| `categories` / `category` | Filtri categoria, chip | Category previews |

### E-commerce (se necessario)

| Cartella | Cosa ci va |
|----------|------------|
| `checkout` | Checkout |
| `order-summary` | Riepilogo ordine (kebab-case) |

Usare solo se la pagina riusa pattern ecommerce Tailwind; altrimenti mappare su `grid` + `card`.

## Eccezioni controllate (non creare nuove)

| Cartella | Motivo | Azione |
|----------|--------|--------|
| `tests/` | Fixture parity Design Comuni / snapshot | Solo pagine `tests.*`; non in produzione |
| `flow/` | Wizard multi-step (stepper + step blade) | Migrare verso `steps/` + `forms/` |
| `design-comuni/` | Riferimento HTML AGID | Non estendere; nuovi blocchi in allowlist |
| `ticket/`, `ticket-layout/`, `ticket-list/` | Debt dominio | Usare `grid`, `layout`, `filters`, `tabs`, `card` per nuovi sviluppi |
| `administration/`, `governance/`, `thematic/` | Debt PA legacy | Mappare su `content`, `team`, `listing` |
| `feature_sections` | Snake_case legacy | Rinominare in `features/` quando si tocca il blocco |


## SSoT nel codice

Lista machine-readable: `laravel/Themes/Sixteen/app/Support/BlockCategoryRegistry.php`  
Test Pest: `Themes/Sixteen/tests/Unit/BlockSubfolderNamingTest.php`  
Shell (mirror del registry): `bash bashscripts/quality-gates/check-blocks-folder-names.sh`

## Verifica automatica

```bash
bash bashscripts/quality-gates/check-blocks-folder-names.sh
```

## Decisione rapida

1. Apri [Flowbite Blocks](https://flowbite.com/blocks/) o [Tailwind UI Blocks](https://tailwindcss.com/plus/ui-blocks)
2. Identifica la **sezione** (es. *Hero Sections*, *Vertical Navigation*)
3. Normalizza in kebab-case singolare/plurale come in tabella (`hero`, `features`, `vertical-navigation`)
4. Crea `blocks/<categoria>/<variante>.blade.php`
5. Nel JSON CMS: `"type": "<categoria>"`, `"view": "pub_theme::components.blocks.<categoria>.<variante>"`

## Collegamenti

- Regola: [cms-block-naming-tailwind-flowbite.md](../wiki/rules/cms-block-naming-tailwind-flowbite.md)
- STORY: [STORY-111](../../../../../docs/stories/STORY-111-home-json-cms-blocks-refactor.md)
- Taxonomy storica: [block-taxonomy.md](./block-taxonomy.md)
