---
title: "Debito D3 — triage migrazioni legacy Pending"
type: how-to
tags: [technical-debt, migrations, path-migrate, data-sacred]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt legacy migrations triage pending path migrate no force"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-users-uuid-foreign-keys.md"
  - "./debt-document-media-conflict.md"
---

# Debito D3 — triage ~95 migrazioni Pending

## Perché

Oltre al wave gestionale, il DB ha decine di migrazioni Pending (blog, profiles duplicati, oauth, pulse, moduli disallineati, create_users multipli, …).  
`php artisan migrate` **globale** le esegue in ordine di timestamp → FK mancanti, tabelle duplicate, collisioni.

Errore agente correlato: **E13**.

## Religione

- Mai `migrate --force`.
- Preferire `--path=Modules/{Modulo}/database/migrations`.
- Dati esistenti = sacri; orphan vuoti solo con checklist.

## Come risolvere (processo, non “un comando”)

### Fase 0 — Inventario

```bash
cd laravel
php artisan migrate:status | tee /tmp/migrate-status.txt   # non commitare dump
# oppure conteggio
php artisan migrate:status 2>/dev/null | rg -c Pending
```

Classificare ogni Pending in:

| Classe | Significato | Azione tipica |
|---|---|---|
| A | Serve al modulo enabled e tabella assente | Path-migrate dopo fix FK |
| B | Tabella già esiste (Ran semantico, Pending formale) | Rendere migrazione idempotente (`hasTable`) **oppure** `migrate --path=file` no-op + insert record migrations solo se policy team lo consente |
| C | Modulo disabled / feature morta | Lasciare Pending **oppure** disable load migrations (modulo off) |
| D | Duplicato pericoloso (`create_users` × N, `create_profiles` × N) | Consolidare forward-only; non eseguire ciechi |
| E | Package publish root `database/migrations` | Idempotenza (come Spatie media) |

### Fase 1 — Wave gestionale (già fatto come pattern)

Ordine sicuro documentato in readiness; ripetere solo se nuovo ambiente:

Catalog → Customer → WorkOrder → Quotation → Intervention → Signature → Vehicle → AiAssistant → Billing → (Document **solo dopo D1**).

### Fase 2 — Batch per modulo enabled

Per ogni modulo `true` in `modules_statuses.json`:

1. `migrate:status` filtrato al path modulo.
2. Fix FK (vedi D4) prima di path-migrate.
3. Path-migrate.
4. Annotare esito in Issue #14.

### Fase 3 — Root `laravel/database/migrations`

- Audit file Pending.
- Pattern obbligatorio: `if (Schema::hasTable(...)) return;` prima di `Schema::create`.
- Non inventare drop.

### Fase 4 — Moduli disabled con migrazioni caricate?

Se un modulo disabled **non** registra provider, le sue migrazioni non partono (XotBase carica migrate al boot). Verificare che disabled = non boot.  
Se un package le espone comunque, trattarle come classe E.

## Policy “marcar Ran senza eseguire”

Solo se:

1. Schema già equivalente verificato (`SHOW CREATE TABLE` / column list).
2. OK esplicito utente/DBA.
3. Documentato in Issue (riga migration name + hash/decision).

Altrimenti: idempotent up() + path-migrate.

## DoD

- [ ] Conteggio Pending documentato e in calo controllato
- [ ] Zero esecuzione migrate globale in runbook CI/dev
- [ ] Wave path script o sezione runbook in questo file aggiornata
- [ ] Nessuna tabella produzione droppata

## Non fare

- `migrate:fresh`, `migrate:reset`, `db:wipe` su DB condivisi.
- Cancellare righe da `migrations` per “rifare” senza piano.
- Path-migrare Document prima di D1.
