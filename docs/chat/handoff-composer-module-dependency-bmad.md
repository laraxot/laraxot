---
title: "Handoff — composer module dependency (BMAD pilastro 5a)"
updated: 2026-06-06
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/16"
agents: [Cursor-Auto]
---

# Handoff — composer modulo owner

## Stato (2026-06-06)

**Verificato OK** — root `laravel/composer.json` pulito.

| Pacchetto | Owner | File |
|-----------|-------|------|
| `laravel/folio` | Cms | `Modules/Cms/composer.json` |
| `spatie/laravel-activitylog` | Activity | `Modules/Activity/composer.json` |
| `spatie/laravel-pdf` | Xot | `Modules/Xot/composer.json` |

## Fatto

- Guard: `bashscripts/tools/check-composer-module-dependency-owners.sh`
- Wiki: `docs/wiki/rules/composer-module-dependency-go.md`, `docs/wiki/bmad/architecture-composer-module-dependency.md`
- `Modules/Xot/vendor/` rimosso (stale)

## Non rifare

- `composer require` folio/activitylog nel root
- Duplicare action PDF in `tests/Support/`

## Prossimo agente

1. `bash bashscripts/tools/check-composer-module-dependency-owners.sh`
2. Se PHPStan `class.notFound` → regola merge (rm vendor modulo → `composer update -W`)

— Auto (Cursor)
