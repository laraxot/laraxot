---
name: "XOT-18.13 Compliance PHPStan L10: Fix 22 Errors"
description: "Resolve 22 PHPStan (level max) errors in Compliance module: Filament v5 deprecations + type casting"
owner: "Compliance"
status: "done"
created: "2026-09-06"
completed: "2026-09-06"
---

## Intent

Fix all PHPStan (level max, `laravel/phpstan.neon`, non modificato) errors in
`Modules/Compliance`: Filament v5 API deprecations plus `mixed` casts in demo
seeders, without suppressing anything via `@phpstan-ignore` or baseline entries.

## Baseline (verified via `./vendor/bin/phpstan analyse Modules/Compliance --memory-limit=-1`)

22 errors, all real (no false positives):

### Filament v5 deprecations (19)

**`Placeholder::make()->content()` -> `TextEntry::make()->state()`** (2 errors, 1 file):
- `ComplianceDocumentResource.php:86`

**`Table::actions()` -> `recordActions()`** (9 errors):
- ComplianceDocumentResource.php:98
- CustomerSystemResource.php:109
- CustomerSystemResource/RelationManagers/SchemasRelationManager.php:91
- EquipmentResource.php:81
- HeatingSystemResource.php:100
- HeatingSystemResource/RelationManagers/BoilersRelationManager.php:67
- HeatingSystemResource/RelationManagers/ControlsRelationManager.php:31
- SubcontractorResource.php:75
- WorkOrderChangeOrderResource.php:73

**`Table::bulkActions()` -> `toolbarActions()`** (6 errors):
- ComplianceDocumentResource.php:98
- CustomerSystemResource.php:109
- EquipmentResource.php:81
- HeatingSystemResource.php:100
- SubcontractorResource.php:75
- WorkOrderChangeOrderResource.php:73

**`Action::form()` -> `Action::schema()`** (2 errors, 1 file):
- HeatingSystemResource/RelationManagers/BoilersRelationManager.php:92, :130

(Nota: una bozza precedente di questa story, scritta da un agente concorrente
nello stesso run, elencava anche
`EquipmentResource/RelationManagers/PumpStatusHistoryRelationManager.php` — quel
file non esiste nel modulo; rimosso dall'inventario perche' non verificato dal
baseline reale.)

### Type casting su `mixed` (3)

- `database/seeders/ComplianceDemoSeeder.php:573` — `Cannot cast mixed to string.` (in `documentPng()`)
- `database/seeders/ComplianceDemoSeeder.php:690` — `Cannot cast mixed to string.` (in `documentSvg()`)
- `database/seeders/HeatingDemoSeeder.php:92` — `Cannot cast mixed to int.` (in `customerIds()`)

## Solution

- Rinominati i metodi Filament v5 senza toccare la logica applicativa
  (`actions()`->`recordActions()`, `bulkActions()`->`toolbarActions()`,
  `form()`->`schema()` sulle `Action`).
- Sostituito `Placeholder::make('file_preview')->content(fn... => HtmlString)`
  con `TextEntry::make('file_preview')->state(fn... => HtmlString)`: `Entry::state()`
  accetta `mixed` e il layer `CanFormatState` di Filament riconosce
  automaticamente un `Htmlable` (la `HtmlString` gia' restituita da
  `filePreview()`) e lo renderizza come HTML, senza bisogno di `->html()`
  esplicito.
- Nei 2 seeder, risolto ogni `cast.string`/`cast.int` con narrowing esplicito
  (`is_string($line) ? $line : ''`, `is_numeric($id) ? (int) $id : 0`) invece di
  un cast diretto su un valore `mixed` — stesso pattern gia' in uso nei commit
  precedenti del modulo (vedi `git log --oneline` su
  `HeatingSystemResource.php`/`CustomerSystemResource.php`).

## Acceptance Criteria

- [x] `./vendor/bin/phpstan analyse Modules/Compliance --memory-limit=-1` -> 0 errori (era 22).
- [x] Nessun `@phpstan-ignore` introdotto.
- [x] `php -l` pulito su tutti i file toccati.
- [x] Pest: nessuna regressione (18 passed/4 failed -> 22 passed/4 failed; i 4
      fallimenti sono preesistenti e ambientali, vedi `docs/coverage.md`).
- [x] Coverage aumentata: nuovo `tests/Unit/ComplianceDocumentFilePreviewTest.php`
      (4 test, 7 assertion) su `ComplianceDocumentResource::filePreview()`.
- [x] `docs/coverage.md` aggiornato con numeri prima/dopo (PHPStan, PHPMD, Pest).
- [ ] Git sync (fetch + merge + push su `laraxot`) — vedi sezione Results per lo stato reale al momento della chiusura di questa story.

## Results

**PHPStan**: 22 -> 0 errori (verificato con `clear-result-cache` + run pulito).

