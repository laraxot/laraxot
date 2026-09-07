---
name: inventory-coverage
description: Stato PHPStan e Pest per Modules/Inventory dopo la campagna zero-errori 2026-09-06 e il follow-up canonical-structure 2026-09-07
metadata:
  module: Inventory
  updated: 2026-09-07
---

# Coverage e qualita — Modules/Inventory

## PHPStan

- Prima: 52 errori (`cast.int` / `cast.string` su valori `mixed` provenienti da closure Filament `$get()`,
  `Model::getAttribute()`, `Collection::pluck()->all()`; piu' `method.deprecated` su `Table::actions()` /
  `Table::bulkActions()` e `method.deprecatedClass` / `staticMethod.deprecatedClass` su
  `Filament\Forms\Components\Placeholder`).
- Dopo: **0 errori** (`./vendor/bin/phpstan analyse Modules/Inventory` con cache pulita, livello del
  `phpstan.neon` del repo, invariato — nessuna modifica al file).

File corretti:
- `app/Filament/Pages/InventoryOverview.php`
- `app/Filament/Resources/InventoryMovementResource.php`
- `app/Filament/Resources/InventoryMovementResource/Pages/CreateInventoryMovement.php`
- `app/Filament/Resources/InventoryMovementResource/Pages/EditInventoryMovement.php`
- `app/Filament/Resources/WarehouseLocationResource.php`
- `app/Filament/Resources/WarehouseResource.php`
- `tests/Feature/WarehouseAndInventoryMovementCrudTest.php`

Pattern applicato: sostituiti tutti i cast diretti `(int)`/`(string)` su valori `mixed` con
`Modules\Xot\Actions\Cast\SafeIntCastAction::cast()` / `SafeStringCastAction::cast()` (azioni canoniche
gia' usate in Geo/Blog/Catalog per lo stesso problema — vedi
`Modules/Xot/docs/safe-casting-actions.md`), che tracciano il tipo reale invece di forzare un cast cieco.
`Filament\Forms\Components\Placeholder` (deprecata, estende `TextEntry`) sostituita con
`Filament\Infolists\Components\TextEntry` + `->state()` al posto di `->content()`.
`Table::actions()` / `Table::bulkActions()` (deprecati in Filament v4) sostituiti con
`recordActions()` / `toolbarActions()`, pattern gia' in uso in `Modules/Catalog`.

## PHPMD

`./tools/phpmd.sh Modules/Inventory text phpmd.xml` (il path `../docs/phpmd.ruleset.xml` indicato nella
consegna non esiste in questo repo; usato `laravel/phpmd.xml`, il ruleset effettivo del progetto)
segnala solo violazioni preesistenti in file non toccati da questo intervento
(`Actions/CreateInventoryMovementAction.php` ExcessiveParameterList, `Actions/GetStockSnapshotAction.php`
ShortVariable, `Policies/*` UnusedFormalParameter, `Providers/*ServiceProvider.php` CamelCasePropertyName).
Nessuna nuova violazione introdotta dai file modificati.

## PHPInsights

Non eseguito nella sessione 2026-09-06 (si riteneva rimosso dal repo). **Correzione 2026-09-07**:
`vendor/bin/phpinsights` e' installato e funzionante (`nunomaduro/phpinsights dev-master`); il solo
blocco reale e' `ForbiddenSecurityIssues` che richiede un `composer.lock` alla radice del path
analizzato — assente quando si scopa un singolo modulo. Workaround: config ephemeral in `/tmp` con
quell'insight rimosso (`'remove' => [ForbiddenSecurityIssues::class]`), non tocca la config condivisa
del repo. Risultati per Modules/Inventory 2026-09-07: Code 92.8, Complexity 92.0, Architecture 82.4,
Style 88.0 — tutti sopra la soglia di progetto (70). Vedi sezione "Follow-up 2026-09-07" sotto.

## Pest

