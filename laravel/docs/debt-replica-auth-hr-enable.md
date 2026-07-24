---
title: "Debito D5 — Auth/HR replica enabled vs User/Employee"
type: how-to
tags: [technical-debt, auth, hr, replica, user, employee]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt auth hr replica enable overlap user employee no merge login"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/10"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-login-route-collision.md"
  - "./debt-auth-users-migration-duplicate.md"
  - "../../docs/gestionale-commesse-comparison/STORY-001-auth-vs-user.md"
  - "../../docs/gestionale-commesse-comparison/STORY-002-hr-vs-employee.md"
---

# Debito D5 — Auth / HR replica enabled vs User / Employee

## Perché

Fase **replica singolare**: Auth e HR esistono come cartelle indipendenti dal SRC.  
Overlap funzionale con `User` e `Employee` **non** autorizza delete/merge (E1/E2).

Con `Auth`/`HR` **Enabled** aumentano:

- doppie rotte login / policy → **[D10](./debt-login-route-collision.md)**
- ownership `users` → **[D12](./debt-auth-users-migration-duplicate.md)**
- provider Filament / UI duplicate
- confusione SSoT auth

## Decisioni prodotto possibili

| Decisione | Significato |
|---|---|
| **Coesistenza lunga** | Scope ristretto; login FO = User Folio (D10 Opzione A) |
| **Disable temporaneo** | `false` finché gap chiusa; chiavi statuses presenti |
| **Merge guidato (dopo)** | Solo con OK utente + epic BMAD |

## Come risolvere

### 0. Landmine tecniche (prima della politica)

1. [D10](./debt-login-route-collision.md) — `route:list --path=login`
2. [D12](./debt-auth-users-migration-duplicate.md) — Auth `create_users`

### 1. Gap analysis

| Area | Auth vs User | HR vs Employee |
|---|---|---|
| Login FO | Chi risponde oggi? | — |
| Policy / Gate | Duplicate? | Ruoli/org |
| Filament | Panel Auth? | HR vs work-hours Employee |
| Dati | Stessa `users`? | Tabelle distinte tipiche |

Root: `STORY-001-auth-vs-user.md`, `STORY-002-hr-vs-employee.md`, `replica-singular-phase.md`.

### 2. Smoke

```bash
cd laravel
php artisan module:list | rg 'Auth|HR|User|Employee'
php artisan route:list --path=login
./vendor/bin/phpstan analyse Modules/Auth Modules/HR --memory-limit=-1
```

### 3. Decisione su Issue #7 / #9

Scelta + owner login FO (default **User**) + feature Auth/HR “unica fonte”.

### 4. Azioni

- **Coesistenza** — perimetro in `Modules/Auth|HR/docs`; D10-A; Pest login User.
- **Disable** — `"Auth": false`, `"HR": false`.
- **Merge-later** — nuova epic; niente merge nascosto.

## DoD

- [ ] D10/D12 chiusi o deferiti con owner
- [ ] Decisione scritta
- [ ] Nessun `rm -rf` Auth/HR
- [ ] Login FO verificato

## Non fare

- Delete cartella perché “esiste User”.
- Ignorare D10 perché i `name()` delle route differiscono.
