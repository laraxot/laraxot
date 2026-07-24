---
title: "Migrazione test SRC gestionale_commesse → Modules/*/tests"
type: how-to
tags: [pest, testing, gestionale-commesse, migration, bmad]
created: 2026-07-24
updated: 2026-07-24
qmd: "migrate tests gestionale Feature Unit Modules Pest XotBase no RefreshDatabase Catalog HR metel"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-pest-field-service-coverage.md"
  - "./pest_testing_setup.md"
---

# Migrazione test SRC → `Modules/{Singolare}/tests`

## Perché

In `/var/www/_bases/gestionale_commesse/tests` i test sono organizzati per dominio (`Feature/Customers`, `Feature/WorkOrders`, …).  
In Laraxot ogni modulo è un package: i test vivono in `laravel/Modules/{Singolare}/tests/{Feature,Unit}/`.

Obiettivo: **portare la BL testata**, non copiare ciechi file con namespace plurale / `RefreshDatabase` / `App\Models\User`.

## Schema sqlite di test (obbligatorio per Feature migrati)

I Feature usano `prepareSharedFixcitySqliteForTesting()` → file `database/fixcity_data.sqlite` (non `:memory:` vuoto).

**Pattern TestCase (Customer / WorkOrder / Intervention / Catalog / HR):**

1. `prepareSharedFixcitySqliteForTesting()` **prima** di `parent::setUp()`
2. Eventuale `ensure*Schema()` (Artisan migrate `--path=Modules/X/database/migrations`, **no `--force`**) **prima** di `parent::setUp()` — altrimenti DDL resta nella transazione `DatabaseTransactions` e sparisce al rollback
3. `$connectionsToTransact = ['sqlite', 'xot', …]`
4. **Mai** `RefreshDatabase`

Dopo nuove migrazioni wave, allineare lo schema sqlite (CLI o ensure* nel TestCase):

```bash
cd laravel
php artisan migrate --database=sqlite --path=Modules/Customer/database/migrations --no-interaction
php artisan migrate --database=sqlite --path=Modules/WorkOrder/database/migrations --no-interaction
php artisan migrate --database=sqlite --path=Modules/Intervention/database/migrations --no-interaction
php artisan migrate --database=sqlite --path=Modules/Catalog/database/migrations --no-interaction
php artisan migrate --database=sqlite --path=Modules/HR/database/migrations --no-interaction
# … Quotation Signature Vehicle AiAssistant
```

Senza questo passo: `no such table: work_orders` (e simili) su connection `xot` / `:memory:`.

### Catalog (evidenza 2026-07-24)

- `config('metel.*')` richiede `mergeConfigFrom(..., 'metel')` nello SP: XotBase registra solo `catalog.metel.*`
- `fixed_layout` / `csv_map` devono essere popolati (non stub `[]`) — altrimenti Metel parser TypeError
- Pest: **11 passed** (`CatalogCrudTest` + `MetelCatalogTest`)

### HR (evidenza 2026-07-24)

- Stub `users` (id string 36) prima della migrate HR: FK `employees.user_id` → `users`
- Traduzioni Filament: chiavi `hr::hr.*` (non `hr.*`)
- Skip documentati: media Document (**D1**), User factory + Spatie roles (**D6**)
- Pest: **3 passed** + **5 skipped** (EmployeeCrud + type normalization)

### Gap ancora aperti (conteggio SRC vs DEST file)

| Dominio SRC | DEST | Note |
|---|---|---|
| Timber (32), Wts (20), Platform (21), Operator, Public, Localization | assenti / parziali | defer o story dedicata |
| Documents (4) | smoke Media | **non** portare Feature finché D1 |
| Compliance / Inventory / EnergyBroker | smoke | Feature UI SRC ancora da triage |

## Mapping cartelle SRC → modulo DEST

