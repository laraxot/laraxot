---
title: "Migrazione test SRC gestionale_commesse → Modules/*/tests"
type: how-to
tags: [pest, testing, gestionale-commesse, migration, bmad]
created: 2026-07-24
updated: 2026-07-24
qmd: "migrate tests gestionale Feature Unit Modules Pest XotBase no RefreshDatabase"
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
