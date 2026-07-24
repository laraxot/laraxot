---
title: "Wave 1 reshape — Customers / WorkOrders / Interventions"
type: concept
tags: [gestionale-commesse, wave1, reshape, xotbase]
created: 2026-07-23
updated: 2026-07-23
qmd: "wave1 reshape XotBase Customers WorkOrders Interventions BaseModel Resource"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./copy-reshape-playbook.md"
  - "./roadmap.md"
  - "./composer-json-import.md"
  - "./README.md"
  - "../wiki/skills/bmad-create-story/stories/1.8.wave1-reshape-xotbase.story.md"
  - "../wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../laravel/Themes/docs/wave1-fo-operator-note.md"
---

# Wave 1 reshape — Customers / WorkOrders / Interventions

> Cuore field-service. Story eseguibile: [1.8.wave1-reshape-xotbase.story.md](../wiki/skills/bmad-create-story/stories/1.8.wave1-reshape-xotbase.story.md).

## Perché questi tre

Sbloccano Cliente → Commessa → Intervento. Senza XotBase su Model/Resource non si abilita il modulo in produzione Laraxot.

## Matrice gap (aggiornata 2026-07-23 sera — singolari)

> Canone cartella/NS: **Customer / WorkOrder / Intervention** (plurale SRC eliminato dal tree).

| Item | Customer | WorkOrder | Intervention |
|---|---|---|---|
| Layout `app/` | OK | OK | OK |
| Composer → `app/` | OK | OK | OK |
| XotBaseServiceProvider | OK | OK | OK |
| Actions → QueueableAction | OK (8) | OK (14) | OK (21) |
| Resources CRUD → XotBaseResource + `getFormSchema` | OK | OK | OK (Intervention, DailyNote) |
| Filament Pages custom (calendar/schedule) | n/a | n/a | TODO |
| BaseModel / XotBaseModel | OK | OK | OK |
| Factories | OK (5) | OK (5) | OK (8) |
| Migrations 1/model | OK (5 XotBase) | OK (5 XotBase) | OK (8 XotBase) |
| Artifact parity | OK | OK | OK |
| PHPStan L10 `app/` | **0** | **0** | **0** |
| Enable statuses | `false` (gate utente) | `false` | `false` |

Anche **Quotation / Signature / Vehicle / Document / AiAssistant**: parity OK + PHPStan L10 `app/` = **0**. CanvasPad FO copiato in `laravel/app/Filament/Forms/Components/`.

### Shim AccountFeatures

`laravel/app/Support/AccountFeatures.php` (+ `Concerns/HasAccountFeatureVisibility`) — permissivo, per call site SRC. Porting → Tenant/User in story dedicata.

## Ordine suggerito in-modulo

1. ~~`BaseModel`~~  
2. ~~Switch models~~  
3. ~~Resources → XotBaseResource~~  
4. ~~Factories/Seeders~~  
5. ~~Consolidate migrations~~  
6. ~~PHPStan L10~~  
7. `modules_statuses=true` (**solo ordine utente**) + Pest regressione

## FO / Themes

Flussi operator (timbratura, firma) restano Filament panel finché non Folio. Nota: [wave1-fo-operator-note.md](../../laravel/Themes/docs/wave1-fo-operator-note.md).
