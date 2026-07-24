---
title: "Mappatura moduli gestionale → panel Filament"
type: concept
tags: [themes, filament, panels, gestionale-commesse]
created: 2026-07-23
updated: 2026-07-24
qmd: "new modules panel mapping workorder quotation intervention admin urls Auth Document"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-docs-index.md"
  - "./gc-modules-runtime-matrix.md"
  - "./gestionale-panels-vs-themes.md"
  - "./wave1-fo-operator-note.md"
  - "../../../docs/gestionale-commesse-comparison/functional-readiness.md"
---

# Mappatura moduli gestionale → panel Filament

> Panel Filament vivono nei **moduli**, non nei Themes. Matrice enable: [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md).

## URL runtime (cuore field-service)

| Modulo | Base panel | Note |
|---|---|---|
| Customer | `/customer/admin` | customers, resellers |
| WorkOrder | `/workorder/admin` | work-orders |
| Intervention | `/intervention/admin` | interventions, calendar, voice |
| Quotation | `/quotation/admin` | quotations, templates |
| Signature | `/signature/admin` | CaptureSignature / CaptureSketch |
| Vehicle | `/vehicle/admin` | vehicles |
| AiAssistant | `/aiassistant/admin` | chat + settings |

## Altri moduli `statuses=true` (2026-07-24)

| Modulo | Panel dedicato | Attenzione |
|---|---|---|
| Billing, Catalog, Inventory, Production, Bom, Fiscal | resources nei propri provider / panel host | Fiscal = motore, poco UI |
| Auth, HR, Email, WhatsApp, Platform | provider/routes SRC; overlap User/Employee/Notify | **non** merge; smoke dedicato |
| Document | — | `statuses=true` ma **conflitto tabella `media`** — non migrate cieco |

## Ancora `false`

Compliance, EnergyBroker, PublicProcurement, TimberBilling — enable solo con gate + ordine.

## Temi FO

Wave 1: nessuna page Folio operator. Vedi [wave1-fo-operator-note.md](./wave1-fo-operator-note.md).

## Vedi anche

- [gestionale-docs-index.md](./gestionale-docs-index.md)
- [functional-readiness.md](../../../docs/gestionale-commesse-comparison/functional-readiness.md)
- `laravel/Modules/*/docs/README.md`
