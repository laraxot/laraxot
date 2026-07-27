---
title: "Import status — EnergyBroker"
type: concept
tags: [import, gestionale-commesse, energybroker]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status EnergyBroker normalize"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — EnergyBroker

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape)
- [x] Namespace `Modules\EnergyBroker\`
- [x] `EnergyBrokerServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [ ] Resources → XotBaseResource (wave)
- [x] PHPStan L10: **pulito** (0 errori reali; era 190). Verificato con
      `./vendor/bin/phpstan analyse Modules/EnergyBroker --memory-limit=-1`.
- [x] `modules_statuses=true` — riabilitato 2026-07-27, confermato dall'utente
      ("certo che devi riabilitare... fa anche phpstan e pest").
- [x] Pest: **3/3 passed**. Fix applicati 2026-07-27:
      `tests/Feature/EnergyBrokerDocumentLifecycleTest.php` usava
      `file_put_contents`/`@unlink` non wrappati (`Safe\*`); `tests/TestCase.php`
      non chiamava `prepareSharedFixcitySqliteForTesting()` prima di
      `parent::setUp()` (pattern Customer/Catalog/HR/TimberBilling) → connessione
      `sqlite` puntava al path sbagliato. Schema modulo migrato in
      `database/fixcity_data.sqlite` (`migrate --database=sqlite --path=Modules/EnergyBroker/database/migrations`).

## PHPStan — sessione 2026-07-24

Partiti da 190 errori reali, risolti tutti senza `@phpstan-ignore`, `assert()`/`@var`
per forzare tipi, cast di comodo o allargamento di tipi.

### Bug funzionali reali trovati e corretti

1. **Migration fatali a runtime** — le 5 migration in
   `database/migrations/2026_07_24_00020{1..5}_*` usavano closure
   `static function (Blueprint $table)` che al loro interno chiamavano
   `$this->updateTimestamps($table, ...)`. In una closure `static` `$this`
   non esiste: la migrazione sarebbe fallita con `Error: Using $this when not
   in object context` alla prima `php artisan migrate`. Rimosso `static`.
2. **`str()->uuid()`** in `EnergyBrokerDocument::booted()` e
   `EnergyBrokerLargeUpload::booted()` — l'helper `str()` senza argomenti non
   espone in modo affidabile `uuid()` in tutti i contesti tipizzati; sostituito
   con `Illuminate\Support\Str::uuid()` esplicito (stesso comportamento,
   tipizzato correttamente) in tutte le Action che generavano path con uuid
   (`ExtractPdfFilesFromZipAction`, `ImportEnergyBrokerDocumentPdfAction`,
   `ImportEnergyBrokerDocumentsFromZipAction`).
3. **`openssl_sign` non controllato** in
   `AnalyzeEnergyBrokerDocumentWithGoogleDocumentAiAction` — il valore di
   ritorno booleano non veniva verificato in modo type-safe; ora si usa la
   variante `Safe\openssl_sign` (lancia eccezione invece di restituire false
   silenziosamente).

### Altri fix (non bug, type-safety)

- Aggiunti blocchi `@property` a tutti i model EnergyBroker (colonne dedotte
  dalle migration) e generics su tutte le relazioni (`BelongsTo<X, $this>`,
  `HasMany<X, $this>`).
- Sostituite tutte le funzioni native unsafe (`preg_match`, `preg_replace`,
  `file_get_contents`, `json_decode`, `fopen`, `fclose`,
  `stream_copy_to_stream`, `hash_file`, `filesize`, `rename`) con le varianti
  `Safe\*`, adattando la logica dove il comportamento cambia (throw invece di
  `false`).
- `ConfirmEnergyBrokerDocumentImportAction`: accesso a proprietà del model
  `Customer` (altro modulo, non annotate su quel model) tramite
  `getAttribute()` con guard, invece di accesso diretto a proprietà
  inesistenti nel PHPDoc.
- `ListEnergyBrokerDocuments`: la vista `energybroker::filament.large-upload-modal`
  non è risolvibile da PHPStan/Laravel finché il modulo resta disabilitato in
  `modules_statuses.json` (namespace vista non registrato). Sostituito
  `view('energybroker::...')` con `view()->file(__DIR__.'/../../../../../resources/views/...')`,
  che funziona indipendentemente dallo stato di attivazione del modulo ed è
  verificabile staticamente da PHPStan.

## Test

`vendor/bin/pest Modules/EnergyBroker/tests` → **1 passed (3 assertions)**.
Unico test presente: smoke test che verifica l'esistenza delle classi
principali del modulo (provider + 2 model). Non esistono ancora test di
integrazione per le Action/Job (import PDF/ZIP, chunked upload, ecc.).

## Cosa resta aperto

- Nessun errore PHPStan reale residuo nel modulo. L'unico "errore" riportato
  dal run è un messaggio innocuo del pattern di ignore globale
  `#PHPDoc tag @mixin contains unknown class #` non matchato quando si
  analizza il solo modulo EnergyBroker — proviene da `phpstan.neon` di root
  (non modificabile per policy) e non è legato al codice del modulo.
- Copertura test ancora minima (solo smoke test): mancano test funzionali per
  `ImportEnergyBrokerDocumentPdfAction`, `ImportEnergyBrokerDocumentsFromZipAction`,
  `ConfirmEnergyBrokerDocumentImportAction`, il flusso di chunked upload
  (`StoreEnergyBrokerLargeUploadChunkAction` → `AssembleEnergyBrokerLargeUploadJob`
  → `ImportEnergyBrokerLargeUploadJob`).
- Resources → XotBaseResource (wave) ancora da fare, come già annotato.
