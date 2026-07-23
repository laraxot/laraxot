---
title: "No Repository pattern"
type: rule
module: Xot
tags: [xot, laraxot, architecture, repository, actions, ponytail]
created: 2026-07-09
updated: 2026-07-09
qmd: "Laraxot no repository pattern Actions Eloquent QueryBuilder no Repositories folder"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/28"
discussions:
  - "https://github.com/laraxot/module_xot_fila5/discussions/29"
related:
  - ../../repositories.md
  - ../../../../../../docs/wiki/rules/no-services-rule.md
  - no-legacy-folders-code.md
---

# No Repository pattern

Laraxot **non** usa il Repository pattern per l'accesso ai dati di dominio.

## Perché

- Duplica Eloquent senza valore (YAGNI / Ponytail)
- Accoppia il container a contratti CRUD generici
- La business logic va nelle **Actions**, non in layer intermedi

## Cosa usare

| Bisogno | Dove |
|--------|------|
| Query riusabile | Scope sul modello o `app/QueryBuilders/*QueryBuilder.php` |
| Orchestrazione | `app/Actions/*Action.php` + `QueueableAction` |
| Paginazione/filtri FO | Spatie Query Builder sul modello o Action dedicata |

## Rimosso (2026-07-09, STORY-489)

- `Modules/Xot/app/Repositories/` (incluso `BaseRepository.php`)
- `Modules/Xot/app/Contracts/RepositoryContract.php`
- `Modules/Fixcity/app/Repositories/` + `TicketRepositoryContract.php`
- `Modules/Comment`, `Geo`, `Seo` — cartelle `app/Repositories/` (solo `.gitkeep`)
- Stub `repository.stub` (se presente in generatori)

Verifica: `bash bashscripts/tools/audit-no-repositories-folder.sh`

## Vietato reintrodurre

- Cartelle `app/Repositories/`
- `registerRepositories()` nei ServiceProvider
- Generatori/stub `*Repository`

## Documentazione storica

La pagina [repositories.md](../../repositories.md) resta come riferimento **deprecato** — non seguire quei pattern.

## Cursor rule

`.cursor/rules/no-repository-pattern.mdc`
