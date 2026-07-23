---
title: "CMS Block naming — Tailwind UI / Flowbite (Sixteen)"
type: rule
confidence: high
created: 2026-06-01
updated: 2026-06-10
tags: [cms, blocks, naming-convention, tailwind, flowbite, views, on-demand]
related:
  - ../how-to/blocks-subfolder-catalog.md
  - frontend-stack-canonical.md
  - no-italian-component-names.md
  - ../../../../../../docs/wiki/rules/011-blocks-view-convention.md
---

# CMS Block naming — Tailwind UI / Flowbite

## Regola (MANDATORY)

> Ogni sottocartella di `resources/views/components/blocks/` **deve** usare uno slug presente in
> [Flowbite Blocks](https://flowbite.com/blocks/) o [Tailwind Plus UI Blocks](https://tailwindcss.com/plus/ui-blocks).

- **Nuovi blocchi**: solo slug canonici (`BlockCategoryRegistry::CANONICAL_FOLDERS`)
- **Legacy**: cartelle dominio ammesse solo per retrocompatibilita CMS
- **Vietato**: nomi pagina/modulo (`ticket-layout`, `segnalazioni`, `homepage`)

## Trigger on-demand

Caricare questa regola quando:

- si crea/rinomina una cartella sotto `blocks/`
- si definisce `type` o `data.view` in JSON CMS
- si aggiunge un blocco Filament (`GetViewBlocksOptionsByTypeAction`)

## Cosa va dentro la cartella

1. **Varianti blade** (`default`, `layout`, `with-*`) dello stesso pattern UI
2. **Props** da CMS — niente business logic
3. **Riferimento** nel commento header al pattern Flowbite/Tailwind

Vedi catalogo: [blocks-subfolder-catalog.md](../how-to/blocks-subfolder-catalog.md)

## SSoT codice

`Themes\Sixteen\Support\BlockCategoryRegistry`

Test: `Themes/Sixteen/tests/Unit/BlockSubfolderNamingTest.php`

## Anti-pattern

```
// SBAGLIATO
blocks/ticket-layout/
blocks/governance-calendario/
blocks/segnalazioni/

// CORRETTO
blocks/layout/
blocks/calendar/
blocks/grid/
```

## Migrazione

| Legacy | Canonico |
|--------|----------|
| `ticket-layout` | `layout` / `sidebar` |
| `ticket-list` | `grid` |
| `contacts` | `contact` |
| `feature_sections` | `features` |

Non eliminare legacy finche referenziati in JSON CMS.

## Collegamenti

- [011-blocks-view-convention](../../../../../../docs/wiki/rules/011-blocks-view-convention.md)
- [frontend-stack-canonical.md](frontend-stack-canonical.md)
