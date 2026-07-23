---
title: "Fase replica — copia SRC + nome singolare"
type: concept
tags: [gestionale-commesse, replica, singular, phase, laraxot]
created: 2026-07-23
updated: 2026-07-23
qmd: "replica singular phase copy modules AiAssistant Auth no merge User AI"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./module-mapping.md"
  - "./agent-errors-lessons.md"
  - "./session-lessons-learned.md"
  - "./copy-then-normalize.md"
  - "./laraxot-compliance-audit.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../wiki/rules/replica-singular-no-merge-delete.md"
  - "../wiki/skills/bmad-create-story/stories/1.10.agent-errors-replica-singular-guardrails.story.md"
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
| `modules_statuses=false` finché gate PHPStan | Trattare la replica come «twin illegale» |

## Tabella rename (unica trasformazione di nome)

| SRC (plurale o già singolare) | Replica Laraxot (singolare) |
|---|---|
| Customers | **Customer** |
| WorkOrders | **WorkOrder** |
| Interventions | **Intervention** |
| Quotations | **Quotation** |
| Signatures | **Signature** |
| Vehicles | **Vehicle** |
| Documents | **Document** (o tenere `Documents` solo se già copiato — target nome **Document**) |
| Auth, HR, Email, WhatsApp, Platform, AiAssistant, Billing, … | **stesso nome** (già singolare / brand) |

`AiAssistant`, `Auth`, `HR`, `Email`, `WhatsApp`, `Platform` **restano moduli propri** in questa fase. Coesistono con `AI`, `User`, `Employee`, `Notify`, `Media` senza merge.

## Cosa viene dopo (fase successiva — non ora)

Analisi gap e eventuale allineamento con piattaforma (`User`/`AI`/…) è **wave successiva**, documentata a parte. Non giustifica delete della replica.

## Anti-pattern già visti

1. Cancellare `AiAssistant` perché mapping diceva `→ AI`
2. Non creare Auth/HR perché «esistono User/Employee»
3. Confondere **collisione di path** (Customers vs Customer) con **overlap funzionale** (Auth vs User)

Canon naming: [module-name-singular-mandatory.md](../wiki/rules/module-name-singular-mandatory.md).
