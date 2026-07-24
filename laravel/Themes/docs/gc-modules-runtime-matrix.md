---
title: "Matrice runtime moduli GC vs Themes"
type: concept
tags: [themes, gestionale-commesse, modules-statuses, bmad, runtime]
created: 2026-07-24
updated: 2026-07-24
qmd: "gc modules runtime matrix statuses enabled Auth Document media themes"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-docs-index.md"
  - "./new-modules-panel-mapping.md"
  - "./copy-normalize-modules-vs-themes.md"
  - "./module-vs-theme-boundaries.md"
  - "../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md"
---

# Matrice runtime moduli GC vs Themes

> SSoT **stato enable** letto da `laravel/modules_statuses.json` (2026-07-24). Themes non cambiano questi flag.  
> Religione: [replica-singular-phase.md](../../../docs/gestionale-commesse-comparison/replica-singular-phase.md).

| Modulo | `statuses` | Panel tipico | Note docs |
|---|---|---|---|
| Customer | true | `/customer/admin` | OK |
| WorkOrder | true | `/workorder/admin` | Soft PDF/DOCX |
| Intervention | true | `/intervention/admin` | OK |
| Quotation | true | `/quotation/admin` | OK |
| Billing | true | (resources in panel) | Pages XotBase wave |
| Catalog | true | (resources) | OK |
| AiAssistant | true | `/aiassistant/admin` | **non** merge AI; **non** nel Theme |
| Vehicle | true | `/vehicle/admin` | OK |
| Signature | true | `/signature/admin` | OK |
| Inventory | true | (resources) | OK |
| Production | true | (resources) | OK |
| Bom | true | (resources) | OK |
| Fiscal | true | n/a (motore calcolo) | OK Pest |
| Auth | true | overlap User | Replica; **non** merge User |
| HR | true | overlap Employee | Replica; **non** merge Employee |
| Email | true | — | Replica; overlap Notify |
| WhatsApp | true | — | Replica |
| Platform | true | settings/company | Replica |
| Document | **true** | — | **RISCHIO**: model `Media` → tabella `media` (Spatie). Migrate/enable cieco pericoloso |
| Compliance | false | — | Gate PHPStan/enable aperto |
| EnergyBroker | false | — | PHPStan ok; enable solo con ordine |
| PublicProcurement | false | — | Gate aperto |
| TimberBilling | false | — | Gate aperto |

## Themes

| Tema | Ruolo | BL gestionale |
|---|---|---|
| Sixteen | FO attivo | **No** — solo Folio/skin |
| Zero / TwentyOne / … | alternativi | **No** |

Canon hub: [gestionale-docs-index.md](./gestionale-docs-index.md) · Story [2.4](../../../docs/wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md).
