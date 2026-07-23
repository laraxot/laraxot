---
title: "Audit conformità Laraxot — replica singolare"
type: concept
tags: [gestionale-commesse, audit, laraxot, singular, replica]
created: 2026-07-23
updated: 2026-07-23
qmd: "audit laraxot replica singular AiAssistant Auth WorkOrder Customer"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./module-mapping.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../../laravel/Themes/docs/module-vs-theme-boundaries.md"
---

# Audit conformità — fase replica

> Snapshot aggiornato alla religione **replica + singolare**. Canon: [replica-singular-phase.md](./replica-singular-phase.md).

## Religione

| Principio | Perché |
|---|---|
| **Replica SRC** | Nessuna collisione di path che obblighi il merge |
| **Nome = singolare** | Una cartella per dominio; niente `WorkOrders`+`WorkOrder` |
| **`app/` + XotBase** | Struttura Laraxot |
| **Piattaforma coesiste** | `User`/`AI`/… restano; le repliche Auth/AiAssistant/… vivono accanto |
| **Enable post-gate** | PHPStan L10 + docs |

## Repliche attese (singolare)

`Auth`, `HR`, `Customer`, `WorkOrder`, `Intervention`, `Quotation`, `Catalog`, `Document`, `Signature`, `Vehicle`, `Billing`, `Platform`, **`AiAssistant`**, `Inventory`, `Bom`, `Production`, `Compliance`, `PublicProcurement`, `EnergyBroker`, `WhatsApp`, `Email`, `TimberBilling`, `Fiscal`

## Plurali residui (da rimuovere dopo merge contenuto → singolare)

Se compaiono `WorkOrders`, `Interventions`, `Quotations`, `Signatures`, `Vehicles`, `Documents`, `Customers`: **non** enable; portare BL nel singolare e cancellare il plurale.

## Checklist modulo replica

1. Nome cartella singolare (o brand)
2. `app/` + manifest nwidart + `docs/`
3. Models → BaseModel/XotBase; Resources → XotBaseResource
4. N model ⇒ N factory + seeder + `create_*`
5. `modules_statuses` true solo post PHPStan

## Temi

Nessuna BL di dominio nei Themes. Panel FO = Theme + widget dai **moduli replica** — [Themes/docs](../../laravel/Themes/docs/copy-normalize-modules-vs-themes.md).  
AiAssistant FO: [aiassistant-vs-themes.md](../../laravel/Themes/docs/aiassistant-vs-themes.md).

## Incident AiAssistant (2026-07-23)

1. Cartella cancellata 2× da agenti (vecchia regola «no gemelli»).
2. Chiave `AiAssistant` **rimossa** da `modules_statuses.json` → invisibile a nwidart.
3. Fix: cartella ripristinata; chiave reinserita `"AiAssistant": false`.
4. Docs: [visibility.md](../../laravel/Modules/AiAssistant/docs/visibility.md).

**`false` ≠ cancellato.** Enable solo post reshape + ordine utente.
