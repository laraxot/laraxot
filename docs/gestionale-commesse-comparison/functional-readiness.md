---
title: "Functional readiness — wave field-service"
type: concept
tags: [gestionale-commesse, migrate, filament, readiness, bmad]
created: 2026-07-24
updated: 2026-07-24
qmd: "functional readiness migrate panels Customer WorkOrder Quotation Intervention"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./reshape-completion-report.md"
  - "./replica-singular-phase.md"
  - "./agent-errors-lessons.md"
  - "./laraxot-compliance-audit.md"
  - "../../laravel/Themes/docs/wave1-fo-operator-note.md"
  - "../../laravel/Themes/docs/copy-normalize-modules-vs-themes.md"
  - "../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
  - "../sprint-status.yaml"
  - "../wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md"
  - "../wiki/skills/bmad-create-story/stories/2.4.module-theme-docs-hygiene.story.md"
---

# Functional readiness — wave field-service

> Scopo: **schema DB + panel Filament** operativi per il cuore field-service. Non è merge Auth/User. Document: vedi rischio `media` sotto.  
> Matrice statuses: [gc-modules-runtime-matrix.md](../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## Verdetto (2026-07-24)

| Gate | Stato | Evidenza |
|---|---|---|
| Moduli enabled | OK | Customer, WorkOrder, Intervention, Quotation, Signature, Vehicle, AiAssistant, Catalog, Billing, Inventory, Production, Bom, Fiscal |
| Document | **true (JSON)** | **RISCHIO** tabella `media` Spatie — non migrate cieco; vedi moduli Document docs |
| Moduli Wave3 Auth/HR/Email/WhatsApp/Platform | true | Replica coesistente; non merge User/Employee/Notify |
| Migrazioni wave (path-scoped) | OK | Tabelle core presenti; **non** `migrate --force`; evitare `migrate` globale |
| Panel Filament | OK | `*/admin` cuore field-service; mapping [Themes](../../laravel/Themes/docs/new-modules-panel-mapping.md) |
| PHPStan L10 cuore | OK | Customer…Fiscal — 0 reali · [#14](https://github.com/laraxot/base_workorder_fila5/issues/14) |
| Matrice statuses Themes | OK | [gc-modules-runtime-matrix.md](../../laravel/Themes/docs/gc-modules-runtime-matrix.md) |
| FK `users.id` | Mitigato | `users.id` = `varchar(36)` → colonne user = `string(36)` **senza** `constrained('users')` |
| Pest smoke | Parziale | Customer + WorkOrder + Fiscal; resto aperto |
| Soft DomPDF/PhpWord | Aperto | `composer require` umano |

## Ordine migrate sicuro (path)

```bash
cd laravel
php artisan migrate --path=Modules/Catalog/database/migrations --no-interaction
php artisan migrate --path=Modules/Customer/database/migrations --no-interaction
php artisan migrate --path=Modules/WorkOrder/database/migrations --no-interaction
php artisan migrate --path=Modules/Quotation/database/migrations --no-interaction
php artisan migrate --path=Modules/Intervention/database/migrations --no-interaction
php artisan migrate --path=Modules/Signature/database/migrations --no-interaction
php artisan migrate --path=Modules/Vehicle/database/migrations --no-interaction
php artisan migrate --path=Modules/AiAssistant/database/migrations --no-interaction
php artisan migrate --path=Modules/Billing/database/migrations --no-interaction
```

## Panel: manifest obbligatorio

`AdminPanelProvider` in **entrambi**:

- `module.json` → `providers[]`
- `composer.json` → `extra.laravel.providers[]`

Poi `composer dump-autoload` + `php artisan package:discover`.

## Gap aperti (non bloccano CRUD base)

1. Document `Media` vs Spatie `media` — stessa tabella, schema diverso.
2. VehicleDocument FK verso Spatie `media` (semantica diversa dal Document SRC).
3. Auth/HR ancora disabled (replica, non merge).
4. ~100 migrazioni legacy Pending fuori wave — **non** lanciare migrate globale senza triage.
5. Themes FO operator = ancora Filament panel (vedi [wave1-fo-operator-note.md](../../laravel/Themes/docs/wave1-fo-operator-note.md)).

## BMAD

- Stories reshape wave → `review` / `done` in [sprint-status.yaml](../sprint-status.yaml)
- Issue [#7](https://github.com/laraxot/base_workorder_fila5/issues/7) · Discussion [#8](https://github.com/laraxot/base_workorder_fila5/discussions/8)
