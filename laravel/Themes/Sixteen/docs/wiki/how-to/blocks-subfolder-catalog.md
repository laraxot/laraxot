---
title: "Catalogo sottocartelle blocks/ (Flowbite + Tailwind UI)"
type: how-to
confidence: high
created: 2026-06-10
updated: 2026-06-10
tags: [blocks, naming, flowbite, tailwind, sixteen, cms]
related:
  - ../rules/cms-block-naming-tailwind-flowbite.md
  - ../rules/frontend-stack-canonical.md
  - ../../../../../../docs/wiki/rules/011-blocks-view-convention.md
  - ../concepts/ai-harness-theme-sixteen.md
---

# Catalogo sottocartelle `blocks/`

## Perche

Le sottocartelle di `resources/views/components/blocks/` sono il **tipo semantico universale** del blocco CMS.
Devono allinearsi al vocabolario di [Flowbite Blocks](https://flowbite.com/blocks/) e [Tailwind Plus UI Blocks](https://tailwindcss.com/plus/ui-blocks).

Non usare nomi di pagina, modulo o dominio PA come cartella (`ticket-layout`, `segnalazioni`, `homepage`).

## Struttura

```
resources/views/components/blocks/
├── {categoria}/          ← slug Flowbite/Tailwind (kebab-case)
│   ├── default.blade.php ← variante base
│   ├── layout.blade.php  ← composizione multi-sezione
│   └── {variante}.blade.php
```

### Cosa mettere dentro

| Elemento | Regola |
|----------|--------|
| File `.blade.php` | Una variante visiva dello stesso pattern UI |
| Props | Solo dati di presentazione dal CMS |
| Include | Altri blocchi/componenti tema, non query business |
| CSS | Tailwind / DaisyUI / Flowbite |
| Header comment | Link alla sezione Flowbite/Tailwind di riferimento |

## Slug canonici (nuovi blocchi)

SSoT codice: `Themes\Sixteen\Support\BlockCategoryRegistry::CANONICAL_FOLDERS`.

Esempi: `hero`, `grid`, `cta`, `tabs`, `filters`, `vertical-navigation`, `feedback`, `breadcrumb`, `layout`, `sidebar`, `modal`, `form`, `faq`, `stats`, `newsletter`, `footer`, `error`.

Tabella completa e mapping legacy nel registry PHP e nella regola `rules/cms-block-naming-tailwind-flowbite.md`.

## Cartelle legacy

Solo manutenzione, non crearne di nuove: `ticket`, `ticket-layout`, `ticket-list`, `tests`, `design-comuni`, `flow`, ...

| Legacy | Target canonico |
|--------|-----------------|
| `ticket-layout` | `layout` o `sidebar` |
| `ticket-list` | `grid` + `filters` |
| `contacts` | `contact` |
| `feature_sections` | `features` |

## Contratto CMS

```json
{
  "type": "hero",
  "data": {
    "view": "pub_theme::components.blocks.hero.layout",
    "title": "fixcity::ticket.heading.title.label"
  }
}
```

## Verifica

```bash
cd laravel && ./vendor/bin/pest Themes/Sixteen/tests/Unit/BlockSubfolderNamingTest.php
```

## Collegamenti

- [Regola on-demand](../rules/cms-block-naming-tailwind-flowbite.md)
- [011-blocks-view-convention](../../../../../../docs/wiki/rules/011-blocks-view-convention.md)
- [Registry PHP](../../../app/Support/BlockCategoryRegistry.php)
