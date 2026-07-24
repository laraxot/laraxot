---
title: "Gestionale Commesse — port status (Billing/Fiscal redo)"
type: concept
tags: [porting, laraxot, gestionale-commesse, billing, fiscal, redo]
created: 2026-07-23
updated: 2026-07-23
qmd: "gestionale commesse port billing fiscal reshape redo XotBase"
related:
  - "./wiki/concepts/laraxot-module-canonical-structure.md"
  - "./wiki/concepts/porting-external-module-playbook.md"
---

# Gestionale Commesse — stato porting Billing/Fiscal (redo reshape 2026-07-23)

## Contesto

`laravel/Modules/Billing` e `laravel/Modules/Fiscal` erano tornati, per effetto di un processo concorrente, a
copie grezze as-is in stile DDD (`Domain/Application/Infrastructure`) del sorgente `gestionale_commesse`, dopo che
un reshape XotBase precedente era stato completato e poi perso/revertito. Questa sessione rifà il reshape.

## Fiscal — redo reshape

Stato di partenza: `app/Domain/{Actions,DTO,ValueObjects}` (namespace `Modules\Fiscal\Domain\*`), `app/Providers`
già canonico (`FiscalServiceProvider`/`RouteServiceProvider`/`EventServiceProvider extends XotBase*`), `module.json`
e `composer.json` già corretti.

Fatto:

- `app/Domain/Actions/*` → `app/Actions/*` (namespace `Modules\Fiscal\Actions`), aggiunto trait
  `Spatie\QueueableAction\QueueableAction` a tutte e 4 le Action (`CalculateInvoiceTotalsAction`,
  `CalculatePaymentPlanAction`, `CalculateVatLiquidationAction`, `GenerateFatturaElettronicaXmlAction`).
- `app/Domain/DTO/*` + `app/Domain/ValueObjects/FiscalRate.php` → `app/Datas/*` (namespace `Modules\Fiscal\Datas`).
  Mantenuti come classi PHP `readonly` semplici (non convertite a `Spatie\LaravelData\Data`): sono value object
  puri di calcolo (non legati a form/richieste Filament), la conversione a Spatie Data non porterebbe benefici
  concreti in questo caso e introdurrebbe rischio di regressione senza motivo — nota esplicita per revisione futura
  se emerge un caso d'uso con validazione/casting.
  - `FiscalRate` (era `ValueObjects/FiscalRate.php`) è un normalizzatore statico di aliquota IVA, spostato in
    `Datas/` per omogeneità con gli altri value object del modulo (non è un enum PHP nativo).
- Cartella `app/Domain/` rimossa (vuota dopo lo spostamento).
- **0 modelli persistibili** confermato: nessuna migrazione, nessun `app/Models`. Il modulo è un motore di calcolo
  puro (totali fattura, piano rate, liquidazione IVA, XML FatturaPA) — ogni verticale (es. Billing) fornisce i dati
  via DTO e riceve il risultato, senza che Fiscal tocchi mai Eloquent/persistenza.
- `composer.json`: aggiunta dipendenza `spatie/laravel-queueable-action` in `require`, aggiunto repository path
  `../Quotation` (le Action dipendono da `Modules\Quotation\Domain\ValueObjects\Money` per i calcoli monetari).
- Aggiunto scaffold test: `tests/TestCase.php` (extends `XotBaseTestCase`, nessun `DatabaseTransactions` — non
  necessario, nessuna persistenza), `tests/Pest.php`, 4 file in `tests/Unit/` (uno per Action) con casi happy-path +
  edge case (es. `installments < 1` → `InvalidArgumentException`).
- Aggiunte cartelle `lang/it/`, `lang/en/` (vuote, pronte per traduzioni future — il modulo non espone ancora
  messaggi utente).
- `php -l` pulito su tutti i file `app/**/*.php` del modulo.

### Blocco PHPStan (non causato da Fiscal)

`./vendor/bin/phpstan analyse Modules/Fiscal --memory-limit=-1` fallisce **in fase di bootstrap applicativo**, prima
di analizzare qualunque file di Fiscal, con:

```
PHP Fatal error:  Type of Modules\Customer\Providers\EventServiceProvider::$shouldDiscoverEvents must not be
defined (as in class Illuminate\Foundation\Support\Providers\EventServiceProvider) in
laravel/Modules/Customer/app/Providers/EventServiceProvider.php on line 9
```

