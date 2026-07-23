---
title: "Handoff — migrate SQLite + PHPStan 0"
type: handoff
tags: [handoff, phpstan, migration, sqlite, multi-agent]
created: 2026-06-06
updated: 2026-06-06
agent: "Auto (Cursor)"
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/22"
discussion: "https://github.com/laraxot/base_techplanner_fila5/discussions/19"
related:
  - ../wiki/rules/data-sacred-no-destructive-db.md
  - ../wiki/how-to/multi-agent-coordination-discipline.md
  - handoff-multi-agent-coordination.md
---

# Handoff — migrate SQLite + PHPStan 0

> **Repo:** `laraxot/base_techplanner_fila5` (`git remote -v`)  
> **Stato:** ✅ `php artisan migrate` OK · PHPStan 4914 file **0 errori**

## Problemi risolti (migrate)

### 1. Connessione `user` → MySQL su ambiente SQLite

**Errore:** `Access denied for user 'root'@'localhost' (Connection: user)`

**Fix:**

- Rimosso blocco hardcoded `connections.user` MySQL da `laravel/config/database.php`
- `TenantServiceProvider`: se `connections.user` è null, copia da `connections.user_{default}` o `connections.{default}` → SQLite `database/db_user.sqlite`

### 2. Migrazione `team_user` su SQLite

**Errore:** `ALTER TABLE team_user ADD PRIMARY KEY (id)` — syntax error (SQLite `integer` vs `bigint`)

**Fix:** `Modules/User/database/migrations/2025_01_22_120000_create_team_user_table.php`

- `integer` e `bigint` accettati come id autoincrement validi
- Conversione UUID→bigint solo su `isMysqlFamilyDriver()` (non su SQLite)

## Problemi risolti (PHPStan)

| File | Fix |
|------|-----|
| `Xot/app/Models/Traits/HasDynamicFillable.php` | Guard su `$dynamicFillableEnums === []` (no `isset` su proprietà non-nullable) |
| 11× `Xot/app/Datas/*Data.php` + `Notify/app/Datas/SmsData.php` | Classe `final` + Pint `self_static_accessor` per `make()` / `from()` |

## Verifica

```bash
cd laravel
php artisan migrate          # Nothing to migrate
php artisan migrate:status    # batch [2] Ran fino a 2026_04_28
./vendor/bin/phpstan analyse --memory-limit=-1   # OK No errors
```

## Debito noto (non bloccante)

- Migrazioni duplicate (`create_profiles_table`, `create_team_user_table`, …) — migrate gira ma va consolidato a parte
- `APP_KEY` vuoto in `.env` locale
- Tracking `migrations` solo su connection default, non su `db_user.sqlite`

## Per altri agenti

- Leggere `docs/wiki/rules/data-sacred-no-destructive-db.md` — **no** `--force` su produzione
- Issue [#22](https://github.com/laraxot/base_techplanner_fila5/issues/22) · coordinamento [#18](https://github.com/laraxot/base_techplanner_fila5/issues/18)

— Auto (Cursor)
