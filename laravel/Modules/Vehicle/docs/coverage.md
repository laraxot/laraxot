---
name: Vehicle coverage
description: Stato copertura Pest e note PHPStan level-max per Modules/Vehicle
metadata:
  module: Vehicle
  updated: 2026-09-06
---

# Coverage — Modules/Vehicle

## PHPStan (2026-09-06)

- Before: 5 errori (level max, `./vendor/bin/phpstan analyse Modules/Vehicle --no-progress`)
- After: **0 errori**

File toccati:
- `app/Filament/Resources/VehicleResource.php` — `->actions()`/`->bulkActions()` deprecati (Filament v4) sostituiti con `->recordActions()`/`->toolbarActions()`.
- `app/Filament/Resources/VehicleResource/RelationManagers/DocumentsRelationManager.php` — `(string) $data['path']` / `(string) $data['title']` (cast.string su `mixed`) sostituiti con `Modules\Xot\Actions\Cast\SafeStringCastAction::cast()` / `SafeNullableStringCastAction::cast()`.
- `database/factories/VehicleDocumentFactory.php` — `(int) Media::query()->create([...])->getKey()` (cast.int su `mixed`, `getKey()` ritorna `int|string`) sostituito con `Modules\Xot\Actions\Cast\SafeIntCastAction::cast()`.

Nessuna modifica a `phpstan.neon`, nessun `@phpstan-ignore`, nessun baseline.

## Pest (`./vendor/bin/pest Modules/Vehicle/tests --no-coverage`)

- 4 passed / 2 failed (6 test totali, 7 assertion sui passed)
- I 2 fallimenti (`AddVehicleDocumentActionTest`, `VehicleCrudTest`) sono **pre-esistenti e fuori scope**: `SQLSTATE[42S02]: Base table or view not found: 1146 Table 'workorder_data_test.vehicles' doesn't exist` — gap di infrastruttura del DB di test condiviso (migrazioni `create_vehicles_table`/`create_vehicle_documents_table` esistono in `database/migrations/` ma non sono applicate al DB `workorder_data_test` usato dalla connection `vehicle`), stessa categoria di problema già documentata per altri moduli nello swarm PHPStan del 2026-09-06 (es. Intervention/TimberBilling: tabelle mancanti nel DB di test condiviso). Non causato dal fix PHPStan di questa sessione (nessuna delle modifiche tocca migrazioni, schema o config DB) e non risolvibile senza `migrate` sul DB di test condiviso — operazione distruttiva potenzialmente fuori policy, quindi non eseguita in questa sessione. Segnalato, non risolto.
- `VehicleModelTest` (Unit, 4 test) — tutti verdi, non toccati dal fix.

Coverage numerica (`--coverage`) non misurabile in questa sessione: i 2 test Feature che eserciterebbero i path toccati (`DocumentsRelationManager::getTableHeaderActions()`, `VehicleDocumentFactory::definition()`) falliscono per il gap di DB sopra, quindi il code path del fix non risulta eseguito end-to-end qui. Nessun test rimosso o indebolito per aggirare il problema.

## PHPMD (`./tools/phpmd.sh Modules/Vehicle text phpmd.xml`)

Nessun nuovo finding sui 3 file toccati. Findings pre-esistenti fuori scope (non toccati in questa sessione):
- `app/Models/Policies/VehicleBasePolicy.php`, `VehiclePolicy.php` — `CamelCaseParameterName`/`UnusedFormalParameter` su parametri `$_ability`/`$_vehicle` (naming convention Policy standard Laravel per parametri non usati).
- `app/Providers/VehicleServiceProvider.php` — `CamelCasePropertyName` su `$module_dir`/`$module_ns` (property nwidart/laravel-modules standard).

## PHPInsights (`./tools/phpinsights.sh analyse Modules/Vehicle --composer=<repo>/laravel/composer.lock`)

Nota: `phpinsights` **è** installato in questo repo (v2.14.2, `nunomaduro/phpinsights` dev-master
in `Modules/Xot/composer.json`) — contrariamente a quanto affermato dall'hook `PostToolUse`
di verifica automatica. Serve `--composer=<path assoluto a laravel/composer.lock>` quando si
analizza un sottopercorso (`Modules/Vehicle`), altrimenti fallisce con "composer.lock not found".

Punteggio: Code 93.8%, Complexity 100%, Architecture 88.2%, Style 94.0%.

Nessun nuovo issue bloccante sui 3 file toccati dal fix PHPStan. Unica nota minore: la riga
`title: SafeNullableStringCastAction::cast(...)` in `DocumentsRelationManager.php` supera 80
caratteri (regola di stile PSR-12 di phpinsights, non applicata da Pint in questo progetto e
già violata da molte altre righe pre-esistenti nello stesso modulo — non corretta per non
introdurre un secondo obiettivo di refactor fuori scope). Tutti gli altri finding (proprietà
pubbliche nei Provider, classi non `final`, ordered class elements, ecc.) sono debito
pre-esistente su file non toccati in questa sessione.

## Pattern per altri moduli

Per errori PHPStan `cast.string`/`cast.int`/`cast.float` su valori `mixed` (es. da `array $data` non tipizzato di Filament, o da `Model::getKey()` che ritorna `int|string`), usare le action già presenti in `Modules\Xot\Actions\Cast\`:
`SafeStringCastAction`, `SafeNullableStringCastAction`, `SafeIntCastAction`, `SafeFloatCastAction`, `SafeBooleanCastAction`, `SafeArrayCastAction`, `SafeObjectCastAction`. Evitano il cast diretto (vietato dalle regole PHPStan del progetto) e centralizzano la logica di conversione sicura.