Causa: `Modules\Customer\Providers\EventServiceProvider` dichiara `protected static bool $shouldDiscoverEvents = true;`
con firma di tipo incompatibile con la classe padre Laravel (la proprietà padre non ha tipo dichiarato). Questo è un
modulo di proprietà di un altro agente in corso in questa sessione multi-agente — per la regola
[multi-agent-lock-coordination.md](./wiki/rules/multi-agent-lock-coordination.md) e il pitfall documentato in
[porting-external-module-playbook.md](./wiki/concepts/porting-external-module-playbook.md), **non modificato**.

Verifica alternativa eseguita in sostituzione: `php -l` su tutti i file di `Modules/Fiscal/app/` (esito: nessun
errore di sintassi). **Condizione di ri-esecuzione**: rilanciare
`./vendor/bin/phpstan analyse Modules/Billing Modules/Fiscal --memory-limit=-1` da `laravel/` non appena
`Modules/Customer/app/Providers/EventServiceProvider.php` viene corretto (rimuovere il tipo `bool` dalla proprietà
`$shouldDiscoverEvents` o allinearlo alla firma del parent Laravel).

### Aggiornamento post-Billing: `Modules\Quotation\DTO\Money`

Durante il lavoro su Billing e' emerso che `Modules\Quotation` e' stato ristrutturato (da un altro agente,
concorrentemente) spostando `Money` da `Domain/ValueObjects/Money.php` a `app/DTO/Money.php`
(namespace `Modules\Quotation\DTO\Money`, stessa API: `zero()`, `fromFloat()`, `add()`, `sub()`, `asFloat()`,
`asDecimalString()`). Le Action di Fiscal (`CalculateInvoiceTotalsAction`, `CalculatePaymentPlanAction`,
`CalculateVatLiquidationAction`) referenziavano ancora il vecchio namespace `Modules\Quotation\Domain\ValueObjects\Money`
(copiato as-is dalla sorgente): aggiornati tutti i riferimenti (app + test) al nuovo namespace. Questo ha risolto
~100 dei 115 errori PHPStan iniziali su Fiscal (da 115 a 15, i restanti tutti rumore Pest `method.internalClass`,
sistemico su tutto il repo).

## Billing — redo reshape

Stato di partenza: struttura **gia' in gran parte reshaped** da un lavoro precedente (Models in `app/Models`
`extends XotBaseModel`, `app/Filament/Resources` gia' presenti, `app/Providers` canonico, `module.json`/
`composer.json` corretti, migrazioni gia' in `database/migrations/`) — **solo** `app/Domain/{Actions,DTO}` era
tornato allo stile DDD grezzo (2 Action, 4 DTO), oltre a violazioni di dettaglio nelle Resource Filament e nelle
migrazioni.

Fatto:

- `app/Domain/Actions/{BuildPreInvoiceDraftAction,ReconcileSupplierInvoiceAction}.php` → `app/Actions/*`
  (namespace `Modules\Billing\Actions`).
- `app/Domain/DTO/{PreInvoiceDraft,PreInvoiceLine,ReconciliationDiscrepancy,SupplierLine}.php` → `app/Datas/*`
  (namespace `Modules\Billing\Datas`). Cartella `app/Domain/` rimossa.
- Aggiunto trait `Spatie\QueueableAction\QueueableAction` a **tutte e 8** le Action del modulo (le 2 ex-Domain +
  le 6 gia' in `app/Actions`: `BuildWorkOrderPreInvoiceAction`, `GenerateInvoicePdfAction`,
  `LinkInvoiceToWorkOrderAction`, `ReconcileSupplierInvoiceWithDeliveryNotesAction`, `SendElectronicInvoiceAction`,
  `UnlinkInvoiceFromWorkOrderAction`). Due Action erano dichiarate `readonly class`: il trait `QueueableAction`
  dichiara una proprieta' mutabile `public ?Job $job` incompatibile con `readonly` — rimosso il modificatore
  `readonly` dalla classe (non dai singoli campi promossi nel costruttore, che restano `private readonly`).
