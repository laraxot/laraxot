---
type: overview
module: Blog
sources:
  - ../../../README.md
  - ../../../blocks.md
  - ../../../structure.md
  - ../../../pages.md
confidence: high
updated: 2026-04-15
---

# Blog Module — Overview

> **Ruolo**: Content management editoriale — articoli, categorie, tag, banner, commenti, editor visuale WYSIWYG, blocchi CMS.

## Responsabilità del Modulo

Il modulo Blog gestisce contenuti editoriali strutturati:

- Articoli e post con categorizzazione e tagging
- Blocchi di contenuto modulari (content blocks system — integra con Cms)
- Visual editor WYSIWYG
- Sistema commenti per interazione utenti
- SEO-friendly pages con slug multilingua
- Supporto banner
- Integrazione AI per content assistance
- Filament admin panel per gestione editoriale

## Modelli Core

| Modello | Scopo | Note |
|---------|-------|------|
| `Post` / `Article` | Articolo/post principale | Con slug, content, status |
| `Category` | Categorizzazione articoli | Hierarchical |
| `Tag` | Tagging flessibile | HasMany Post |
| `Banner` | Banner promozionali | Con immagini e link |
| `Comment` | Commenti articoli | Vedi Comment module |

**Nota**: `Transaction` model disabilitato (2025-10-15) — non necessario per FixCity.

## Content Blocks System

Il Blog integra il sistema CMS content_blocks:

```php
// Blocchi disponibili nel Blog:
// - article_list: lista articoli
// - leaderboard: classifica
// - rating: sistema voti
// - ticket_list: lista ticket (cross-module Fixcity)
// - chart: grafici
// - headernav: navigazione
// - footer: footer
```

Le view dei blocchi risiedono in:
`Themes/{pub_theme}/resources/views/components/blocks/`

## Struttura Modulo

```
Blog/
├── app/
│   ├── Actions/        # Business logic actions
│   ├── Filament/       # Resources, Pages, Widgets
│   ├── Models/         # Post, Category, Tag, Banner
│   ├── Services/       # ContentService, etc.
│   └── DataObjects/    # Spatie Data Objects
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/    # Blade templates
└── lang/               # Traduzioni multilingua
```

## Parental STI Architecture

Il Blog usa Single Table Inheritance per varianti di contenuto:

```php
// Articoli con tipi diversi condividono la stessa tabella
// ma hanno comportamenti differenti via STI
// Vedi: docs/parental-sti-architecture.md
```

## Integrazione AI

```php
// AI content assistance (via AI module)
// - Generazione bozze articoli
// - Suggerimenti SEO
// - Sintesi automatica
// Vedi: docs/ai.md
```

## Filament Admin

- `/admin/posts` — gestione articoli
- `/admin/categories` — gestione categorie
- `/admin/tags` — gestione tag
- `/admin/banners` — gestione banner
- Visual editor integrato in Filament (blocchi CMS)

## Dipendenze Cross-Module

| Modulo | Uso |
|--------|-----|
| `Xot` | `XotBaseModel`, `XotBaseServiceProvider` |
| `Cms` | Content blocks, Folio routing |
| `Seo` | Meta tag articoli |
| `Media` | Immagini articoli e banner |
| `Lang` | Auto-label, traduzioni |
| `AI` | Content assistance |

## Architettura

- PHPStan compliance in progressione (Level 2 → 10)
- Multi-lingua: traduzioni complete
- Segue pattern Laraxot (XotBase, no `->label()`, DRY)
- `BaseModel` custom per tutti i modelli Blog

## Cross-References

- [[../../../../../../laravel/Modules/Cms/docs/wiki/overviews/cms-module|Cms Module]] — content blocks foundation
- [[../../../../../../laravel/Modules/Seo/docs/wiki/index|Seo Module]] — SEO per articoli
- [[../../../../../../laravel/Modules/Media/docs/wiki/overviews/media-module|Media Module]] — immagini e allegati

## Raw Sources Prioritari

- `README.md` — feature list, struttura, modelli disabilitati
- `blocks.md` — content blocks system
- `pages.md` — routing pagine, SEO integration
- `structure.md` — directory layout
- `parental-sti-architecture.md` — STI pattern
- `ai.md` — AI content assistance
