---
title: "Porting gestionale_commesse — Platform (reshape completato, modulo permanente separato)"
type: concept
tags: [import, gestionale-commesse, platform, reshape, xotbase, bmad-readiness]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting Platform reshape XotBase modulo permanente separato audit BMAD"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
---

# Porting gestionale_commesse — Platform (reshape, 2026-07-24)

## Contesto

Un BMAD readiness audit (2026-07-24) aveva segnalato `Modules/Platform` come "raw DDD as-is
copy" (registrato `false` in `modules_statuses.json`). Decisione finale dell'utente: `Platform`
resta modulo **permanente e separato**, reshapato come gli altri moduli completati — **non**
va fuso in Tenant/Activity/Xot/AI (vedi sezione "Moduli gemelli — SUPERSEDUTO" nel playbook).

## Stato reale all'ingresso di questa sessione

Verifica diretta del contenuto (non fidandosi solo del flag): il modulo era **già
sostanzialmente reshapato**, non una copia grezza DDD. Presenti: `app/Models` (`PlatformSetting`,
`AuditLog`), `app/Filament/Resources` (`PlatformSettingResource`, `AuditLogResource`, entrambe
`extends XotBaseResource`), `app/Providers` (`PlatformServiceProvider extends
XotBaseServiceProvider`, `RouteServiceProvider extends XotBaseRouteServiceProvider`,
`EventServiceProvider`), `routes/{web,api}.php`, seeders/demo dataset multipli. Nessuna cartella
`Domain/Application/Infrastructure` residua.

Gap riscontrati e corretti in questa sessione:

- **Models non estendevano `XotBaseModel`**: `PlatformSetting` e `AuditLog` estendevano
  `Illuminate\Database\Eloquent\Model` direttamente, con `use App\Models\User;` (namespace
  applicativo sbagliato per un modulo). Corretto: entrambi ora `extends XotBaseModel`, import
  `Modules\User\Models\User`.
- **Migrazioni non usavano `XotBaseMigration`**: `create_platform_settings_table` e
  `create_audit_logs_table` erano `Migration` plain con `Schema::create()` diretto. Convertite a
  `XotBaseMigration` con `tableCreate()` + `updateTimestamps()` (solo per `platform_settings`,
  che ha `created_at`/`updated_at`; `audit_logs` ha solo `created_at` fisso, quindi nessuna
  `updateTimestamps()` lì per non introdurre colonne non usate dal modello).
- **Due migrazioni framework spurie**: `0001_01_01_000001_create_cache_table.php` e
  `0001_01_01_000002_create_jobs_table.php` erano state copiate dentro `Platform` — duplicati
  di tabelle già create da `Modules/Xot` (`create_cache_table`) e `Modules/Job`
  (`create_jobs_table`). Rimosse per evitare conflitti "tabella già esistente" nella migrazione
  multi-modulo.
- **`module.json` priority**: era `0`; `PlatformServiceProvider::boot()` referenzia modelli di
  `Billing`, `Customer`, `Intervention`, `Quotation`, `WorkOrder` (per registrare gli observer di
  audit) — portata a `200` per garantire che quei moduli siano già caricati.
- **`composer.json` authors**: aggiunto Davide Cavallini (co-autore convenzionale in tutti i
  moduli di questa porting session).
- **`lang/{it,en}/platform.php`**: assenti, creati con le chiavi base per `PlatformSetting` e
  `AuditLog`.
