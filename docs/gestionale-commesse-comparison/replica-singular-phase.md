---
title: "Fase replica — copia SRC + nome singolare"
type: concept
tags: [gestionale-commesse, replica, singular, phase, laraxot]
created: 2026-07-23
updated: 2026-07-24
qmd: "replica singular phase copy modules AiAssistant Auth no merge User AI enabled phpstan"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./module-mapping.md"
  - "./agent-errors-lessons.md"
  - "./session-lessons-learned.md"
  - "./copy-then-normalize.md"
  - "./laraxot-compliance-audit.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../wiki/rules/replica-singular-no-merge-delete.md"
  - "../wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md"
  - "../../laravel/Themes/docs/copy-normalize-modules-vs-themes.md"
---

# Fase attuale: replica + nome singolare

> **Religione (perché):** in questa fase **non** c’è problema di collisione di cartelle nwidart. Si **replicano** i moduli da `gestionale_commesse`. L’unica differenza intenzionale sul nome: **PascalCase singolare**.

## Cosa fare (fase A–B)

| Fare | Non fare |
|---|---|
| Copiare ogni modulo SRC in `laravel/Modules/{NomeSingolare}` | Cancellare un modulo perché «mappa su User/AI/Media» |
| Rinominare `Customers`→`Customer`, `WorkOrders`→`WorkOrder`, … | Fondere subito Auth→User, AiAssistant→AI, Documents→Media |
| Normalizzare layout a `app/` + XotBase | Tenere due cartelle plurale+singolare enable insieme |
| Enable **solo** dopo gate PHPStan + smoke | Trattare la replica come «twin illegale» |

## Tabella rename (unica trasformazione di nome)

| SRC (plurale o già singolare) | Replica Laraxot (singolare) |
|---|---|
| Customers | **Customer** |
| WorkOrders | **WorkOrder** |
| Interventions | **Intervention** |
| Quotations | **Quotation** |
| Signatures | **Signature** |
| Vehicles | **Vehicle** |
| Documents | **Document** |
| Auth, HR, Email, WhatsApp, Platform, AiAssistant, Billing, … | **stesso nome** (già singolare / brand) |

`AiAssistant`, `Auth`, `HR`, `Email`, `WhatsApp`, `Platform` **restano moduli propri**. Coesistono con `AI`, `User`, `Employee`, `Notify`, `Media` senza merge.

## Cosa viene dopo (fase successiva — non ora)

Analisi gap e eventuale allineamento con piattaforma (`User`/`AI`/…) è **wave successiva**. Non giustifica delete della replica.

## Anti-pattern già visti

1. Cancellare `AiAssistant` perché mapping diceva `→ AI`
2. Non creare Auth/HR perché «esistono User/Employee»
3. Confondere **collisione di path** (Customers vs Customer) con **overlap funzionale** (Auth vs User)

Canon naming: [module-name-singular-mandatory.md](../wiki/rules/module-name-singular-mandatory.md).

## Progress reshape + quality (2026-07-24)

| Modulo | `app/` | PHPStan L10 | `modules_statuses` | Note |
|---|---|---|---|---|
| Customer | OK | **0 reali** | **true** | Pest models/SP OK |
| WorkOrder | OK | **0 reali** | **true** | Soft PDF/DOCX bridge |
| Intervention | OK | **0 reali** | **true** | |
| Quotation | OK | **0 reali** | **true** | |
| Billing | OK | **0 reali** | **true** | |
| Catalog | OK | **0 reali** | **true** | `config/metel.php` no `env()` |
| AiAssistant | OK | **0 reali** | **true** | Neuron stub `static` fluent |
| Vehicle | OK | **0 reali** | **true** | |
| Signature | OK | **0 reali** | **true** | |
| Inventory | OK | **0 reali** | **true** | |
| Production | OK | **0 reali** | **true** | |
| Bom | OK | **0 reali** | **true** | |
| Fiscal | OK | **0 reali** | **true** | |
| Document | OK | — | **false** | replica; non merge Media |
| Auth / HR / Email / WhatsApp / Platform / Compliance / EnergyBroker / PublicProcurement / TimberBilling | OK reshape | — | **false** | enable solo con ordine |

Deep quality BMAD: [2.3.deep-quality-enabled-modules.story.md](../wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md) · Issue [#14](https://github.com/laraxot/base_workorder_fila5/issues/14) · Discussion [#15](https://github.com/laraxot/base_workorder_fila5/discussions/15).
