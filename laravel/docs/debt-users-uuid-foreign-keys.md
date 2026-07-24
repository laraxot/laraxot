---
title: "Debito D4 — FK users UUID vs foreignId"
type: how-to
tags: [technical-debt, users, uuid, foreign-key, migrations]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt users uuid varchar foreignId constrained migrate 3780"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-legacy-migrations-triage.md"
---

# Debito D4 — `users.id` UUID vs `foreignId()->constrained('users')`

## Perché

In questo ambiente `users.id` è **`varchar(36)`** (UUID string), non `bigint`.  
`foreignId()` crea `bigint unsigned` → MySQL/MariaDB **3780** “incompatible”.

Errore agente: **E11**. Già mitigato su AiAssistant / SiteSurvey; possono restare residui in altri moduli.

## Pattern canonico (risoluzione)

```php
// NO
$table->foreignId('user_id')->constrained('users');

// SÌ
$table->string('user_id', 36)->nullable()->index();
// relazione Eloquent: belongsTo(User::class, 'user_id', 'id');
```

Stesso per `created_by_user_id`, `performed_by_user_id`, `uploaded_by_user_id`, ecc.

Cross-connection / multi-db: Document SRC già evitava FK users — mantenere.

## Come fare audit completo

```bash
cd laravel
rg -n "constrained\('users'\)|foreignId\('.*user" Modules --glob '**/migrations/*.php'
rg -n "constrained\('users'\)" Modules --glob '**/migrations/*.php'
```

Per ogni hit:

1. Verificare se migrazione già Ran (allora serve `tableUpdate` alter column — **pericoloso** con dati).
2. Se Pending: correggere prima di path-migrate.
3. Se Ran con tipo sbagliato e tabella vuota: drop orphan + fix + remigrate (solo vuota).
4. Se Ran con dati: piano alter + backfill stringhe UUID.

## Contatti / altre FK tipo mismatch

Caso visto: `contacts.id` legacy `int` vs `foreignId` bigint su `work_orders.primary_contact_id`.  
Mitigazione usata: `unsignedBigInteger` **senza** constrained + ricreate contacts bigint se orphan.

Generalizzare: **prima di constrained**, `SHOW COLUMNS FROM target LIKE 'id'`.

## DoD

- [ ] Grep `constrained('users')` = 0 nelle migrazioni moduli GC attivi
- [ ] Nuove migrazioni review checklist include “tipo PK users”
- [ ] Lesson E11 linkata nelle story migrate

## Non fare

- Cambiare `users.id` a bigint “per comodità” (rompe User/UUID ovunque).
- Ignorare 3780 e commentare la FK lasciando `foreignId` (colonna resta bigint sbagliato).
