---
title: IDE Helper Models Governance
type: reference
tags: [ide-helper, phpstan, data-sacred, models]
created: 2026-03-10
updated: 2026-08-31
qmd: ide-helper models nowrite never write reset data sacred schema
related:
  - ./ide-helper-best-practices.md
  - ../../../../docs/wiki/rules/data-sacred-no-destructive-db.md
  - ../../../../docs/chat/ide-helper-refresh.md
---

# IDE Helper Models Governance

## Religione: dati sacri

Ide-helper **non** giustifica `migrate:fresh`, `migrate --force`, `db:wipe` o `RefreshDatabase`.
Se lo schema manca o è vuoto: **fermati** — allineamento DB solo dal responsabile, additivo (`migrate` senza force).

Canon: [data-sacred-no-destructive-db.md](../../../../docs/wiki/rules/data-sacred-no-destructive-db.md).

## Comando canonico (2026-08-31)

```bash
cd laravel
php artisan ide-helper:generate --no-interaction
php artisan ide-helper:meta --no-interaction
php artisan ide-helper:models --nowrite --no-interaction
```

Scrive solo `_ide_helper_models.php` (escluso da PHPStan). **Mai** `--write` / `-W` / `--write --reset` sui model app: cancella `@property` manuali → migliaia di `property.notFound`.

## Precondizione schema

`ide-helper:models` legge le colonne dal DB. Schema vuoto + `-W` = perdita silenziosa dei PHPDoc.

```bash
cd laravel && php artisan tinker --execute="echo count(DB::select('show tables'));"
```

Se `0`: **non** lanciare write; **non** migrate distruttivo. Handoff al owner.

## Config locale

- `force_fqn => true` in `config/ide-helper.php`
- `config/media.php` deve essere un **file**, non directory `media.php/` (rompe `meta` con EISDIR)

## Dopo refresh

```bash
./vendor/bin/phpstan clear-result-cache
./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

## Evidence

- [ide-helper-refresh.md](../../../../docs/chat/ide-helper-refresh.md)
- [ide-helper-best-practices.md](./ide-helper-best-practices.md)