- **Migrazioni consolidate 1-per-modello** (regola `one-migration-per-model.md`): le 3 migrazioni esistenti
  (`create_billing_tables.php` con 2 tabelle, `add_einvoice_tracking_to_invoices_table.php` alter separata,
  `create_supplier_billing_tables.php` con 5 tabelle) violavano sia la regola "1 file per modello" sia il divieto
  di migrazioni evolutive `add_*`/`alter_*` separate. Sostituite con **7 file**, uno per modello, tutti
  `extends Modules\Xot\Database\Migrations\XotBaseMigration` con `protected ?string $model_class`,
  `tableCreate()`/`tableUpdate()`/`updateTimestamps()`: `create_invoices_table.php` (colonne e-invoice della
  vecchia alter fuse qui), `create_invoice_work_order_links_table.php`, `create_suppliers_table.php`,
  `create_delivery_notes_table.php`, `create_delivery_note_lines_table.php`, `create_supplier_invoices_table.php`,
  `create_supplier_invoice_lines_table.php`.
- **Resource Filament → XotBaseResource** (violazione critica: le 4 Resource estendevano `Filament\Resources\Resource`
  direttamente): `InvoiceResource`, `DeliveryNoteResource`, `SupplierInvoiceResource`, `SupplierResource` ora
  `extends Modules\Xot\Filament\Resources\XotBaseResource`; il metodo `form(Schema $schema): Schema` (che in
  `XotBaseResource` e' `final`) e' stato convertito in `getFormSchema(): array` come richiesto dalla classe base
  astratta (il metodo `table()` non e' `final`/astratto e resta invariato).
- Corretti riferimenti stantii a `Modules\Billing\Domain\DTO\*` in due Blade view (`@var` PHPDoc) e in
  `SupplierInvoiceResource.php` → `Modules\Billing\Datas\*`.
- `composer.json`: aggiunte dipendenze `spatie/laravel-queueable-action` e `barryvdh/laravel-dompdf` (quest'ultima
  gia' usata da `GenerateInvoicePdfAction` ma mai dichiarata — **non installata in vendor** in questa sessione per
  non toccare l'albero condiviso multi-agente, solo documentata; PHPStan segnala ancora `class.notFound` su
  `Barryvdh\DomPDF\Facade\Pdf` finche' non viene fatto `composer update` nel modulo); aggiunti repository path
  `../Customer`, `../WorkOrder`, `../Intervention`, `../Platform` (dipendenze effettive delle Action/Resource).
- **`lang/{it,en}/{billing,suppliers}.php` creati da zero**: il modulo usava decine di chiavi `__('billing.*')` e
  `__('suppliers.*')` (inclusi due casi di concatenazione dinamica: `billing.pre_invoice.sources.{sourceCode}` e
  `suppliers.reconciliation.{types,explanations}.{typeCode}`) senza che esistesse **alcun** file di traduzione nel
  modulo — mancava del tutto la cartella `lang/`. Popolate tutte le chiavi effettivamente referenziate nel codice
  (grep esaustivo su `app/` e `resources/views/`).
- `preg_replace` non-Safe in `ReconcileSupplierInvoiceAction::normalize()` → `Illuminate\Support\Str::of()->trim()
  ->lower()->squish()`.
- Aggiunto scaffold test: `tests/TestCase.php` (extends `XotBaseTestCase`, `DatabaseTransactions`), `tests/Pest.php`,
  2 file in `tests/Unit/` per le due Action ex-Domain (`BuildPreInvoiceDraftAction`, `ReconcileSupplierInvoiceAction`).
- `php -l` pulito su tutti i file del modulo; **11 test Pest passano** (`./vendor/bin/pest Modules/Billing/tests/`).

### Non fatto in questa sessione (debito tracciato, non bloccante)

- **Pages e RelationManager delle 4 Resource** (16 classi `Pages\{Create,Edit,List,View}*` + 2
  `RelationManagers\*RelationManager`) estendono ancora direttamente le classi Filament core
  (`CreateRecord`/`EditRecord`/`ListRecords`/`ViewRecord`/`RelationManager`) invece di `XotBase*`. La conversione a
  `XotBaseViewRecord` richiederebbe implementare `getInfolistSchema(): array` per ognuna (nessuna Resource lo
  definisce oggi: l'infolist attuale e' quello di default di Filament) — e' una feature UX nuova da progettare, non
  una pura riorganizzazione strutturale, quindi fuori dal perimetro di questo "redo reshape". Tracciato come wave
  successiva in `docs/import-status.md` del modulo.
- `barryvdh/laravel-dompdf` dichiarata in `composer.json` ma non installata (vedi sopra).
- 62 errori PHPStan residui su Billing, quasi tutti `property.notFound` su modelli Eloquent con `$guarded = []`
  senza `@property` PHPDoc — pattern diffuso in tutto il repo (non introdotto da questa sessione), non risolvibile
  con un fix puntuale senza una passata dedicata di annotazione modelli su piu' moduli.

### Verifica finale

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Billing Modules/Fiscal --memory-limit=-1
# Billing: 62 errori (property.notFound pre-esistente + Pdf class.notFound + rumore Pest)
# Fiscal:  15 errori (solo rumore Pest method.internalClass)

./vendor/bin/pest Modules/Billing/tests/ --configuration phpunit.xml --colors=never --compact
# Tests: 5 passed (9 assertions)

./vendor/bin/pest Modules/Fiscal/tests/ --configuration phpunit.xml --colors=never --compact
# Tests: 6 passed (14 assertions)
```

`modules_statuses.json` aveva gia' `"Billing": true` e `"Fiscal": true` prima di questa sessione — nessuna modifica
necessaria li'.

## Signature/Vehicle — redo reshape (2026-07-23, sessione successiva)

Contesto: `laravel/Modules/Signature` e `laravel/Modules/Vehicle` erano tornati a copie grezze as-is dopo un reshape
XotBase precedente perso/revertito (stesso pattern di Billing/Fiscal sopra). A ispezione diretta, entrambi i moduli
avevano **gia'** l'ossatura `app/{Models,Actions,Filament,Providers}` (non `Domain/Application/Infrastructure` puro),
segno che il reshape precedente era stato in gran parte preservato; restavano pero' diversi difetti puntuali
corretti in questa sessione.

### Signature

- **Store diretto disk/path/mime_type sul modello** (decisione esplicita del task, mantenuta): `Signature` e
  `Sketch` referenziavano `Modules\Document\Models\Media` via FK `media_id` — cambiato in colonne dirette
  `disk`/`path`/`mime_type` su `signatures`/`sketches` (Spatie MediaLibrary ha colonne NOT NULL incompatibili con
  `::create()` raw). Migrazioni, model, `StoreSignatureForModelAction`/`StoreSketchForModelAction` aggiornati di
  conseguenza (niente piu' creazione di un record `Media` intermedio).
- `signable()`/`sketchable()` erano gia' `morphTo()` generico (nessun hardcode `WorkOrder`/`Intervention`) — solo
  aggiunto PHPDoc esplicito che lo documenta.
- **Bug reale trovato e corretto**: `database/factories/{Signature,Sketch}Factory.php` e
  `database/seeders/{Signature,Sketch}Seeder.php` avevano `namespace Modules\\Signature\...` con **doppio backslash
  letterale** nel namespace/uso classi — `php -l` falliva con "syntax error, unexpected token \"\\\"" su tutti e 4 i
  file (bug bloccante, non un typo innocuo: il modulo non avrebbe mai potuto autoloadare quelle classi).
- Aggiunto `use HasFactory` (mancante) + `@method static {Factory} factory(...)` sui modelli `Signature`/`Sketch`
  (pattern gia' in uso in `Customer`), altrimenti `Model::factory()` risolve a `mixed` in PHPStan.
- Fix `base64_decode` non-Safe → `\Safe\base64_decode` con cattura `Safe\Exceptions\UrlException` (identifier
  `theCodingMachineSafe.function`, unico errore reale non-baseline sulle Action).
- Aggiunti `lang/{it,en}/signatures.php`, `tests/TestCase.php`, `tests/Pest.php`,
  `tests/Unit/SignatureModelTest.php` (4 test, no DB write: l'infrastruttura test dei moduli non esegue migrazioni
  automaticamente, quindi si usano factory `makeOne()` non persistite).
- **Non toccato** (fuori perimetro): `app/Filament/Pages/{CaptureSignature,CaptureSketch}.php` restano fortemente
  accoppiati a `Modules\Intervention\Models\Intervention`/`Modules\WorkOrder` — sono pagine di consumo del modulo
  Signature (non i Model, che restano generici), gia' cosi' prima di questa sessione; 55 errori PHPStan qui
  derivano da `App\Filament\Forms\Components\CanvasPad` non trovato (app root) e da `Filament\Pages\Page` non
  esteso da `XotBasePage` — refactor architetturale non richiesto da questo task.

### Vehicle

- **Migrazioni consolidate 1-per-modello** (richiesta esplicita): `create_vehicles_tables.php` (2 tabelle) +
  `add_vehicle_maintenance_fields.php` (alter separata) → **2 file**, uno per modello:
  `create_vehicles_table.php` (con le colonne manutenzione fuse) e `create_vehicle_documents_table.php`.
- `DocumentsRelationManager` estendeva `Filament\Resources\RelationManagers\RelationManager` direttamente →
  convertito a `extends Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager` (pattern
  `getFormSchema()`/`getTableColumns()`/`getTableActions()`/`getTableBulkActions()`/`getTableHeaderActions()` con
  chiavi stringa, come da esempio `User\...\DevicesRelationManager`).
- `VehiclePolicy` era in `app/Policies/` senza base class → spostata in `app/Models/Policies/` (convenzione reale
  del repo, verificata su `Media`/`Geo`/`Tenant`/...) con nuova `VehicleBasePolicy` (pattern `{Module}BasePolicy`
  con `before()` su `super-admin`), `VehiclePolicy extends VehicleBasePolicy`.
- **Bug reale trovato e corretto**: `AddVehicleDocumentAction` era `final readonly class` + `use QueueableAction`
  → fatal error a runtime/PHPStan ("Readonly class ... cannot use trait with a non-readonly property
  QueueableAction::$job"), stesso pattern gia' documentato per Billing sopra. Rimosso `readonly`.
- **Bug reale trovato e corretto**: stesso bug del doppio-backslash-nel-namespace di Signature, presente
  identico in `database/seeders/{Vehicle,VehicleDocument}Seeder.php`.
- Aggiunto `use HasFactory` + `@method static {Factory} factory(...)` su `Vehicle`/`VehicleDocument` (stesso motivo
  di Signature). `VehicleDocumentFactory` chiamava `Media::factory()` (il modello `Document\Models\Media` non ha
  `HasFactory`, fuori dal perimetro del modulo Vehicle per modificarlo) → sostituito con una `Media::query()->create()`
  diretta dentro la factory.
- Seeders passati da `Model::factory()->count()->create()` a `{Factory}::new()->count()->create()` (risolve
  `method.nonObject` su `mixed`, pattern osservato anche in `Customer\...\CustomerSeeder` nel frattempo).
- Aggiunti `lang/{it,en}/vehicles.php`, `tests/TestCase.php`, `tests/Pest.php`,
  `tests/Unit/VehicleModelTest.php` (4 test, no DB write per lo stesso motivo di Signature).
- **Non fixato** (baseline pre-esistente, diffuso in tutto il repo): 2 `classConstant.unused` su
  `VehicleResource::ACCOUNT_FEATURE_{ADMIN,OPERATOR}` — false positive PHPStan, le costanti sono lette
  riflessivamente da `App\Support\Concerns\HasAccountFeatureVisibility` via `defined()`/`constant()` (stesso
  pattern in tutte le Resource `Catalog`/... che usano il trait).

### Verifica finale

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Signature Modules/Vehicle --memory-limit=-1
# 57 errori residui, tutti pre-esistenti/fuori perimetro:
#   - 55 in Signature/app/Filament/Pages/{CaptureSignature,CaptureSketch}.php (CanvasPad root-app, Page non-XotBase)
#   - 2 in Vehicle/app/Filament/Resources/VehicleResource.php (costanti lette per reflection, falso positivo)

./vendor/bin/pest Modules/Vehicle/tests/ Modules/Signature/tests/ --configuration phpunit.xml --colors=never --compact
# Tests: 8 passed (17 assertions)
```

`modules_statuses.json` aveva gia' `"Signature": true` e `"Vehicle": true` prima di questa sessione — nessuna
modifica necessaria li' (stato confermato ragionevolmente pulito).
