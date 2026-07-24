---
title: "Tests migration — Intervention"
type: concept
tags: [tests, pest, intervention, gestionale-commesse]
created: 2026-07-24
updated: 2026-07-24
qmd: "Intervention tests migration SRC Platform skip CreateIntervention"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "./import-status.md"
  - "../../../../laravel/docs/migrate-gestionale-tests.md"
  - "../../../../laravel/Themes/docs/wave1-fo-operator-note.md"
---

# Tests migration — Intervention

## Perché

SRC `Feature/Interventions` (~23) + `Unit` (2) = cuore operator (timbrature, report, calendar, voice).

## Stato

| Voce | Valore |
|---|---|
| DEST | Feature+Unit copiati/adattati |
| `uses()` | FQCN `Modules\Intervention\Tests\TestCase` |
| Platform | Test che importano `Modules\Platform\…` → **skip** finché replica Platform |
| Smoke | `CreateInterventionTest`: create **pass**, normalize **skip**, extractPersistable **pass/skip metodo** |
| Themes | Operator ancora Filament — [wave1-fo-operator-note.md](../../../../laravel/Themes/docs/wave1-fo-operator-note.md) |

## Come continuare

1. Grep `Platform\\` in `tests/` → skip o stub.
2. Panel: `filament()->setCurrentPanel('intervention')` non `admin`.
3. Pest suite intera solo dopo skip stabili.
