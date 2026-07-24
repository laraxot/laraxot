---
title: "Laravel docs — indice applicazione"
type: index
tags: [laravel, docs, index, technical-debt, gestionale]
created: 2026-07-24
updated: 2026-07-24
qmd: "laravel docs README index technical debt gestionale playbook"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "../AGENTS.md"
---

# Laravel docs

Documentazione **applicazione** (`laravel/`). Qui vive il **come risolvere** il debito del porting gestionale (solo docs; il codice si tocca dopo story BMAD).

Distinto da: `docs/gestionale-commesse-comparison/` (stato porting), `docs/wiki/` (second brain), `Modules/*/docs/`, `Themes/docs/`.

## Entrare dal debito tecnico

1. **[gestionale-technical-debt.md](./gestionale-technical-debt.md)** — inventario D1–D14 + ordine di attacco (**SSoT**).
2. Aprire il playbook `debt-*.md` della riga.
3. Commentare evidenza comandi su [Issue #14](https://github.com/laraxot/base_workorder_fila5/issues/14).

### Playbook per ID

| ID | Playbook |
|---|---|
| D1 | [debt-document-media-conflict.md](./debt-document-media-conflict.md) |
| D2 | [debt-vehicle-document-media.md](./debt-vehicle-document-media.md) |
| D3 | [debt-legacy-migrations-triage.md](./debt-legacy-migrations-triage.md) |
| D4 | [debt-users-uuid-foreign-keys.md](./debt-users-uuid-foreign-keys.md) |
| D5 | [debt-replica-auth-hr-enable.md](./debt-replica-auth-hr-enable.md) |
| D6 | [debt-pest-field-service-coverage.md](./debt-pest-field-service-coverage.md) |
| D7 | [debt-soft-deps-export.md](./debt-soft-deps-export.md) |
| D8 | [debt-fo-operator-folio.md](./debt-fo-operator-folio.md) |
| D9 | [debt-themes-docs-uppercase.md](./debt-themes-docs-uppercase.md) |
| D10 | [debt-login-route-collision.md](./debt-login-route-collision.md) |
| D11 | [debt-billing-pages-xotbase.md](./debt-billing-pages-xotbase.md) |
| D12 | [debt-auth-users-migration-duplicate.md](./debt-auth-users-migration-duplicate.md) |
| D13 | [debt-phpunit-modules-registration.md](./debt-phpunit-modules-registration.md) |
| D14 | [debt-wave3-event-providers.md](./debt-wave3-event-providers.md) |

### Approfondimento narrativo (complementare)

- [gestionale-commesse-technical-debt-resolution-guide.md](./gestionale-commesse-technical-debt-resolution-guide.md) — guida lunga (login/media/migrate); **non** sostituisce l’inventario.

### Alias (non usare come secondo inventario)

- [gestionale-commesse-debito-tecnico.md](./gestionale-commesse-debito-tecnico.md)
- [gestionale-commesse-technical-debt.md](./gestionale-commesse-technical-debt.md)

## Altri documenti

| File | Nota |
|---|---|
| [phpstan-roadmaps-index.md](./phpstan-roadmaps-index.md) | PHPStan moduli nativi + GC |
| [migrate-gestionale-tests.md](./migrate-gestionale-tests.md) | Porting test SRC → `Modules/*/tests` (story 2.5) |
| [pest_testing_setup.md](./pest_testing_setup.md) | Setup Pest |
| [development/tdd-laravel-pest-guide.md](./development/tdd-laravel-pest-guide.md) | TDD |
| [wiki/](./wiki/index.md) | Wiki locale laravel |

## Convenzioni

- Solo lowercase nei nomi `.md` (eccezione `README.md`); **niente date** nel filename.
- Prima di un nuovo file: cercare duplicati sullo stesso argomento (`debt-*` / inventario).
- Focus: *perché* + *come risolvere* (KISS/DRY). **Questa cartella non è il luogo del fix codice.**
