---
title: "Document module — PHPStan Level 10 verify + quality/test hardening"
type: story
module: Document
story_id: DOCUMENT-phpstan-l10-fix
slug: phpstan-l10-fix
status: done
created: 2026-09-06
updated: 2026-09-06
language: it-IT
related:
  - ./phpstan-Document-fix.md
  - ./phpstan-batch.story.md
  - ../../Xot/docs/stories/18.3.document-phpstan-l10-fix.story.md
  - ../coverage.md
---

# Document module — PHPStan Level 10 verify + quality/test hardening

## Problema

Baseline richiesta dal task di coordinamento multi-agente (ondata parallela
Gdpr/Document/HR/Compliance/Costing/Notify, vedi
`docs/chat/2026-09-06-pest-v5-done-plus-phpstan-parallel-wave.md`). Un run
precedente nella stessa giornata (commit `27fa27a` → `6071c43`, story
`phpstan-Document-fix.md`) aveva già portato PHPStan da N errori a 0 e
prodotto un `docs/coverage.md` iniziale.

## Baseline verificata in questa sessione

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Document --memory-limit=-1 --no-progress
```

**Risultato: `[OK] No errors`** (24 file analizzati) — sia a inizio sessione
sia dopo ogni modifica successiva (cache pulita con `clear-result-cache`
prima di ogni verifica, per escludere falsi negativi da cache stale durante
edit concorrenti).

**Errori PHPStan iniziali: 0. Errori PHPStan finali: 0.** Nessun errore reale
da correggere: la correzione L10 vera e propria era già stata completata dal
run precedente nella stessa giornata.

## Audit `mixed` e User/Profile (richiesta esplicita del task)

Verificati tutti gli usi di `mixed` rimasti in `app/` (esclusi i test):

- `Actions/CreateMediaFromStoredFileAction.php` — `@param array<string, mixed> $meta`:
  bag di metadati eterogenei per il file caricato (JSON column). Stesso
  pattern usato in `Modules/Gdpr/app/Models/Traits/HasGdpr.php` e
  `Modules/Geo/app/Datas/Map/GeoMapWidgetData.php` — convenzione di
  repository per colonne JSON generiche, non un caso di pigrizia.
- `Filament/Pages/UploadInterventionPhotos.php:31` — `@var array<string, mixed> $data`:
  stato Livewire/Filament (`statePath('data')`), tipo standard per lo state
  di uno schema Filament.
- `Filament/Pages/UploadInterventionPhotos.php:92` — parametro di callback
  `array_map(static function (mixed $path): string {...})`: il valore è già
  narrowed esplicitamente dentro la funzione con `is_string`/`is_int`/`is_float`
  prima di ogni uso; l'input è genuinamente eterogeneo (proviene dallo stato
  di un FileUpload Filament) e la funzione è già difensiva (ritorna `''` per
  qualunque altro tipo). Restringere la firma del parametro a un'unione
  avrebbe richiesto ipotesi non garantite sul contenuto reale dell'array.

Nessun `mixed` rimasto è stato giudicato eliminabile senza introdurre falsa
precisione. Nessun riferimento a un model `User`/`Profile` generico da
sostituire con `UserContract`/`XotData`: `Media::uploadedBy()` usa
`Modules\User\Models\User` in una relazione `BelongsTo`, pattern identico a
quello usato in **tutti** gli altri moduli del monorepo (`Quotation`,
`Platform`, `Signature`, `AiAssistant`, ecc. — verificato via grep) — le
relazioni Eloquent richiedono comunque una classe concreta, non
un'interfaccia.

## Fix applicati (qualità, non PHPStan)

Con PHPStan già verde, i seguenti micro-fix di qualità sono stati applicati
dopo audit PHPMD (`./tools/phpmd.sh Modules/Document/app text phpmd.xml`):

1. `app/Models/Media.php` — rimossa riga vuota dopo l'apertura della classe.
2. `app/Actions/AttachPhotosToInterventionAction.php` — spaziatura docblock
   (riga vuota tra `@param` e `@return`).
3. `app/Actions/CreateMediaFromStoredFileAction.php` — sostituito
   l'operatore ternario breve `$meta ?: null` con `$meta === [] ? null : $meta`
   (esplicito, non ambiguo su valori falsy diversi da array vuoto).

Non toccati (falsi positivi / convenzione di progetto, verificati con grep
sull'intero monorepo):
- `DocumentServiceProvider::$module_dir` / `$module_ns` (PHPMD
  `CamelCasePropertyName`) — nome di proprietà imposto da
  `XotBaseServiceProvider`, identico in ogni modulo.
- `Media::booted()` cyclomatic complexity 10 (== soglia configurata, non la
  supera) — metodo difensivo con early-return, refactor giudicato non
  necessario e a rischio di introdurre bug nella logica di fill mime/size.

## Test — root cause dei 4 Feature test falliti (fix parziale in-scope)

`./vendor/bin/pest Modules/Document/tests --no-coverage` inizialmente:
**4 failed, 2 passed**. Causa originale: `documentCreateIntervention()`
(in `tests/PestHelpers.php`) chiamava `InterventionFactory::new()->createOne()`,
che risolve `work_order_id => WorkOrder::factory()`, che a sua volta risolve
`customer_id => Customer::factory()` — un INSERT reale sulla connessione
`customer` (mysql, DB `workorder_data_test`), la cui tabella `customers` non
è migrata in questo ambiente.

Fix applicato (solo file di test di Document, nessun altro modulo toccato):

- `tests/PestHelpers.php`: `documentCreateIntervention()` ora usa
  `InterventionFactory::new()->makeOne(['work_order_id' => null, ...])` (mai
  persistita) con un id fittizio assegnato manualmente
  (`setAttribute($keyName, random_int(...))` + `exists = true`). Motivazione
  documentata inline: `document_media` non ha una FK verso `mediable`
  (`morphs()` crea solo colonne + indice, mai un vincolo — vedi commento
  nella migration), quindi un id fittizio basta per esercitare la relazione
  polimorfica `MorphMany`/`MorphTo` senza mai scrivere sulla connessione
  `intervention`/`customer`.
- `tests/Feature/MediaActionsTest.php`: rimossa una `$intervention->refresh()`
  superflua (l'intervention non è più una riga reale, `refresh()` avrebbe
  tentato una SELECT su una tabella inesistente).

**Risultato dopo il fix**: l'errore si sposta e si restringe — non più
`customers`/`interventions` mancanti, ma **`document_media` stessa non esiste
sulla connessione `document` (mysql, `workorder_data_test`)** in questo
ambiente. Confermato con `php -l` (nessun errore di sintassi) e
`phpstan clear-result-cache` + re-run (0 errori) dopo ogni modifica.

### Blocco residuo (fuori scope, non fixabile dentro Modules/Document)

Le connessioni custom mysql (`document`, `intervention`, `customer`) in
questo ambiente non hanno le tabelle migrate nel DB `workorder_data_test`.
`Modules/Xot/tests/XotBaseTestCase::prepareSharedFixcitySqliteForTesting()`
redirige verso lo sqlite condiviso **solo** le connessioni già configurate
con driver `sqlite` — non converte una connessione mysql. Il pattern
esplicitamente documentato altrove nel monorepo
(`Modules/Cms/tests/TestCase.php`, commento inline) è:

> "NOTE: Migrations are NOT run in setUp(). They must be run ONCE
> externally: `php artisan migrate --env=testing`. DatabaseTransactions
> trait handles rollback automatically between tests."

Cioè: la migrazione delle connessioni mysql di test è un'azione operativa
esterna, a carico di chi gestisce l'ambiente — non del codice del modulo né
di un agente. Coerente con la regola di memoria "Data is sacred" (mai
migrate/refresh/RefreshDatabase eseguiti autonomamente: lo schema è
decisione dell'utente) e con il vincolo di restare dentro `Modules/Document`
(eseguire `migrate` per altre connessioni condivise è un'azione
infrastrutturale, non una modifica di codice del modulo). Non eseguito.

**Azione richiesta al proprietario dell'ambiente:** eseguire una volta
`php artisan migrate --env=testing` (o equivalente per le connessioni
`document`, `intervention`, `customer`) per sbloccare i 4 Feature test.

## Acceptance criteria

- [x] PHPStan `Modules/Document` → `[OK] No errors` (0 → 0, verificato con
      cache pulita ad ogni step)
- [x] Ogni `mixed` residuo auditato singolarmente e giustificato (nessuno
      rimosso perché nessuno era eliminabile senza falsa precisione)
- [x] Nessun riferimento a `User`/`Profile` generico da sostituire con
      contratti (verificato: pattern già coerente col resto del monorepo)
- [x] PHPMD: nessuna nuova violazione introdotta; 3 preesistenti confermate
      falsi positivi / convenzione di progetto
- [x] Root cause dei test Feature falliti identificata e ridotta
      (Customer/WorkOrder eliminati dalla catena; residuo isolato a
      "migrazioni mysql di test non eseguite", blocco ambientale non di
      codice)
- [x] `docs/coverage.md` aggiornato con numeri prima/dopo
- [ ] 4 Feature test verdi — **bloccato** da migrazioni ambiente mysql non
      eseguite (fuori scope, azione richiesta al proprietario ambiente)

## Dev Agent Record

### Misura

| Step | Errori PHPStan | Pest (Document) |
|------|---------------:|------------------|
| Partenza sessione | 0 | 4 failed, 2 passed |
| Dopo fix qualità + test root-cause | 0 | 4 failed (causa ridotta/isolata), 2 passed |

### Comandi

```bash
cd laravel
./vendor/bin/phpstan clear-result-cache
./vendor/bin/phpstan analyse Modules/Document --memory-limit=-1 --no-progress
./tools/phpmd.sh Modules/Document/app text phpmd.xml
./vendor/bin/pest Modules/Document/tests --no-coverage
```
