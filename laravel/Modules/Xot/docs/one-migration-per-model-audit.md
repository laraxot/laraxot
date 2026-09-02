---
id: xot-one-migration-per-model-audit
slug: one-migration-per-model-audit
title: "Audit N modelli = N migrazioni"
description: "Come si verifica e si risana la regola una-migrazione-per-modello: neutralizzazione invece di cancellazione, e quando invece si cancella."
document_type: rule
category: database-migrations
status: active
version: 1.0.0
language: it-IT
project: Fixcity Fila5
ecosystem: Laraxot
domain: database-schema
priority: high
source_of_truth: false
module: Xot
scope:
  - modules
  - migrations
audience:
  - developers
  - ai-agents
tags:
  - migrations
  - audit
  - convention
  - xot-base-migration
related:
  - ../../../../bashscripts/ai/wiki/rules/migration-filename-from-model-name.md
  - ../../IndennitaResponsabilita/docs/stories/01.33.one-migration-per-model.story.md
created_at: '2026-09-01'
updated_at: '2026-09-01'
maintainer: Laraxot
license: project-internal
---

# Audit "N modelli = N migrazioni"

Regola canonica: [`migration-filename-from-model-name.md`](../../../../bashscripts/ai/wiki/rules/migration-filename-from-model-name.md).
Questa pagina descrive **come verificarla e come risanarla**, non la ridefinisce.

## Lo strumento

```bash
php bashscripts/tools/migrations/audit-one-migration-per-model.php
php bashscripts/tools/migrations/audit-one-migration-per-model.php --module=User
php bashscripts/tools/migrations/audit-one-migration-per-model.php --fail-on-violation   # per la CI
```

Raggruppa le migrazioni per basename senza timestamp — cio' che resta identifica il
modello — e segnala i gruppi con **piu' di una migrazione attiva**.

## Cosa conta come violazione (e cosa no)

Una migrazione e' **attiva** se il suo `up()` contiene `tableCreate`, `tableUpdate` o
`Schema::`. Una duplicata **neutralizzata** (`up()` no-op documentato che punta all'owner)
e' conforme e non conta: e' il pattern gia' consolidato nel progetto.

```php
/**
 * NEUTRALIZZATA — no-op idempotente.
 *
 * Owner: {timestamp}_create_{modello}_table.php
 *
 * @see {timestamp}_create_{modello}_table.php
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = MyModel::class;

    public function up(): void
    {
        // no-op: owner = {timestamp}_create_{modello}_table
    }
};
```

## Neutralizzare o cancellare?

| Caso | Azione | Perche' |
| :-- | :-- | :-- |
| Duplicata con nome **conforme** (`create_*_table.php`) | **neutralizzare** | il file resta leggibile accanto all'owner e la riga in `migrations` conserva la cronologia |
| Nome **vietato** (`add_*_to_*`, `update_*`, `drop_*`, `_old`, …) | **cancellare** | il nome e' esso stesso la violazione: neutralizzarlo lascerebbe in piedi cio' che la regola proibisce |

Cancellare un file di migrazione **non tocca i dati**: la riga in `migrations` resta,
Laravel semplicemente non la ritrova e non ri-esegue nulla. Verificare comunque prima,
sulla connessione giusta:

```sql
SELECT migration, batch FROM migrations WHERE migration LIKE '%nome_file%';
```

## Il nome vietato costringe a una pezza

`XotBaseMigration::getModelClass()` deriva il modello dal filename con
`->between('_create_', '_table.php')`. Senza `_create_` la derivazione salta e serve un
`$model_class` esplicito: quella proprieta' e' il **sintomo**, non la soluzione. Si
corregge il nome, non si tiene la pezza.

## Prima di cancellare: tre verifiche

1. Il file e' davvero un no-op **oppure** un duplicato byte-identico dell'owner
   (`diff` sui due file, non a occhio).
2. L'owner dichiarato **esiste** ed e' attivo, e contiene davvero le colonne che il file
   diceva di aggiungere (`grep` colonna per colonna).
3. Nessun `.md` o `.php` referenzia il filename.

## Stato

Verificato: **167 migrazioni**, **0 basename duplicati**, **0 gruppi con piu' di una
migrazione attiva**, **0 filename con prefisso vietato**, 2 no-op residui documentati.

Il risanamento e' avvenuto per **cancellazione** delle duplicate, non per neutralizzazione:
git conserva la cronologia, quindi una cartella di archivio sarebbe una seconda fonte di
verita' destinata a divergere. Vedi
[`no-backup-directories.md`](../../../../bashscripts/docs/no-backup-directories.md).
