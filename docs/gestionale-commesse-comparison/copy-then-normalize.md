---
title: "Copy-then-normalize — replica singolare"
type: concept
tags: [gestionale-commesse, copy, normalize, replica, singular]
created: 2026-07-23
updated: 2026-07-23
qmd: "copy then normalize replica singular status Customer WorkOrder Billing app XotBase"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./module-mapping.md"
  - "./laraxot-compliance-audit.md"
  - "./agent-errors-lessons.md"
  - "../wiki/concepts/module-structure-laraxot-canon.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
---

# Copy-then-normalize (fase replica)

> Canon: [replica-singular-phase.md](./replica-singular-phase.md).  
> Replica SRC + nome **singolare** + reshape `app/` + XotBase SP.

## Stato 2026-07-23 (sera)

| Cluster | Moduli | Layout `app/` | NS singolare | XotBase SP | statuses |
|---|---|---|---|---|---|
| Cuore | Customer, WorkOrder, Intervention | sì | sì | sì | `false` |
| Preventivi/flotta | Quotation, Vehicle, Signature, Document | sì | sì | sì | `false` |
| Manufacturing | Billing, Bom, Catalog, Compliance, Inventory, Production, PublicProcurement, TimberBilling, EnergyBroker, Fiscal | sì (reshape batch) | sì | sì | `false` |
| Repliche piattaforma | AiAssistant, Auth, HR, Email, WhatsApp, Platform | sì | sì | sì | `false` |

### Fatto in questo passo

1. Reshape DDD root → `app/` (Billing, Bom, Catalog, Compliance, …)
2. Namespace plurale SRC → singolare (`Modules\Customer\…`)
3. `{Name}ServiceProvider` → `XotBaseServiceProvider` + Route/Event XotBase
4. `composer.json` PSR-4 → `app/` + authors Marco+Davide
5. `modules_statuses.json`: 23 chiavi singolari a `false` (niente plurali)

### Residuo (wave successiva)

- Filament `extends Resource` → `XotBaseResource` (moduli non-cuore)
- BaseModel + connection per modulo
- PHPStan L10 batch successivo: Quotation, Billing, Catalog, …
- Cuore **Customer / WorkOrder / Intervention**: PHPStan OK (0 reali)
- Opzionale root: `composer require barryvdh/laravel-dompdf phpoffice/phpword`

## Non in questa fase

Cancellare AiAssistant/Auth/… o merge in AI/User — [agent-errors-lessons.md](./agent-errors-lessons.md).
