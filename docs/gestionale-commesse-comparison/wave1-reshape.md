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

## Matrice gap (aggiornata 2026-07-23 — BaseModel OK)

| Item | Customers | WorkOrders | Interventions |
|---|---|---|---|
| Layout `app/` | OK | OK | OK |
| Composer → `app/` | OK | OK | OK |
| XotBaseServiceProvider | OK | OK | OK |
| Actions → QueueableAction | OK (8) | OK (14) | OK (23) |
| Resources CRUD → XotBaseResource | OK | OK | OK (Intervention, DailyNote) |
| Filament Pages custom (calendar/schedule) | n/a | n/a | TODO |
| BaseModel / XotBaseModel | OK (`customers`) | OK (`work_orders`) | OK (`interventions`) |
| Factories | TODO (0) | TODO (0) | TODO (0) |
| Migrations 1/model | OK (1 file) | TODO (2 file) | TODO (14 file) |
| PHPStan L10 + enable | TODO (`Themes/Two`) | TODO | TODO |

### Shim AccountFeatures

`laravel/app/Support/AccountFeatures.php` (+ `Concerns/HasAccountFeatureVisibility`) — permissivo, per call site SRC. Porting → Tenant/User in story dedicata.

## Ordine suggerito in-modulo

1. ~~`BaseModel`~~  
2. ~~Switch models~~  
3. ~~Resources → XotBaseResource~~  
4. Factories/Seeders  
5. Consolidate migrations (dove serve)  
6. Pest + PHPStan  
7. `modules_statuses=true`

## Rename (Fase C — dopo enable)

`Customers`→`Customer`, `WorkOrders`→`WorkOrder`, `Interventions`→`Intervention` = story separata (evita churn durante XotBase).

## FO / Themes

Flussi operator (timbratura, firma) restano Filament panel finché non Folio. Nota: [wave1-fo-operator-note.md](../../laravel/Themes/docs/wave1-fo-operator-note.md).
