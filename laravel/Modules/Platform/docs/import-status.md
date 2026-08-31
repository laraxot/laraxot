---
title: "Import status — Platform (replica)"
type: concept
tags: [platform, replica, import, wave3, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Platform import status wave3 replica enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./composer-manifest.md"
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Platform

> Replica Wave 3 (settings/company). **Non** merge in Tenant. **Non** cancellare.

- [x] Layout `app/` + XotBase SP / Route / Resources principali
- [x] QueueableAction
- [x] `modules_statuses=true` (2026-07-24)
- [ ] EventServiceProvider → XotBaseEventServiceProvider (se ancora Illuminate)
- [x] BaseModel / factories / Pest (4 file, 27 test — vedi sotto)
- [x] PHPStan L10 rieseguire post-enable (`Modules/Platform/app` → 0 errori reali)

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## Test migrati da SRC (gestionale_commesse) — 2026-07-24

Migrati/adattati e **passanti**: 4 file, 27 test totali (`vendor/bin/pest Modules/Platform/tests`).

- `tests/Feature/AuditLogTest.php` (2 test) — namespace adattati a `Modules\Platform\Models\*`, `Modules\User\Models\User`, `Modules\Customer\Models\Customer` (struttura flat del modulo target, non `Infrastructure/Domain/Application` come in SRC).
- `tests/Feature/CompanyProfileProviderTest.php` (9 test)
- `tests/Feature/ModelsTest.php` (4 test)
- `tests/Unit/CompanyProfileTest.php` (12 test) — namespace `Modules\Platform\ValueObjects\CompanyProfile` (non `Domain\ValueObjects`).

### Bug reali trovati e corretti in questa sessione

1. **FK type mismatch `platform_settings.updated_by_user_id` / `audit_logs.actor_user_id`**: le migrazioni usavano `$table->foreignId(...)->constrained('users')` (bigint), ma `users.id` in questo target è `string(36)` (UUID). La migrazione falliva con `SQLSTATE[HY000]: 3780` in MySQL. Corretto in `uuid()` (colonna indicizzata, non FK — vedi punto 2) e aggiornati i docblock `@property` dei modelli `PlatformSetting`/`AuditLog` da `int|null` a `string|null`.
2. **FK cross-connection impossibile**: i modelli `Platform` vivono sulla connessione `xot`, mentre `User` vive sulla connessione separata `user` (DB/schema diverso). Un vincolo FK DB-level fra le due non è enforce-abile (SQLite fallisce con `no such table: main.users` al primo insert quando `foreign_key_constraints=true`). Rimosso `->constrained('users')`, mantenuta solo colonna UUID indicizzata; le relazioni Eloquent `belongsTo` restano valide (non richiedono la stessa connessione).
3. `Modules/Platform/tests/TestCase.php` non seguiva il pattern condiviso "dati sacri" (`prepareSharedFixcitySqliteForTesting()` + `$connectionsToTransact=['sqlite']` + `UserServiceProvider`) usato da Gdpr/Activity/HR — senza, i test Feature fallivano con `no such table: platform_settings` perché la connessione `xot` è sqlite `:memory:` per default e le tabelle non vengono create automaticamente (niente `RefreshDatabase`, vietato per progetto). Allineato al pattern canonico.
4. Migrate una tantum eseguita (non ripetibile automaticamente, va rifatta se il file `fixcity_data.sqlite` o i DB mysql di test vengono rigenerati): `Modules/Platform` + `Modules/Customer` su `database/fixcity_data.sqlite` (connessione `xot`), `Modules/User` su DB mysql `techplanner_user_test` (connessione `user`), `Modules/Platform` anche su `techplanner_data_test` (connessione default `.env.testing`, usata da alcuni path legacy).

### Non migrati da SRC (18/22 file) — motivazione

Gli altri test SRC (`AsdSociPanelAccessTest`, `CustomerSupplierPanelsTest`, `DashboardWidgetsRenderTest`, `DashboardWorkedHoursSanitizationTest`, `DdsPanelAccessTest`, `EnergyBrokerPanelAccessTest`, `GerardoDemo*Test`, `GivebackPlatformPanelAccessTest`, `PanelAreaIdentityTest`, `PlatformSettingEditPageTest`, `ThermoPanelAccessTest`, `TimberPanelAccessTest`, `WtsPanelAccessTest`, `DemoUserPanelAccessSeederTest`, `ElectricianDemoCommercialDataSeederTest`, `SyntheticDataSeederAgendaCoverageTest`, `ThermoInstanceBootstrapSeederTest`, `OperationalLookupsSeederTest`) presuppongono uno scaffolding SRC assente in questo target: pannelli Filament dedicati per-tenant (`Asd`, `Dds`, `Wts`, `Timber`, `Thermo`, `EnergyBroker`, `GivebackPlatform`) sotto `App\Filament\*` e moduli plurali (`Interventions`, `Quotations`, `WorkOrders`, `Customers`) che qui sono singolari o non presenti allo stesso modo. Il target ha un solo `AdminPanelProvider` per modulo, non un pannello per cliente demo. I seeder citati (`ImpiantiElettricIDemoSeeder`, `OperationalLookupsSeeder`, `SyntheticDataSeeder`, `ThermoInstanceBootstrapSeeder`, ecc.) **esistono** in `Modules/Platform/database/seeders/`, ma i relativi test verificano tabelle/moduli (`work_order_statuses`, `catalog_frequencies`, pannelli demo) non ancora verificati equivalenti in questo target — richiedono un'analisi dedicata caso per caso, non fatta in questa sessione per limiti di tempo.

### PHPStan

`vendor/bin/phpstan analyse Modules/Platform/app --memory-limit=-1` → 0 errori reali (solo un errore di rumore "ignoreErrors pattern not matched", tipico quando si analizza uno scope più stretto di quello configurato in `phpstan.neon`).

Includendo `tests/` nello scope emergono ~62-83 errori `method.internalClass` (`Call to method toBe() of internal class Pest\Mixins\Expectation<TValue> from outside its root namespace Pest`) su **qualunque** `expect($scalar)->toBe($scalar)` in **qualunque modulo** (riprodotto anche in `Modules/Gdpr` con un file di prova sintetico) — è un'incompatibilità pre-esistente fra Larastan e la versione di Pest installata, non introdotta in questa sessione e non specifica a Platform. Non risolta (fuori scope): richiederebbe verificare `vendor/pestphp/pest/extension.neon` (`universalObjectCratesClasses` non include `Pest\Mixins\Expectation`) o allineare i pattern di assert usati nei test.

### Aperto

- Riverificare `PHPStan L10` includendo `tests/` una volta risolta l'incompatibilità Pest/Larastan globale (non bloccante per questo modulo).
- Valutare, modulo per modulo, quali dei 18 test SRC non migrati siano adattabili con giudizio (in particolare i seeder-test, se le tabelle referenziate esistono con nomi equivalenti).
- `EventServiceProvider → XotBaseEventServiceProvider` resta aperto (invariato da prima di questa sessione).

## Delta seconda tranche 2026-08-29 — NavigationGate portato ✅

Portato dal sorgente (delta `15f07e80..HEAD`): sistema di navigazione runtime.
- `app/Models/NavigationPreference` (XotBaseModel, fillable, casts()) + migration XotBase
  `navigation_preferences` (uuid updated_by_user_id, niente FK cross-connection)
- `app/Support/Navigation/{NavigationGate,NavigationClassifier,NavigationCatalog}`
- `app/Filament/Concerns/{GatesResourceNavigation,GatesPageNavigation}` +
  `app/Filament/Resources/GatedXotBaseResource` (equivalente Laraxot del GatedResource sorgente,
  necessario perché due trait con gli stessi metodi statici collidono)
- `NavigationSettingsPage` (XotBasePage) + view `platform::filament.pages.navigation-settings`
  + chiavi lang it/en `platform.navigation_settings.*`
- PlatformSettingResource e AuditLogResource → GatedXotBaseResource
- Test: NavigationGateTest (7) + NavigationSettingsPageTest (4) portati e adattati
  (panel catalog::admin, permission Shield ViewAny:Product, UserFactory)

Non portati (differiti): seeders demo BoutiqueIdraulica/IdroclimaRecaldini/Tomasi — dipendono
da classi Compliance (CalculateFGasObligationAction, ThermalPlantRegistry) del delta Compliance;
verranno portati insieme a quello. I diff M restanti del sorgente erano solo stile Pint.

Suite Platform+Catalog: 49 passed, PHPStan = 0.

## 2026-08-31 — Widget di riepilogo dal codice app/ mai portato

Portati da `gestionale_commesse/app/Filament/Widgets` i cinque widget di dashboard
rimasti fuori dalla prima tranche, ciascuno nel modulo che possiede i dati:

| Widget | Modulo | Perche' li' |
|---|---|---|
| `OperationsOverviewStats` | Platform | attraversa commesse, interventi e assenze: non appartiene a nessuno dei tre |
| `InterventionsPerformanceChart` | Intervention | |
| `WorkOrdersStatusChart` | WorkOrder | |
| `QuotationsByStatusChart` | Quotation | |
| `QuotationsValuePerMonthChart` | Quotation | |

Il trait `ResolvesPanelUserScope` (che decide se restringere i dati all'operatore
corrente) sta in Platform: lo usano widget di moduli diversi.

`parse_url()` sul referer: con la versione Safe un referer malformato solleva invece
di restituire `false`. Estratto in `refererPath()`, che tratta un referer non valido
come referer assente — e' quello che il codice voleva dire davvero.

Test `DashboardWidgetsTest`: monta tutti e cinque i widget. Interrogano tre moduli
diversi, quindi un nome di colonna o una relazione cambiata altrove si vede subito qui.

## 2026-08-31 — Panel cliente e fornitore

Portati da `app/Providers/Filament/{Customer,Supplier}PanelProvider` e riscritti su
`XotBasePanelProvider`. Stanno in Platform come l'operator panel: definiscono una
superficie per un tipo di utente, non un dominio.

- `/customer`: commesse, fatture, calendario interventi del cliente;
- `/supplier`: magazzini e movimentazioni.

### Perche' e' nato `discoverModuleComponents`

`XotBasePanelProvider` scopre automaticamente resource, pagine e widget del modulo
proprietario. Per un pannello aperto a utenti esterni questo e' sbagliato: il pannello
cliente si ritrovava dentro `AuditLogResource` di Platform e — verificato con un test —
rispondeva **200 a un cliente qualunque** su `/customer/audit-logs`.

Il gate della resource non bastava: `AccountFeatures::currentPanelAllowsFeature()` nel
ramo `customer` traduce `admin.audit_log` in `customer.admin.audit_log` con default
**true**, perche' quel default serve a far vedere al cliente le sue commesse e le sue
fatture. Alzare la barriera li' avrebbe chiuso anche quelle.

La causa vera era un'altra: quelle schermate non dovevano proprio essere nel pannello.
`XotBasePanelProvider::$discoverModuleComponents` (default `true`, invariato per tutti
gli altri) permette di spegnere la scoperta; i due pannelli esterni montano a mano le
poche schermate che servono. Il gate resta come seconda linea, non come unica.

`ExternalPanelsTest` copre entrambi i lati: cosa vede il cliente, cosa non vede, e che
un cliente non entri nel pannello del fornitore.
