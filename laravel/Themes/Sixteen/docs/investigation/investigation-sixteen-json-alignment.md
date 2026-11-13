---
id: "kp-0f0a55f6caae40179f5fe5d26e093f92"
type: "investigation"
title: "Investigation — Sixteen JSON Data-Driven Home"
description: "Analisi della struttura dati JSON per la home di Sixteen, allineata al pattern TwentyOne: hero, features, stats, categories, cta, testimonials, footer."
frontmatter:
  github_issue: "#333"
links:
  github_issue: "#333"
status: draft
updated: 2026-10-11
tags: [docs, sixteen, investigation]
qmd: "investigation sixteen json alignment sixteen"
---

## Contesto

Il tema **Sixteen** sta migrando da template Blade hardcoded a contenuto guidato da JSON, seguendo il pattern già validato da **laravel/Themes/TwentyOne** (`<x-cms::page side="content" slug="home" />`).

L'obiettivo: creare un file di dati strutturato in `laravel/Themes/Sixteen/config/local/fixcity/database/content/pages/home.json` che contenga tutto il contenuto della homepage, rendendo il rendering **theme-agnostic** e i blocchi **componenti riutilizzabili**.

## Struttura dati JSON (home.json)

Il JSON è strutturato in sezioni logiche, ciascuna mappabile a un blocco componente:

### 1. Meta dati globali
```json
{
  "title": "...",
  "subtitle": "..."
}
```
- `title`: titolo SEO + H1 della pagina
- `subtitle`: intro sottotitolo (lead)

### 2. Hero section
```json
"hero": {
  "heading": "...",
  "description": "...",
  "cta_primary": { "text": "...", "url": "..." },
  "cta_secondary": { "text": "...", "url": "..." }
}
```
- Hero con titolo grande, descrizione, due CTA (primaria/secondaria)

### 3. Features (cards a due colonne)
```json
"features": [
  { "icon": "...", "title": "...", "description": "..." }
]
```
- Array di card: icona + titolo + descrizione
- Mappatura: 2 card per riga, 3 righe → 6 card totali

### 4. Stats (contatori)
```json
"stats": [
  { "value": "...", "label": "...", "color": "..." }
]
```
- Contatori con valore, label e colore per differenziare

### 5. Categories (filtri a semaforo)
```json
"categories": [
  { "name": "...", "icon": "...", "count": N, "color": "..." }
]
```
- Badge colorati con conteggio → filtri visivi

### 6. CTA section
```json
"cta_section": {
  "heading": "...",
  "description": "...",
  "button_text": "...",
  "button_url": "..."
}
```
- Sezione CTA finale con link dedicato

### 7. Testimonials (recensioni)
```json
"testimonials": [
  { "name": "...", "city": "...", "text": "...", "rating": N }
]
```
- Recensioni utenti con città, testo e valutazione

### 8. Footer
```json
"footer": {
  "tagline": "...",
  "links": { "privacy": "...", "terms": "...", "contact": "..." },
  "social": [ { "name": "...", "url": "..." } ]
}
```

### 9. Route helpers (Sixteen-specific)
```json
{
  "list_url": "{{ route('container0.index',['container0' => 'tickets']) }}",
  "report_url": "{{ route('container0.view',['container0' => 'tickets','slug0' => 'create' ]) }}",
  "track_url": "{{ route('container0.index',['container0' => 'tickets']) }}"
}
```
- **Lista**: usare `container0.index` per il mount Folio `[container0]/index`.
- **Creazione**: usare `container0.view` con `container0=tickets` e
  `slug0=create`; il mount `[container0]/[slug0]/index` risolve la pagina
  CMS `tickets.create`. `container0.create`, `container0.store` e
  `tickets.create` non sono il contratto di questa CTA.
- Usare Blade echo `{{ }}` nel JSON, non stringhe hardcodate.

## Blocco componenti previsti (Sixteen)

| Blocco | JSON section | Contenuto |
|---|---|---|
| `home.hero` | hero | Hero a due colonne (testo + mappa/CTA) |
| `home.features` | features | Griglia card |
| `home.stats` | stats | Contatori animati |
| `home.categories` | categories | Filtri visivi |
| `home.cta` | cta_section | CTA finale |
| `home.testimonials` | testimonials | Recensioni |
| `home.footer` | footer | Footer |

## Fonti consultate
- `Modules/Fixcity/database/content/pages/home.json` — schema di riferimento esistente
- `Themes/TwentyOne/resources/views/pages/index.blade.php` — pattern `x-cms::page`
- `Themes/TwentyOne/resources/views/components/blocks/home/*.blade.php` — blocchi wrapper

## Note BMAD
- Epic story: `theme-sixteen-twentyone-json-alignment.story.md`
- Second story: `theme-sixteen-nine11-homepage-json-alignment.story.md` (allineamento con pattern `tickets.confirmation.json`: multilingua, content_blocks, block-based)
- Hindsight initiative: `kp-0f0a55f6caae40179f5fe5d26e093f92`
