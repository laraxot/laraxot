---
title: "Playbook copia + reshape — fase replica singolare"
type: concept
tags: [gestionale-commesse, copy, reshape, replica, singular, laraxot]
created: 2026-07-23
updated: 2026-07-23
qmd: "copy reshape replica singular gestionale AiAssistant Auth Customer WorkOrder"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./module-mapping.md"
  - "./composer-json-import.md"
  - "../wiki/concepts/laraxot-module-canonical-structure.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
---

# Playbook copia + reshape (replica + singolare)

> **Religione:** si **replicano** i moduli SRC. Unica differenza di nome: **PascalCase singolare**.  
> SSoT fase: [replica-singular-phase.md](./replica-singular-phase.md).

## Fase A — Copia / replica

| Azione | Dettaglio |
|---|---|
| Sorgente | `/var/www/_bases/gestionale_commesse/Modules/{Name}` |
| Destinazione | `laravel/Modules/{NomeSingolare}` |
| Status | `modules_statuses=false` finché gate |
| Docs | `docs/import-status.md` + `docs/README.md` |
| Composer | [composer-json-import.md](./composer-json-import.md) |

### Replicare **tutti** i 23 (inclusi Auth, HR, Documents, Email, WhatsApp, AiAssistant, Platform)

Niente «skip perché esiste User/AI/Media/Notify». Mapping: [module-mapping.md](./module-mapping.md).

### Rename nome (unica trasformazione)

`Customers`→`Customer`, `WorkOrders`→`WorkOrder`, `Interventions`→`Intervention`, `Quotations`→`Quotation`, `Signatures`→`Signature`, `Vehicles`→`Vehicle`, `Documents`→`Document`.  
Già singolari/brand: Auth, HR, Email, WhatsApp, Platform, AiAssistant, Billing, …

## Fase B — Reshape (ogni replica)

1. Albero → `app/` (niente Domain/Application/Infrastructure root)
2. PSR-4 `Modules\{Name}\` → `app/`
3. `BaseModel` → XotBaseModel; Resources → XotBaseResource; Actions → QueueableAction
4. Provider XotBase + lista in `module.json` / composer
5. 1 `create_*` + factory + seeder per model
6. lang it+en; Pest + XotBaseTestCase
7. Enable solo post PHPStan L10

## Fase C — Cleanup plurale

Se restano cartelle plurali (`WorkOrders`, …): portare BL nel singolare, poi **delete solo il plurale** — mai la replica singolare / Auth / AiAssistant.

## Anti-pattern (già visti)

- Cancellare `AiAssistant` / `Auth` / `HR` «perché mappa su AI/User/Employee»
- Non copiare un modulo SRC per «collisione funzionale»
- Enable plurale+singolare insieme
- Controllers FO SRC nei Themes

## Temi

BL nei moduli; Themes = vestito — [Themes/docs/copy-normalize-modules-vs-themes.md](../../laravel/Themes/docs/copy-normalize-modules-vs-themes.md).
