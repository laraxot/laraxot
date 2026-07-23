---
title: "Mapping moduli gestionale_commesse → replica singolare"
type: concept
tags: [gestionale-commesse, module-mapping, replica, singular, laraxot]
created: 2026-07-23
updated: 2026-07-23
qmd: "gestionale_commesse module mapping replica singular AiAssistant Auth Customer WorkOrder"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./glossary-align.md"
  - "./copy-then-normalize.md"
  - "../../laravel/Themes/docs/gestionale-panels-vs-themes.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
---

# Mapping — fase replica (singolare)

> **Perché:** porting = **replicare** i moduli SRC. Unica trasformazione di nome: **singolare**.  
> Colonna «overlap piattaforma» = nota analitica per **dopo**; non impedisce la replica.

## Fonte

- `/var/www/_bases/gestionale_commesse/Modules/*`
- UI SRC = multi panel Filament (nessun Themes package)

## Tabella master

| Modulo SRC | Replica (nome cartella) | Modelli (n) | Overlap piattaforma (fase **dopo**) |
|---|---|---:|---|
| Auth | **Auth** | 0 | nota vs `User` |
| HR | **HR** | 5 | nota vs `Employee` |
| Customers | **Customer** | 5 | — |
| WorkOrders | **WorkOrder** | 5 | ≠ `Job` |
| Interventions | **Intervention** | 7 | — |
| Quotations | **Quotation** | 5 | — |
| Catalog | **Catalog** | 7 | — |
| Documents | **Document** | 1 | nota vs `Media` |
| Signatures | **Signature** | 2 | — |
| Vehicles | **Vehicle** | 2 | — |
| Billing | **Billing** | 7 | — |
| Platform | **Platform** | 2 | nota vs Tenant/Activity/Xot |
| AiAssistant | **AiAssistant** | 4 | nota vs `AI` — **non** rinominare in AI |
| Inventory | **Inventory** | 5 | — |
| Bom | **Bom** | 2 | — |
| Production | **Production** | 2 | — |
| Compliance | **Compliance** | 9 | ≠ `Gdpr` |
| PublicProcurement | **PublicProcurement** | 15 | — |
| EnergyBroker | **EnergyBroker** | 6 | — |
| WhatsApp | **WhatsApp** | 3 | nota vs `Notify` |
| Email | **Email** | 3 | nota vs `Notify` |
| TimberBilling | **TimberBilling** | 4 | — |
| Fiscal | **Fiscal** | 0 | — |

## Regole

1. Ogni riga SRC → **una cartella replica** con nome singolare (o brand).
2. Vietato cancellare la replica perché esiste un modulo piattaforma «simile».
3. Vietato tenere enable sia plurale SRC sia singolare.
4. Temi: panels FO restano nei Themes; BL nei moduli — [gestionale-panels-vs-themes.md](../../laravel/Themes/docs/gestionale-panels-vs-themes.md).

## Priorità normalize (business)

1. `Customer` + `WorkOrder` + `Intervention`
2. `Quotation` + `Catalog`
3. `AiAssistant` + `Auth` + `HR` (repliche complete, non merge)
4. `Billing` / `Fiscal` / `Signature` / `Vehicle` / `Document`
5. Verticali e manufacturing
6. `Email` / `WhatsApp` / `Platform`
