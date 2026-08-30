---
title: "Import status — Customer"
type: concept
tags: [import, customer, gestionale-commesse, phpstan, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Customer phpstan L10 enabled migrate panel"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/copy-then-normalize.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Customer

**Perché esiste:** anagrafica clienti / sedi / referenti / rivenditori — radice del grafo field-service.

- [x] Rename SRC `Customers` → `Customer`
- [x] Layout `app/` + Models + XotBase SP
- [x] Namespace `Modules\Customer\`
- [x] composer PSR-4 → `app/`
- [x] Seeders via `*Factory::new()`
- [x] **PHPStan L10** — 0 errori reali
- [x] `modules_statuses=true`
- [x] Migrazioni path applicate (`customers`, `contacts` bigint, …)
- [x] Panel Filament `customer/admin` (AdminPanelProvider in composer + module.json)
- [x] Test reali migrati da SRC `gestionale_commesse/tests/Feature/Customers/*`

## Migrazione test (2026-07-24)

**10/11 file SRC migrati, 33 test passanti, 0 errori PHPStan L10.**

| Test file (destinazione) | Origine SRC | Esito |
|---|---|---|
| `ModelsTest.php` | — (già presente) | 5 pass |
| `ServiceProviderTest.php` | — (già presente) | 1 pass |
| `CustomerActionsTest.php` | `CreateCustomerTest`, `UpdateCustomerTest`, `DeleteCustomerTest`, `CreateCustomerSiteTest`, `DeleteCustomerSiteTest`, `RestoreCustomerCascadeTest`, `RestoreCustomerCascadeNoopTest`, `UpsertCustomerContactByEmailActionMutationTest` (consolidati) | 11 pass |
| `CustomerResourceTest.php` | `CustomerResourceTest` | 1 pass |
| `SearchCompanyOnlineActionTest.php` | `SearchCompanyOnlineTest` | 3 pass |
| `CompanyLookupSourcesTest.php` | `CompanyLookupSourcesTest` | 12 pass |

Namespace adattati: `Modules\Customers\Application\{Actions,Lookup}` → `Modules\Customer\{Actions,Lookup}`,
`Modules\Customers\Infrastructure\Models` → `Modules\Customer\Models`. `WebAiCompanyLookupProvider` vive in
`Modules\AiAssistant\Lookup` (non più `Application\Lookup`).

**Non migrato:** `CompanyLookupProductionFlowTest.php` — integration test cross-modulo (Customer + Quotation
`RecordQuotationVoicePage` + AiAssistant) che dipende da fixture Catalog/Quotation (`products`, `roles`) non
migrate nell'ambiente di test condiviso. Verificato che anche il test equivalente già presente in
`Modules/Quotation/tests/Feature/RecordQuotationVoicePageTest.php` fallisce oggi per lo stesso motivo
(`no such table: products` / `no such table: roles`) — non è una regressione introdotta qui, è un gap
di ambiente di test pre-esistente e trasversale a più moduli, fuori scope per Customer.

**Bug reali trovati nel codice Customer:** nessuno. Le Action (`CreateCustomerAction`, `UpdateCustomerAction`,
`SoftDeleteCustomerAction`, `SoftDeleteCustomerSiteAction`, `RestoreCustomerCascadeAction`,
`UpsertCustomerContactByEmailAction`, `SearchCompanyOnlineAction`) e i provider Lookup (`Vies`, `OpenApi`,
`OpenCorporates`, `Composite`) hanno comportamento equivalente all'originale SRC; solo i namespace test
richiedevano adattamento.

**Fix infrastrutturale di test (non un bug Customer, ma bloccava l'esecuzione):** `Modules/Customer/tests/TestCase.php`
usava sqlite `:memory:` (default PHPUnit) mai migrato → `no such table: customers`. Allineato al pattern
già in uso in `Modules/Rating/tests/TestCase.php`: `prepareSharedFixcitySqliteForTesting()` in `setUp()`
punta le connessioni sqlite al file condiviso `database/fixcity_data.sqlite` (già migrato con le tabelle
Customer). Rigenerato anche il bridge PHPStan/Pest (`bashscripts/tools/generate-pest-phpstan-bridge.php`)
per includere i nuovi namespace dei test e azzerare gli errori `method.internalClass` su `expect()`.

**Aperto:** `CompanyLookupProductionFlowTest.php` da re-includere quando l'ambiente di test avrà le
migrazioni Catalog/Quotation applicate al sqlite condiviso (non è un compito di questo modulo).

SSoT readiness: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).

## Delta seconda tranche (2026-08-30)

Sostanza: `CustomerResource` e `ResellerResource` → `GatedXotBaseResource`.
Suite 46/46 verdi. (Il rename tabella `contacts`→`customer_contacts` era già
stato applicato nel ciclo collisioni, vedi issue #14.)
