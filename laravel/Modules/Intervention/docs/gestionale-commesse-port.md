---
title: "Redo reshape — Intervention (dopo revert)"
type: concept
tags: [import, gestionale-commesse, intervention, reshape, redo]
created: 2026-07-23
updated: 2026-07-23
qmd: "Intervention redo reshape dopo revert XotBase Domain Actions Contracts"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
---

# Redo reshape — Intervention (dopo revert)

## Contesto

Una precedente sessione aveva portato `Intervention` alla struttura canonica XotBase; un
processo concorrente ha poi ripristinato (revert) la copia grezza DDD
`Domain/Application/Infrastructure`. Questa sessione rifà il reshape.

**Nota**: all'ispezione, la working tree conteneva già gran parte del reshape (`app/Models`,
`app/Filament`, `app/Actions`, `app/Providers`, `routes/{web,api}.php`) — probabilmente residuo
non committato della sessione precedente, non effettivamente ripristinato in toto dal revert
(`git log` mostra un solo commit `Ignore laravel/Modules/docs` su questo path, e `git status`
mostrava ~30 file modificati/non tracciati). Il lavoro di questa sessione si è quindi concentrato
sul completare i pezzi mancanti/incoerenti piuttosto che rifare da zero.

## Cosa è stato fatto in questa sessione

