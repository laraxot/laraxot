---
title: "composer.json sui moduli importati gestionale_commesse"
type: concept
tags: [composer, import, gestionale-commesse, nwidart]
created: 2026-07-23
updated: 2026-07-23
qmd: "composer.json import moduli Billing WorkOrders Customers laraxot merge-plugin"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./copy-reshape-playbook.md"
  - "./module-mapping.md"
  - "./README.md"
  - "../wiki/concepts/laraxot-module-canonical-structure.md"
  - "../wiki/concepts/composer-root-minimal-nwidart.md"
  - "../../laravel/Themes/docs/composer-modules-not-themes.md"
---

# composer.json sui moduli importati gestionale_commesse

> **Fatto:** ogni modulo “nuovo” (copia as-is) ha un `composer.json` Laraxot.  
> **Perché:** il merge-plugin root include solo `Modules/*/composer.json`.

## Moduli aggiornati

### Batch 16 (import iniziale)

Billing, Bom, Catalog, Compliance, Customers, Interventions, Inventory, Production, PublicProcurement, Quotations, Signatures, TimberBilling, Vehicles, WorkOrders, EnergyBroker, Fiscal

### Dopo reshape → `app/` + manifesto completo

- **Documents** — `laraxot/module_documents_fila5` (PSR-4 → `app/`, providers sync, path repos) — vedi [composer-manifest.md](../../laravel/Modules/Documents/docs/composer-manifest.md)
- **Vehicles** — già reshape + composer `app/`

## Pattern applicato

| Campo | Scelta |
|---|---|
| `name` | `laraxot/module_{alias}_fila5` |
| `extra.laravel.providers` | = `module.json` providers (sync obbligatorio) |
| `autoload` PSR-4 | dopo reshape: `Modules\{Name}\` → **`app/`**; pre-reshape: root `""` |
| `require` | solo `php: ^8.3` (salvo Media/Xot con pacchetti dedicati) |
| path repos | Xot, User, Tenant, UI (+ dipendenze dominio) |
| `scripts` / `config.allow-plugins` | come Billing (analyse, pest, pint) |

Documentazione per-modulo: `laravel/Modules/{Name}/docs/composer-manifest.md`.

## Target reshape

Ridurre autoload a `app/` come in [laraxot-module-canonical-structure.md](../wiki/concepts/laraxot-module-canonical-structure.md).

## Non toccare

- Root `laravel/composer.json` (skeleton)
- `Themes/*/composer.json` (non nel merge) — vedi [composer-modules-not-themes.md](../../laravel/Themes/docs/composer-modules-not-themes.md)
- Auth/HR (merge su User/Employee) finché restano staging senza reshape completo
