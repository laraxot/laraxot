---
title: "Document/Media: collisione fisica sulla tabella media tra due moduli distinti"
created: 2026-07-27
---

# Blocco strutturale: `Modules\Document\Models\Media` e `Modules\Media` collidono sulla tabella `media`

## Sintomo osservato

`Modules/Document/tests/Feature/MediaActionsTest.php` fallisce con:

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'kind' in 'field list'
(Connection: document, ... Database: workorder_data_test, ...
insert into `media` (`kind`, `title`, `disk`, `path`, ...))
```

Ispezionando le colonne reali della tabella `media` sul DB di test:

```
id, model_type, model_id, uuid, collection_name, name, file_name, mime_type,
disk, conversions_disk, size, manipulations, custom_properties,
generated_conversions, responsive_images, order_column, created_at,
updated_at, updated_by, created_by, deleted_at, deleted_by
```

Questo è lo schema di `Modules/Media/database/migrations/2022_01_01_000011_create_medias_table.php`
(stile Spatie Laravel-MediaLibrary: `model_type`/`model_id`/`collection_name`/...),
**non** lo schema di `Modules/Document/database/migrations/2026_07_23_180010_create_media_table.php`
(`mediable_type`/`mediable_id`/`kind`/`title`/`path`/...).

## Causa radice

Due moduli distinti dichiarano un modello chiamato `Media` che punta, tramite
`XotBaseMigration`, alla stessa tabella fisica `media`:

- `Modules\Media\Models\Media` → connessione `media` → `config('database.connections.media.database')`
- `Modules\Document\Models\Media` → connessione `document` → `config('database.connections.document.database')`

Sul DB di test entrambe le connessioni risolvono allo **stesso database fisico**:

```
document db: workorder_data_test
media db: workorder_data_test
document host: 127.0.0.1
media host: 127.0.0.1
```

`XotBaseMigration::tableCreate()` è idempotente per design (`if (!
$this->tableExists($tableName)) { create... }`). La migration di `Modules\Media`
è girata per prima (o comunque prima che la tabella `document`-side esistesse),
quindi quando la migration di `Modules\Document` ha provato a creare `media` ha
trovato la tabella già esistente (quella di Spatie/Modules\Media) e si è
fermata silenziosamente — nessun errore alla migrazione, il bug emerge solo a
runtime quando `Modules\Document\Models\Media` prova a scrivere colonne che
non esistono in quello schema.

## Perché non è un problema di test né di modello

- Il test (`MediaActionsTest.php`) e le Actions (`CreateMediaFromStoredFileAction`,
  `AttachPhotosToInterventionAction`) sono corretti: scrivono esattamente le
  colonne definite nella migration di `Modules\Document`.
- Il modello `Modules\Document\Models\Media` è corretto.
- La migration di `Modules\Document` è corretta (crea lo schema giusto quando
  può effettivamente creare la tabella).

## Cosa NON fare

- **Non** aggiungere colonne mancanti (`kind`, `uploaded_by_user_id`, ...) alla
  tabella `media` esistente con un `tableUpdate` — quella tabella appartiene
  logicamente a `Modules\Media` (schema Spatie), aggiungere colonne
  `Document`-specific la corromperebbe per l'altro modulo.
- **Non** rinominare/bumpare il timestamp della migration di `Document`
  sperando che `tableCreate` la ricrei: `tableCreate()` fa `tableExists()`
  quindi resta no-op finché la tabella fisica `media` esiste, indipendentemente
  dal nome del file.
- **Non** eseguire `migrate:fresh` / `migrate --force` per "ripulire": i dati
  sono SACRED, e comunque non risolverebbe la collisione strutturale, la
  ricreerebbe identica al prossimo giro di migration se l'ordine resta lo
  stesso.

## Possibili fix reali (da decidere con l'owner del progetto)

1. **Separare le connessioni su database fisici diversi** — se `document` e
   `media` sono concettualmente connessioni separate, dovrebbero puntare a
   database fisici distinti anche in test (coerente con l'architettura
   multi-connessione dichiarata nei `BaseModel` dei due moduli).
2. **Rinominare la tabella fisica di uno dei due moduli** — es. la migration
   di `Modules\Document` potrebbe usare un nome tabella esplicito diverso da
   `media` (es. `document_media`) per evitare la collisione indipendentemente
   dalla configurazione delle connessioni.
3. **Verificare se `Modules\Media` è effettivamente ancora necessario/attivo**
   in questo progetto — se `Modules\Document` è il modulo "vincente" per la
   gestione media nel dominio corrente, valutare deprecazione/rimozione del
   vecchio modulo Spatie-style.

Nessuna di queste opzioni è stata applicata: sono scelte architetturali che
spettano all'owner del progetto, non un fix "silenzioso" da parte di un agente.

## Stato del lavoro collegato

- `Modules/WorkOrder/app/Models/WorkOrder.php` e
  `Modules/Intervention/app/Models/Intervention.php`: **fix reale applicato**
  (sostituito `HasFactory` con `HasXotFactory`, convenzione già usata da 38
  altri modelli nel progetto per la risoluzione corretta del namespace della
  factory nei moduli). Verificato phpstan 0 errori, phpmd pulito. Questo fix è
  indipendente dal blocco sopra ed è corretto/completo.
- `Modules/Document/tests/Feature/MediaActionsTest.php`: la sostituzione di
  `Intervention::factory()->create()` con l'helper tipizzato
  `documentCreateIntervention()` resta corretta e necessaria (bypassa il bug
  di risoluzione generics di Larastan su `Model::factory()` per questi
  modelli). Il test tornerà verde automaticamente non appena il blocco sopra
  sarà risolto a livello di connessione/tabella — nessun'altra modifica al
  test è necessaria.

GitHub issue di riferimento: laraxot/base_workorder_fila5#14