| SRC `tests/Feature|Unit/` | Modulo Laraxot |
|---|---|
| `Customers` | `Customer` |
| `WorkOrders` | `WorkOrder` |
| `Interventions` | `Intervention` |
| `Quotations` | `Quotation` |
| `Signatures` | `Signature` |
| `Vehicles` | `Vehicle` |
| `AiAssistant` | `AiAssistant` |
| `Documents` | `Document` |
| `Catalog` | `Catalog` |
| `Billing` | `Billing` |
| `Auth` | `Auth` |
| `HR` | `HR` |
| `Inventory` | `Inventory` |
| `Compliance` / `Platform` / `Timber*` / `Wts` / … | Modulo omonimo se replica esiste; altrimenti **defer** |

`Feature/Operator`, `Feature/Authorization`, `Feature/Public`, `Feature/Seeders`, `Feature/Localization` → non sono un modulo: distribuire o story dedicata.

## Trasformazioni obbligatorie (per ogni file)

1. **Namespace import**
   - `Modules\{Plural}\Application\Actions\` → `Modules\{Singular}\Actions\`
   - `Modules\{Plural}\Infrastructure\Models\` → `Modules\{Singular}\Models\`
   - `Modules\{Plural}\Filament\` → `Modules\{Singular}\Filament\`
   - `Modules\{Plural}\` → `Modules\{Singular}\`
2. **User**
   - `App\Models\User` → `Modules\User\Models\User` (o factory via `XotData` se già in uso nel modulo)
3. **Bootstrap Pest**
   - Ogni file: `uses(\Modules\{Singular}\Tests\TestCase::class);`
   - `tests/Pest.php` del modulo: **niente** `uses()->in()` (PHPStan); niente `RefreshDatabase`
4. **TestCase modulo**
   - Estende `XotBaseTestCase`
   - Preferire `DatabaseTransactions` (già in Customer)
5. **Filename** PascalCase `*Test.php`; cartelle `Feature`/`Unit`
6. **Non sovrascrivere** smoke già utili (`ModelsTest`, `ServiceProviderTest`) senza merge

## Ordine di migrazione (wave)

1. Customer (Actions CRUD pure — basso accoppiamento)
2. WorkOrder
3. Intervention (più dipendenze Platform/Filament → skip mirati)
4. Quotation
5. Signature / Vehicle / AiAssistant
6. Catalog / Billing
7. Auth / HR / Document (dopo decisioni D1/D5)

## Cosa saltare o marcare `skip` subito

| Sintomo | Azione |
|---|---|
| Dipende da `Modules\Platform\...` non portato | `->skip('Platform non in wave')` o file in `_deferred/` documentato |
| Dipende da Timber/Wts/EnergyBroker | defer |
| Filament panel name SRC `admin` vs `{alias}/admin` | adattare `filament()->setCurrentPanel('...')` al panel Laraxot |
| Export PDF senza DomPDF | skip finché D7 |
| Document Media / Spatie | skip finché D1 |

## Comandi verifica

```bash
cd laravel
vendor/bin/pest Modules/Customer/tests --compact
vendor/bin/pest Modules/WorkOrder/tests --compact
# mai RefreshDatabase su DB condiviso
```

## DoD per un modulo

- [ ] File SRC Feature+Unit del dominio copiati/adattati o elencati come deferred in docs modulo
- [ ] Namespace plurale = 0 (`rg 'Modules\\\\Customers\\\\' Modules/Customer/tests`)
- [ ] Nessun `RefreshDatabase` nei test del modulo
- [ ] Pest green o skip documentati con motivo
- [ ] `Modules/{X}/docs/import-status.md` + `tests-migration.md` aggiornati
- [ ] Story BMAD 2.5 aggiornata

## Anti-pattern

- Copiare `gestionale_commesse/tests/Pest.php` con `RefreshDatabase` nel root laravel
- Lasciare test in `laravel/tests/Feature/Customers` (fuori modulo)
- Merge Auth test dentro User “perché overlap”
