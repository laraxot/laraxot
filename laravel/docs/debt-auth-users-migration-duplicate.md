---
title: "Debito D12 — Auth non deve possedere create_users"
type: how-to
tags: [technical-debt, auth, user, migrations, users-table]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt auth create users table duplicate user migration"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-users-uuid-foreign-keys.md"
  - "./debt-replica-auth-hr-enable.md"
  - "./debt-legacy-migrations-triage.md"
---

# Debito D12 — migration `users` duplicata (Auth vs User)

## Perché

`Modules\User` è **SSoT** della tabella `users` (UUID, Passport/Socialite/team, schema ricco).  
Il modulo **Auth** (replica SRC) può ancora portare (o aver portato) una migration `create_users_table` povera.

Su DB **nuovo** (`migrate` fresh in ambiente dedicati):

- “table users already exists”, **oppure**
- tabella creata con schema Auth incompleto se Auth gira prima di User.

Su DB **esistente** (produzione-like): spesso silente finché lo status dice Ran/Pending in modo incoerente.

## Come scoprire lo stato reale

```bash
cd laravel
find Modules/Auth/database/migrations -iname '*users*' -print
find Modules/User/database/migrations -iname '*users*' -print
php artisan migrate:status 2>/dev/null | rg -i 'users'
```

Classificare:

| Caso | Significato |
|---|---|
| Auth ha `create_users` **Pending** | Non eseguire: rischia collisioni; neutralizzare prima |
| Auth ha `create_users` **Ran** | Non cancellare il file (Laravel lo vedrebbe come nuova); renderlo **no-op** |
| Auth non ha create_users | Debito già chiuso lato file — tenere audit periodico |

## Come risolvere

### Se Pending (mai eseguita in alcun ambiente rilevante)

1. Conferma su **tutti** gli ambienti (`migrate:status`) che non è Ran.
2. **Eliminare** il file Auth `create_users*` **oppure** trasformarlo in migration vuota con commento “owned by User”.
3. Assicurarsi che Auth usi `Modules\User\Models\User` ovunque (già fix tipico del reshape).

### Se Ran (storico)

1. **Non** `git rm` il file (rompe checksum migrations).
2. Edit forward-only: corpo `up()`/`tableCreate` → no-op (`return;` / `if (Schema::hasTable('users')) return;`).
3. Commento obbligatorio: owner = `Modules\User`.
4. Test: su DB clone, `migrate` non altera `users`.

### Prevenzione

```bash
# audit: una sola create_users “vera” nei moduli
rg -l "create_users_table|Schema::create\\('users'" Modules/*/database/migrations
```

Idealmente script in `bashscripts/tools/` (story dedicata) che fallisce CI se >1 owner.

## DoD

- [ ] Una sola fonte di verità schema `users` = User
- [ ] Auth non crea/altera colonne core di `users` senza migration **additive** esplicita (es. approval fields già tipizzate UUID)
- [ ] `migrate:fresh` su ambiente **usa-e-getta** (mai prod) verde
- [ ] Note in `Modules/Auth/docs/import-status.md`

## Non fare

- `migrate:fresh` su dati reali.
- Lasciare due `create_users` “perché Pending non fa male”.
- Spostare ownership `users` ad Auth.