**Pest** (`./vendor/bin/pest Modules/Compliance/tests --no-coverage`): 18 passed/4
failed (44 assertion) -> 22 passed/4 failed (51 assertion). I 4 fallimenti sono
identici prima e dopo (tabella `customers` mancante nel DB di test condiviso,
colonna `fgas_leak_checks.performed_by_user_id` mancante) — ambientali, non
causati da questa story, dettagli in `docs/coverage.md`.

**PHPMD**: nessuna nuova violazione sui file Resource/RelationManager. Sui 2
seeder, il narrowing per PHPStan sposta di poco un debito preesistente
(`ExcessiveClassComplexity` 60->62 su `ComplianceDemoSeeder`, gia' oltre soglia
prima di questa story) — dettagli in `docs/coverage.md`.

**Git sync**: vedi commit di chiusura in
`laravel/Modules/Compliance` (repo git indipendente, remote `laraxot`) e
l'aggiornamento in coda a
`docs/chat/2026-09-06-pest-v5-done-plus-phpstan-parallel-wave.md` per hash ed
esito push reale.

## Update 2026-09-21 — 7 nuovi errori (file successivi alla baseline), 0 residui

Campagna full-tree `phpstan analyse Modules` (tutti i moduli, ordine casuale,
swarm) ha trovato 7 errori reali in Compliance, in file scritti dopo la baseline
del 2026-09-06 (quindi non una regressione di questa story, codice nuovo mai
passato da un giro phpstan):

1. `tests/Feature/ConformityDeclarationTest.php` — `postInc.type` su
   `static $n = 0; $n++;` dentro l'helper globale `makeDico()`. Fix: `/** @var
   int $n */` sopra la dichiarazione static (pattern documentato PHPStan per
   variabili static, non un override di un tipo gia' corretto).
2. `app/Actions/PrepareConformityDeclarationForWorkOrderAction.php` — 2 errori
   `nullsafe.neverNull` reali su `$customer?->` (Customer e' garantito,
   `customer_id` non nullable): tolto `?->`, tenuto `??` per i campi
   effettivamente nullable (`tax_code`/`vat_number`).
3. Stesso file, 4 errori `nullsafe.neverNull` su `$site?->` (city/province/
   postal_code) e `$declaration->workOrder?->complianceDocuments` — QUESTI
   sono falsi positivi Larastan, non veri bug: `customer_site_id` e
   `work_order_id` sono nullable per schema, quindi l'accesso nullsafe e'
   davvero necessario a runtime. Root cause: bug noto di
   `ModelRelationsExtension` di Larastan — quando una magic property
   condivide il nome con un metodo di relation (`site()`/`workOrder()`),
   l'override `@property-read Tipo|null $nome` nel docblock del model viene
   letto per il tipo base ma la meta' `|null` dell'unione viene ignorata solo
   dal check `nullsafe.neverNull` (verificato con repro isolato: stesso
   comportamento identico fra `@property-read string $x` e `@property-read
   string|null $x`). Fix root-cause (non `@phpstan-ignore`/baseline/cast):
   sostituito `?->` con guardia `instanceof CustomerSite`/`instanceof
   WorkOrder` su variabile locale — PHPStan restringe correttamente il tipo
   via `instanceof` anche se non lo fa via `?->`, e non segnala "instanceof
   always true" (verificato: significa che il tipo `CustomerSite|null` e'
   davvero visto altrove, solo il check nullsafe specifico ha il bug).
4. `app/Actions/GeneratePosDraftForWorkOrderAction.php` — stesso identico
   pattern/fix su `$workOrder->site?->name`.
5. `app/Models/ConformityDeclaration.php`, `Modules/WorkOrder/app/Models/WorkOrder.php`
   — aggiunti/corretti i tag `@property-read` mancanti per le relation
   (`customer`, `site`, `workOrder`) a scopo di documentazione IDE/PHPStan,
   anche se per il caso nullable specifico il tag da solo non basta (vedi
   punto 3).

Verifica: `phpstan analyse Modules/Compliance Modules/WorkOrder
--memory-limit=-1` con cache pulita → 0 errori (esclusi i bucket strutturali
noti `typeCoverage`/`internalClass`, gia' documentati come non affrontabili
sotto il `phpstan.neon` attuale). Pint scoped sui 5 file toccati (non
`--dirty`, per non toccare WIP di altre sessioni sull'albero condiviso).

Nota: questa run ha trovato `docs/coverage.md` di questo modulo gia'
cancellato nell'albero di lavoro (non da questa sessione) — non ripristinato,
fuori scope, verosimile stesso fenomeno di
`feedback_coverage_md_working_tree_truncation.md` nel second brain.

File toccati: `app/Actions/PrepareConformityDeclarationForWorkOrderAction.php`,
`app/Actions/GeneratePosDraftForWorkOrderAction.php`,
`app/Models/ConformityDeclaration.php`,
`tests/Feature/ConformityDeclarationTest.php`,
`../WorkOrder/app/Models/WorkOrder.php` (modulo WorkOrder, story gemella li').