`./vendor/bin/pest Modules/Inventory/tests --no-coverage`: 9 failed, 1 passed — **blocco preesistente,
non causato da questa modifica**: `SQLSTATE[42S02]: Base table or view not found: 1146 Table
'workorder_data_test.warehouses' doesn't exist`. Verificato con `php artisan migrate:status --env=testing`
che il DB di test condiviso ha decine di migrazioni `Pending` su piu' moduli (non solo Inventory) — stato
del DB di test antecedente a questa sessione, non un side-effect dei cast fix (che non toccano
migrazioni/schema). Non eseguito `migrate` per non rischiare interferenze con altri agenti che stanno
lavorando in concorrenza sullo stesso DB condiviso (regola "data sacra": nessuna operazione distruttiva o
a rischio sul DB condiviso senza autorizzazione esplicita del proprietario).

Nessuna regressione rispetto allo stato pre-esistente: i fallimenti sono tutti a livello di inserimento
DB (`WarehouseFactory` / `InventoryMovementFactory`), prima ancora che il codice modificato (Filament
Resources/Pages) venga eseguito.

## Follow-up 2026-09-07 — chiusura errori canonical-structure (story 4.14.4)

La story `4.14.4.inventory-canonical.story.md` (swarm epic-4, Schemas/Tables extraction su
`InventoryMovementResource`/`WarehouseResource`/`WarehouseLocationResource`) aveva lasciato 13 errori
PHPStan residui nei file appena estratti (non coperti dalla campagna 2026-09-06 sopra, che riguardava lo
stato pre-estrazione). Lavoro svolto in questa sessione, in concorrenza con l'agente della story 4.14.4
stesso (stesso modulo, stesso arco temporale — verificato via lock `bashscripts/lock/check.sh` prima di
ogni edit, sempre `FREE`, e via confronto hash/mtime dei file prima di editarli per evitare collisioni):

- **Risolti dall'agente 4.14.4 in corso (non da questa sessione, osservato tra due run consecutivi di
  `phpstan analyse`)**: 5 errori `staticMethod.notFound`/`return.type` in
  `InventoryMovementsTable.php` (chiamate `InventoryMovementResource::storageZonesEnabled()/
  workOrderLabel()/interventionLabel()` corrette in `InventoryMovementForm::...`, la classe che
  possiede davvero questi helper dopo l'estrazione) + 4 errori `return.unusedType` su
  `getDefaultTableSortColumn()`/`getDefaultTableSortDirection()` (`?string` → `string`) in
  `InventoryMovementsTable.php` e `WarehouseLocationsTable.php`.
- **Risolti in questa sessione**: 4 errori `method.childReturnType` residui — `getFormSchema()` di
  `WarehouseForm.php` e `WarehouseLocationForm.php` dichiarava `@return array<int, Component>` con un
  singolo elemento posizionale (`[Section::make()...]`), incompatibile con il contratto
  `array<string, Component>` di `XotBaseResourceForm::getFormSchema()`/`HasXotForm::getFormSchema()`.
  Fix: chiave esplicita `'section' => Section::make()...`, pattern gia' usato da
  `InventoryMovementForm::getFormSchema()` nello stesso batch di estrazione.

Risultato: `./vendor/bin/phpstan analyse Modules/Inventory --memory-limit=-1 --no-progress` →
**13 → 0 errori** (cache pulita). Nessuna modifica a `phpstan.neon`.

PHPMD (`./tools/phpmd.sh Modules/Inventory text phpmd.xml`): stesse violazioni preesistenti di cui sopra,
nessuna nuova sui 2 file toccati (`WarehouseForm.php`, `WarehouseLocationForm.php`).

Pest: stesso blocco preesistente descritto sopra (`workorder_data_test.warehouses` mancante), non
toccato per lo stesso motivo (DB di test condiviso, altri agenti/swarm in esecuzione concorrente sullo
stesso repo — vedi `docs/chat/INDEX.md` root, swarm `migration-foreignidfor-swarm` include Inventory tra
i moduli attivi nello stesso arco temporale). Coverage non misurabile in questa sessione per lo stesso
blocco; nessuna regressione introdotta (i 2 file toccati sono Filament Schemas, non coperti dai test
Feature/Unit falliti, che falliscono prima ancora di toccare la UI).
