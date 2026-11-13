---
title: "Filament Resource: Schemas e Tables (tema Zero)"
type: guide
description: "Convenzione Schemas/Tables Filament per tema Zero; eccezione scheda BaseSchedaForm/Infolist."
status: stable
tags: [filament, schemas, tables, theme-zero]
module: "Themes/Zero"
created: 2026-07-14
updated: 2026-09-01
qmd: "filament resource schemas tables tema zero BaseSchedaForm BaseSchedaInfolist"
related:
  - ../../Modules/Ptv/docs/scheda-resource-pages-inheritance.md
  - ../../Modules/IndennitaResponsabilita/docs/base-scheda-form-inheritance.md
  - ../../Modules/IndennitaResponsabilita/docs/base-scheda-infolist-inheritance.md
  - ../One/docs/filament-resource-schemas-tables.md
  - ../../../docs/wiki/rules/markdown-file-naming-and-frontmatter.md
id: zero-docs-bmad-filament-resource-schemas-tables
slug: filament-resource-schemas-tables
document_type: guide
category: database
issues: []
discussions: []
---

# Filament Resource: Schemas e Tables (tema Zero)

## Scopo

Risorse Filament nel tema Zero (o che estendono classi del tema) devono separare form, infolist e tabella in classi dedicate sotto `Schemas/` e `Tables/`, come nel [demo Filament 5](https://github.com/filamentphp/demo/tree/5.x/app/Filament/Resources/Blog/Categories).

## Struttura

```
Themes/Zero/app/Filament/Resources/{ResourceName}/
├── Schemas/
│   ├── {Entity}Form.php
│   └── {Entity}Infolist.php
├── Tables/
│   └── {Entities}Table.php
└── {ResourceName}.php
```

## Regole

1. `XotBaseResourceForm` / `XotBaseResourceInfolist` / `XotBaseResourceTable` — mai Filament diretto.
2. Traduzioni automatiche: **no** `->label()`, `->placeholder()`, `->helperText()`.
3. `getFormSchema()`, `getInfolistSchema()`, `getTableColumns()` → `array<string, ...>`.
4. **`getPages()`:** non dichiarare se solo `index` / `create` / `edit` con Page `List{plural}`, `Create{name}`, `Edit{name}` — [getpages-redundancy-rule](../../../laravel/Modules/Xot/docs/filament/getpages-redundancy-rule.md).
4. **`getPages()`:** non dichiarare se solo `index` / `create` / `edit` con Page `List{plural}`, `Create{name}`, `Edit{name}` — [getpages-redundancy-rule](../../Modules/Xot/docs/filament/getpages-redundancy-rule.md).
4. **`getPages()`:** non dichiarare se solo `index` / `create` / `edit` con Page `List{plural}`, `Create{name}`, `Edit{name}` — [getpages-redundancy-rule](../../../laravel/Modules/Xot/docs/filament/getpages-redundancy-rule.md).
4. **`getPages()`:** non dichiarare se solo `index` / `create` / `edit` con Page `List{plural}`, `Create{name}`, `Edit{name}` — [getpages-redundancy-rule](../../Modules/Xot/docs/filament/getpages-redundancy-rule.md).

Per il dettaglio infolist vedi anche [filament-infolist-pattern](./filament-infolist-pattern.md).

### Copia metodi tabella Page → `*Table`

Spostando la config dalla Page alla `*Table`: `getHeaderActions()` → `getTableHeaderActions()`, `$this->getModel()` → FQCN esplicito, `$this->tableFilters ?? []`, niente `#[Override]`. **NON** creare metodi `return parent::getTableXxx();` (passthrough) o `return [];` (vuoto): equivalgono al default `HasXotTable` (DRY+KISS). Dettaglio: [Progressioni](../../../laravel/Modules/Progressioni/docs/filament-resource-schemas-tables.md#copia-metodi-tabella-page--classe-table-override-utili-vs-inutili).

## Riferimenti

- [Xot – Filament v5 hybrid pattern](../../../laravel/Modules/Xot/docs/wiki/concepts/filament-v5-hybrid-pattern.md)
- [Progressioni – inventario scaffold](../../../laravel/Modules/Progressioni/docs/filament-resource-schemas-tables.md)
Spostando la config dalla Page alla `*Table`: `getHeaderActions()` → `getTableHeaderActions()`, `$this->getModel()` → FQCN esplicito, `$this->tableFilters ?? []`, niente `#[Override]`. **NON** creare metodi `return parent::getTableXxx();` (passthrough) o `return [];` (vuoto): equivalgono al default `HasXotTable` (DRY+KISS). Dettaglio: [Progressioni](../../Modules/Progressioni/docs/filament-resource-schemas-tables.md#copia-metodi-tabella-page--classe-table-override-utili-vs-inutili).

## Riferimenti

- [Xot – Filament v5 hybrid pattern](../../Modules/Xot/docs/wiki/concepts/filament-v5-hybrid-pattern.md)
- [Progressioni – inventario scaffold](../../Modules/Progressioni/docs/filament-resource-schemas-tables.md)
Spostando la config dalla Page alla `*Table`: `getHeaderActions()` → `getTableHeaderActions()`, `$this->getModel()` → FQCN esplicito, `$this->tableFilters ?? []`, niente `#[Override]`. **NON** creare metodi `return parent::getTableXxx();` (passthrough) o `return [];` (vuoto): equivalgono al default `HasXotTable` (DRY+KISS). Dettaglio: [Progressioni](../../../laravel/Modules/Progressioni/docs/filament-resource-schemas-tables.md#copia-metodi-tabella-page--classe-table-override-utili-vs-inutili).

## Riferimenti

- [Xot – Filament v5 hybrid pattern](../../../laravel/Modules/Xot/docs/wiki/concepts/filament-v5-hybrid-pattern.md)
- [Progressioni – inventario scaffold](../../../laravel/Modules/Progressioni/docs/filament-resource-schemas-tables.md)
- [Progressioni – wire pilota Assenze](../../Modules/Progressioni/docs/filament-resource-wire-assenze.md)
- [One – stesso pattern](../../One/docs/filament-resource-schemas-tables.md)
- [Three – stesso pattern](../../Three/docs/filament-resource-schemas-tables.md)
- [Cursor rule](../../../.cursor/rules/filament-resource-schemas-tables.mdc)

*Ultimo aggiornamento: 2026-09-01*
