---
title: "CLAUDE Overview"
type: concept
tags: [claude, overview]
created: 2026-07-14
updated: 2026-07-14
qmd: "claude-overview claude overview"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# CLAUDE Overview

Panoramica del progetto LaravelPizza/Base Predict Fila5.

## Progetto

**LaravelPizza** è una conversione e miglioramento di https://laravelpizza.com/ - rendendolo **PIÙ COOL, PIÙ CLICKBAIT, PIÙ ENGAGING**.

### Mission

> Prendere laravelpizza.com e renderlo **straordinario** - cooler design, più engaging UX, clickbait-worthy content.

**NON** è un progetto e-commerce pizza - è una piattaforma per Laravel developer meetups e tech communities.

---

## Stack Tecnologico

| Tecnologia | Descrizione |
|-----------|-------------|
| PHP 8.3+ | Strict typing |
| Laravel 12 | Latest |
| Filament v3/v4 | Admin/backend |
| Nwidart Modules | Modular architecture |
| Folio + Volt | Front-office |
| Livewire v3 | → v4 per Filament 5 |
| Pest PHP | Testing |
| PHPStan Level 10 | Static analysis |
| Tailwind CSS v3 | → v4 per Filament 5 |

---

## Architettura

```
laravel/
├── Modules/               # Business logic
│   ├── Activity/
│   ├── Cms/
│   ├── Geo/
│   ├── Lang/
│   ├── Meetup/
│   ├── Tenant/
│   ├── User/
│   └── Xot/
├── Themes/                # Frontend
│   └── Meetup/
└── composer.json
```

Ogni modulo:
```
Modules/{ModuleName}/
├── app/
│   ├── Actions/
│   ├── Datas/
│   ├── Filament/
│   └── Models/
├── docs/
├── database/migrations/
└── composer.json
```

---

## Principi Core

- **Folio + Volt** per tutte le pagine front-office (NO controller/routes tradizionali)
- **CMS-driven content** con JSON e block components
- **Filament** solo per admin/backend
- **Modular architecture** via nwidart/laravel-modules
- **PHPStan level 10** strict type safety
- **XotBase pattern** per tutte le estensioni Filament

---

## 🔗 Link

- [Indice CLAUDE](./claude-split-index.md)
- [claude.md originale](../../claude.md)
- [Index principale](./index.md)