- **PHPStan max**: 3 errori reali risolti — `json_encode`/`json_decode` non-Safe in
  `EditPlatformSetting` (con gestione `Safe\Exceptions\JsonException` per l'input JSON libero
  dell'utente) e in `OperationalLookupsSeeder`; generic `MorphTo<Model, $this>` non risolto in
  `AuditLog::subject()` per import `Model` mancante.

## Non toccato (fuori scope, nessun problema riscontrato)

- `app/Services/AuditLogger.php` e `app/Settings/SettingsRepository.php`: classi con più metodi
  correlati (repository/logger), non azioni singole — lasciate come Services, non convertite in
  `*Action` con `QueueableAction` (avrebbe frammentato senza beneficio; la regola Action riguarda
  operazioni di business singole, non repository stateless con getter multipli).
- `app/Company/CompanyProfileProvider.php` + `app/ValueObjects/CompanyProfile.php`: value object
  e provider di lettura, coerenti con le convenzioni del repo, non richiedono reshape.

## Verifica

- `./vendor/bin/phpstan analyse Modules/Platform Modules/AiAssistant --memory-limit=-1` → 0
  errori reali (1 riga di rumore per un ignore-pattern globale del `phpstan.neon` condiviso,
  non pertinente a questo scope).
- `modules_statuses.json`: `"Platform": true` (corretto dopo verifica reale, non solo per
  fiducia nel flag precedente).

## Test migrati dal sorgente (2026-07-24)

Sorgente: `gestionale_commesse/tests/{Feature,Unit}/Platform/` (22 file). Portati **solo** i
test di copertura genuinamente applicabile alla nostra struttura Filament-only (nessun
multi-pannello custom, nessuna app root `App\Filament\*`); scartati quelli legati a
funzionalità fuori scope.

### Portati (`laravel/Modules/Platform/tests/`)

| File sorgente | File modulo | Note |
|---|---|---|
| `Unit/Platform/CompanyProfileTest.php` | `tests/Unit/CompanyProfileTest.php` | 1:1, solo namespace `Modules\Platform\ValueObjects\CompanyProfile` (era `Domain\ValueObjects`) |
| `Feature/Platform/CompanyProfileProviderTest.php` | `tests/Feature/CompanyProfileProviderTest.php` | 1:1, `Modules\Platform\Company\CompanyProfileProvider` + `Modules\Platform\Models\PlatformSetting` (erano `Application\Company` / `Infrastructure\Models`) |
| `Feature/Platform/AuditLogTest.php` | `tests/Feature/AuditLogTest.php` | Adattato: `Customer::factory()->create()` del modulo `Customer` locale al posto del `Customer` DDD sorgente; `test()->actingAs()` al posto di `Pest\Laravel\actingAs()` (bug noto, vedi sotto) |
| — (nuovo, non nel sorgente) | `tests/Feature/ModelsTest.php` | Coverage aggiuntiva per `PlatformSetting`/`AuditLog` (istanza + `PlatformSetting::getBool()`), sullo stile di `Modules/Customer/tests/Feature/ModelsTest.php` |

`tests/TestCase.php` e `tests/Pest.php` creati ex novo (mancavano): pattern `fixcity_data.sqlite`
condiviso + `DatabaseTransactions`, stesso schema di `Modules/HR/tests/TestCase.php` e
`Modules/Gdpr/tests/TestCase.php`.

### Scartati (fuori scope, nessuna azione richiesta)

Tutti i test di accesso ai pannelli multi-tenant custom del sorgente (`App\Filament\*` non
esiste in questo repo, sono pannelli specifici dell'installazione gestionale_commesse, non
del modulo `Platform` riusabile):

- `AsdSociPanelAccessTest`, `DdsPanelAccessTest`, `EnergyBrokerPanelAccessTest`,
  `GivebackPlatformPanelAccessTest`, `ThermoPanelAccessTest`, `TimberPanelAccessTest`,
  `WtsPanelAccessTest`, `PanelAreaIdentityTest`, `CustomerSupplierPanelsTest` — pannelli e
  ruoli custom (`asd_admin`, `dds_technician`, `thermo_supplier_call_center`, ecc.) e viste
  Blade specifiche dell'app sorgente, non presenti né richieste in questo modulo.
- `DashboardWidgetsRenderTest`, `DashboardWorkedHoursSanitizationTest` — dipendono da
  `App\Filament\Widgets\*` dell'app sorgente, non da `Modules\Platform`.
- `DemoUserPanelAccessSeederTest`, `ElectricianDemoCommercialDataSeederTest`,
  `GerardoDemoPanelAccessTest`, `GerardoDemoSeederTest`, `ThermoInstanceBootstrapSeederTest`,
  `SyntheticDataSeederAgendaCoverageTest` — asseriscono dati/utenti/PDF specifici
  dell'installazione gestionale_commesse (es. `info@gsbuildingservice.it`, "Furgone Gerardo",
  fatture con numeri fissi); i seeder equivalenti esistono in `database/seeders/` di questo
  modulo (`ImpiantiElettricIDemoSeeder`, `BoutiqueIdraulicaBootstrapSeeder`,
  `DeLucaBootstrapSeeder`, `SyntheticDataSeeder`) ma senza test dedicati per ora — il volume di
  riscrittura necessario (assunzioni WorkOrder/Intervention/Inventory specifiche) supera il
  beneficio rispetto alla copertura già garantita da model/VO/audit test.
- `OperationalLookupsSeederTest` — asserisce conteggi di tabelle lookup (`work_order_statuses`,
  `contact_roles`, ecc.) di moduli non ancora verificati 1:1 in questo porting; da rivalutare
  quando quei moduli saranno stabilizzati.
- `PlatformSettingEditPageTest` — richiede `filament()->setCurrentPanel('admin')` e
  `ShieldPermissionsSeeder`; il `PlatformSettingResource` di questo modulo dipende da
  `App\Support\AccountFeatures::isAvailableInCurrentPanel()` la cui configurazione pannelli
  reali va verificata con un test Filament dedicato in una sessione successiva.

### Bug di infrastruttura test rilevato (non introdotto da questa sessione)

`Pest\Laravel\actingAs()` risolve talvolta allo stub `Modules/Xot/tests/PestStubs.php`
(`throw new RuntimeException('Stub: ...')`) invece della funzione reale del plugin
`pestphp/pest-plugin-laravel`, perché `laravel/tests/Pest.php` esegue
`require_once` di **tutti** i `Modules/*/tests/Pest.php` (compreso quello di `Xot`, che
inietta lo stub) indipendentemente dal modulo sotto test. Workaround adottato in
`AuditLogTest.php`: `test()->actingAs($user)` (metodo Illuminate, non affetto). Da
segnalare/risolvere a livello di `laravel/tests/Pest.php` o `Modules/Xot/tests/PestStubs.php`
in una sessione dedicata all'infrastruttura di test, non specifica di `Platform`.

### Verifica esecuzione

```
cd laravel && ./vendor/bin/pest Modules/Platform/tests --no-coverage
Tests: 27 passed (67 assertions)
```