1. **`app/Domain/*` eliminato** (era l'unico residuo DDD rimasto, dentro `app/`, non in root):
   - `Domain/DTO/*` → `app/Data/*` (namespace `Modules\Intervention\Data`)
   - `Domain/Exceptions/*` → `app/Exceptions/*`
   - `Domain/ValueObjects/*` → `app/ValueObjects/*`
   - `Domain/Actions/{ComputeWorkMinutesAction,DetectExtraQuotationMaterialsAction}` →
     `app/Actions/*`, con trait `Spatie\QueueableAction\QueueableAction` aggiunto.
   - Tutti i riferimenti (namespace `use`) aggiornati nei consumer
     (`app/Filament/Resources/InterventionResource.php`, `app/Filament/Pages/RecordInterventionReportPage.php`,
     diverse Actions, `PublicInterventionReportController`).
2. **`*Interface` → `*Contract`** (regola `module-contracts-naming-placement`):
   - `app/AI/InterventionReportExtractorInterface.php` → `app/Contracts/InterventionReportExtractorContract.php`
   - `app/Payments/ReportPaymentGatewayInterface.php` → `app/Contracts/ReportPaymentGatewayContract.php`
   - `app/Payments/CashierReportPaymentGateway.php` (implementazione) resta in `app/Payments/`
     (cartella di dominio ammessa, come `app/GoogleCalendar/`), aggiornato l'`implements`.
3. **Filament Pages/RelationManagers**: tutte le 8 Pages di risorsa
   (`{List,Create,Edit,View}{Intervention,DailyNote}`) e le 6 `RelationManager` di
   `InterventionResource` estendevano direttamente classi Filament core
   (`ListRecords`/`CreateRecord`/`EditRecord`/`ViewRecord`/`RelationManager`) — violazione della
   regola "nessun componente Filament estende Filament core direttamente". Convertite a
   `XotBase{ListRecords,CreateRecord,EditRecord,ViewRecord}` e `XotBaseRelationManager`.
   Le 5 Filament Pages standalone (`InterventionCalendar`, `CustomerInterventionCalendar`,
   `MySchedule`, `GoogleCalendarSettingsPage`, `RecordInterventionReportPage`) estendevano
   `Filament\Pages\Page` diretto → convertite a `XotBasePage`.
4. **Modelli**: verificato che tutti e 7 estendono `app/Models/BaseModel.php` (già
   `extends XotBaseModel`). Aggiunto trait `HasFactory` mancante su tutti i modelli (le
   factory in `database/factories/` lo richiedevano ma nessun modello lo dichiarava).
5. **Factories/Seeders**: sono comparse durante la sessione (probabilmente scaffolding
   automatico di uno strumento collegato — non generate manualmente in questa sessione) con un
   bug sistematico `Modules\\Intervention` (doppio backslash, namespace non valido) in 7 file
   `database/factories/*.php` e 7 file `database/seeders/*.php`: corretto con sed mirato,
   verificato `php -l` pulito su tutto il modulo dopo la correzione.
6. **`composer.json`**: aggiunti path repository mancanti per `Customer`, `WorkOrder`,
   `Signature`, `Media` (moduli da cui `Intervention` importa classi via namespace completo:
   `Modules\Customer\Models\Customer`, `Modules\WorkOrder\Models\WorkOrder`,
   `Modules\Signature\Models\{Signature,Sketch}`, `Modules\Document\Models\Media` — quest'ultimo
   già coperto da nessun repo esplicito, verificato che il modulo `Media`/`Document` esiste nel
   monorepo).
7. **`tests/TestCase.php` e `tests/Pest.php`**: mancanti, creati sul modello di `Employee`
   (`XotBaseTestCase`, `DatabaseTransactions`, niente `RefreshDatabase`).
8. **`lang/{it,en}/intervention.php`**: mancante, creato uno stub minimo per il modello
   `Intervention` (label, campi principali). Gli altri 6 modelli non hanno ancora traduzioni
   dedicate — vedi TODO.
9. `RouteServiceProvider`, `EventServiceProvider`, `routes/{web,api}.php` erano già presenti e
   corretti (nessun intervento necessario) — il pitfall PHPStan/Larastan citato dal playbook non
   si è quindi materializzato per causa di `Intervention` stesso.

## Verifica

- `php -l` su tutti i file di `app/`, `database/`, `tests/`: **pulito** dopo le correzioni.
- `./vendor/bin/pint Modules/Intervention`: eseguito, ha normalizzato import/ordinamento/parentesi
  su gran parte dei file (nessuna modifica semantica).
- `./vendor/bin/phpstan analyse Modules/Intervention --memory-limit=-1`: **bloccato**, non per
  causa di `Intervention`. Errore fatale al bootstrap:
  ```
  PHP Fatal error: Type of Modules\Auth\Providers\EventServiceProvider::$shouldDiscoverEvents
  must not be defined (as in class Illuminate\Foundation\Support\Providers\EventServiceProvider)
  in Modules/Auth/app/Providers/EventServiceProvider.php on line 9
  ```
  Confermato riproducibile anche analizzando `Modules/Xot` (modulo completamente estraneo),
  quindi è un blocco di bootstrap applicativo globale in `Modules\Auth`, non introdotto da questa
  sessione. Per la regola "multi-agente concorrente" del playbook, **non è stato toccato**
  `Modules/Auth` (modulo non in lock da questo agente). Ri-eseguire
  `./vendor/bin/phpstan analyse Modules/Intervention --memory-limit=-1` non appena
  `Modules/Auth/app/Providers/EventServiceProvider.php` viene corretto da chi possiede quel modulo
  (rimuovere la ridichiarazione tipizzata di `$shouldDiscoverEvents`, o allinearla al tipo della
  classe padre Laravel).

## TODO residui (non bloccanti, tracciati)

- **Migrazioni**: il modulo ha 14 file di migrazione per 7 modelli, con pattern
  `create_*` + numerosi `add_*`/`alter_*` evolutivi, tutti `extends Migration` (Laravel core), non
  `XotBaseMigration`. La regola canonica vorrebbe 1 file `create_{table}_table.php` per modello con
  `tableCreate()`/`tableUpdate()`. Consolidare 14→7 file tocca uno storico di schema già
  potenzialmente applicato in ambienti condivisi: **non eseguito in questa sessione** per rischio
  di rottura dati, va pianificato come task dedicato con verifica di `php artisan migrate:status`
  sugli ambienti coinvolti prima di procedere.
- **`lang/`**: solo `intervention.php` (it/en) creato. Mancano traduzioni per `DailyNote`,
  `TimePunch`, `IssueReport`, `InterventionMaterialLine`, `InterventionReportShare`,
  `UserGoogleCalendarSetting`.
- **Factories/Seeders comparsi automaticamente**: contenuto minimo (non rivisto riga per riga
  oltre alla correzione del bug di namespace) — verificarli quando si scrivono i primi test Pest
  reali per il modulo.
- `modules_statuses.json`: **non registrato `"Intervention": true`** in questa sessione, perché
  PHPStan non ha potuto girare fino in fondo per causa esterna (vedi sopra). Da abilitare non
  appena la verifica PHPStan risulta pulita.
