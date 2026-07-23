---
title: "Handoff — php artisan migrate (sqlite + connessione user)"
type: handoff
tags: [migrate, database, sqlite, tenant, activity, media, multi-agent]
created: 2026-06-06
updated: 2026-06-06
qmd: "handoff artisan migrate sqlite user connection activity index temporary uploads duplicate migration"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/21"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/19"
related:
  - ../wiki/bmad/architecture-data-sacred-no-destructive-db.md
  - ../wiki/how-to/multi-agent-coordination-discipline.md
  - ../../laravel/Modules/Tenant/docs/database-config-standard.md
---

# Handoff — `php artisan migrate`

**Repo:** [laraxot/base_techplanner_fila5](https://github.com/laraxot/base_techplanner_fila5)  
**Issue:** [#21](https://github.com/laraxot/base_techplanner_fila5/issues/21)  
**Coordinamento:** [#18](https://github.com/laraxot/base_techplanner_fila5/issues/18) · [Discussion #19](https://github.com/laraxot/base_techplanner_fila5/discussions/19)

## Obiettivo

Eseguire `php artisan migrate` da `laravel/` e risolvere tutte le segnalazioni, rispettando BMAD (solo `migrate`, mai `fresh`/`--force`/`--path` singolo).

## Stato finale (2026-06-06)

| Comando | Esito |
|---------|--------|
| `php artisan migrate` | OK — Nothing to migrate |
| `php artisan migrate --database=user` | OK — batch completato |
| Pending (default + user) | **0** |

## Root cause e fix

### 1. Activity — indice duplicato su `activity_log`

**Errore:** `index activity_log_causer_id_index already exists` su connessione `activity` / `database.sqlite`.

**Fix:** `Modules/Activity/database/migrations/2024_01_01_000002_create_activity_table.php` — separare `->change()` da `->index()`; creare indice solo se `! $this->hasIndex('causer_id')`.

### 2. Media — migrazione duplicata `temporary_uploads`

**Errore:** `no such table: temporary_uploads` su connessione `user` con `migrate --database=user`.

**Causa:** `2026_01_18_152545_add_columns_to_temporary_uploads_table.php` (Migration plain) duplicava `2026_01_18_152545_create_temporary_uploads_table.php` (XotBaseMigration → connessione `media` del modello `TemporaryUpload`).

**Fix:** rimossa la migrazione duplicata `add_columns_*`.

### 3. Config DB (già risolto in sessione precedente)

- `config/database.php` = standard Laravel 13.x (no connessioni modulari hardcoded)
- `TenantServiceProvider::registerDB()` mappa moduli da default sqlite
- Dev locale: `user` → `database/db_user.sqlite` via `config/localhost/database.php` (`user_sqlite`)

## Comandi verifica

```bash
cd laravel
php artisan migrate
php artisan migrate --database=user
php artisan migrate:status
php artisan migrate:status --database=user
php artisan db:show
```

## Note per agenti successivi

- **Pest / `.env.testing`:** usa MySQL `marco`/`techplanner_data_test` — se MySQL non disponibile in locale, i test falliscono con `Access denied`; non è un errore migrate. Valutare sqlite in `.env.testing` solo con accordo team (phpunit.xml commenta MySQL _test).
- Non reintrodurre migrazioni plain duplicate: sempre `XotBaseMigration` + connessione del modello owner.

— Auto (Cursor Agent)
