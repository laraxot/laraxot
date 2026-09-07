---
name: Vehicle coverage
description: Stato copertura Pest e note PHPStan level-max per Modules/Vehicle
metadata:
  module: Vehicle
  updated: 2026-09-07
---

# Coverage — Modules/Vehicle

## PHPStan typeCoverage.constantTypeCoverage (2026-09-07)

Bucket repo-wide (campagna "PHPStan Modules a zero errori"), visibile solo con
`phpstan analyse Modules` (albero intero), non con `phpstan analyse Modules/Vehicle` da
solo (il modulo è già a 0 errori a livello locale, prima e dopo).

- File: `app/Filament/Resources/VehicleResource.php`, righe 38 e 42.
- Prima: `private const ACCOUNT_FEATURE_ADMIN = 'admin.vehicles';` /
  `private const ACCOUNT_FEATURE_OPERATOR = 'operator.vehicles';`, con solo un PHPDoc
  `/** @var string */` sopra, nessuna type declaration nativa.
- Dopo: `private const string ACCOUNT_FEATURE_ADMIN = 'admin.vehicles';` /
  `private const string ACCOUNT_FEATURE_OPERATOR = 'operator.vehicles';` (PHP 8.3+ native
  const type), PHPDoc `@var` rimosso perché ridondante.
- Verifica shape reale prima del fix: entrambe stringhe scalari semplici, nessun rischio
  del pattern sbagliato `@var list<string>` su array associativi/annidati documentato in
  `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`.
- Percentuale globale (`phpstan analyse Modules`, cache pulita): 164/300 (54.6%) →
  177/300 (59.0%). Il salto di 13 anziché 2 riflette il lavoro concorrente di altri agenti
  sullo stesso bucket in altri moduli (repo condiviso multi-agente); il contributo
  verificabile di questa sessione sono le 2 costanti di Vehicle — confermate assenti
  dall'elenco `typeCoverage.constantTypeCoverage` dopo il fix (`phpstan analyse Modules`
  con cache pulita, prima e dopo, confrontati per nome file).
- Nessuna modifica a `phpstan.neon`, nessun `@phpstan-ignore`, nessun baseline, nessun
  `mixed`. `declare(strict_types=1);` già presente (file non toccato in quel punto).
- Effetto collaterale positivo PHPInsights: rimosso il finding pre-esistente
  `[Code] Useless constant type hint` su `VehicleResource.php:37`/`:41` (il PHPDoc `@var`
  era già segnalato come "Useless documentation comment"). Vedi sezione PHPInsights sotto.
- Story: `stories/02.Vehicle-typecoverage-constant-fix.story.md` /
  `stories/phpstan-Vehicle-typecoverage-fix.md`.

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

## Sessione 2026-09-07 — typeCoverage constant fix, quality gate completo

Quality gate rieseguito per intero dopo il fix delle 2 costanti (vedi sezione
`typeCoverage.constantTypeCoverage` in cima al file).

### PHPStan (`./vendor/bin/phpstan analyse Modules/Vehicle --no-progress --memory-limit=-1`)

- Prima: 0 errori (invariato dalla sessione 2026-09-06)
- Dopo: 0 errori

### PHPStan albero intero (`./vendor/bin/phpstan analyse Modules --no-progress --memory-limit=-1`, cache pulita)

- Prima: `typeCoverage.constantTypeCoverage` 164/300 (54.6%), 136 occorrenze su 73 file, incluse le 2 righe di `VehicleResource.php` (38, 42)
- Dopo: 177/300 (59.0%), 123 occorrenze su 67 file, `VehicleResource.php` non più presente nell'elenco
- Nota: il salto di 13 (non 2) è dovuto a lavoro concorrente di altri agenti sullo stesso bucket in altri moduli (repo multi-agente condiviso); il contributo di questa sessione sono le 2 costanti Vehicle, verificate come non più presenti.

### Pest (`./vendor/bin/pest Modules/Vehicle/tests --no-coverage`)

- Prima: 4 passed / 2 failed
- Dopo: 4 passed / 2 failed (invariato)
- I 2 fallimenti restano gli stessi pre-esistenti già documentati sopra (tabella `vehicles` mancante nel DB di test condiviso `workorder_data_test`), non toccati da un fix di sola type declaration su costanti scalari.

### PHPMD (`bash tools/phpmd.sh Modules/Vehicle/app text phpmd.xml`)

- Prima: 14 finding (elenco identico a quello già documentato sopra)
- Dopo: 14 finding, identici — nessun nuovo finding, nessuno risolto (il file toccato, `VehicleResource.php`, non aveva e non ha finding PHPMD)

### PHPInsights (`bash tools/phpinsights.sh analyse Modules/Vehicle/app --no-interaction`)

- Prima: Code 92.8%, Complexity 100%, Architecture 88.2%, Style 94.0% — con finding `[Code] Useless constant type hint` su `VehicleResource.php:37`/`:41` (i due `@var string` ridondanti)
- Dopo: **Code 93.8%** (+1.0 punto), Complexity 100%, Architecture 88.2%, Style 94.0% — finding "Useless constant type hint" rimosso
- Nota: il valore Code 92.8% "prima" di questa sessione è più basso del 93.8% registrato a fine sessione 2026-09-06 in questo stesso file: nel frattempo (repo condiviso multi-agente) qualcuno ha introdotto i due PHPDoc `/** @var string */` sulle costanti (probabile tentativo di fix del bucket typeCoverage con il pattern sbagliato, PHPDoc invece di type declaration nativa — vedi `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`), causando una piccola regressione locale poi corretta in questa sessione con il fix nativo `private const string`.

### Pint

`vendor/bin/pint --dirty` (scope: solo il file toccato) → `{"tool":"pint","result":"passed"}`, nessuna riformattazione necessaria.
