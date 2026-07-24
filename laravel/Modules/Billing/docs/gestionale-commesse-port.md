---
title: "Porting gestionale_commesse — Billing (test migration)"
type: concept
tags: [import, gestionale-commesse, billing, tests, pest]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting Billing gestionale_commesse test migration pest"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./import-status.md"
  - "./README.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../laravel/docs/gestionale-commesse-technical-debt-resolution-guide.md"
---

# Porting gestionale_commesse — Billing (test migration, 2026-07-24)

Sorgente: `/var/www/_bases/gestionale_commesse/tests/{Feature,Unit}/Billing/` (8 Feature + 1 Unit).
Adattati a namespace/model/Action reali di questo repo (`Modules\Billing\Models\*`, `Modules\Billing\Actions\*`,
`Modules\Billing\Datas\*` invece di `Domain\DTO`/`Infrastructure\Models`/`Application\Actions` del sorgente).

## Test migrati dal sorgente

| Sorgente | Destinazione | Esito |
|---|---|---|
| `Unit/Billing/ReconcileSupplierInvoiceActionTest.php` | `tests/Unit/ReconcileSupplierInvoiceActionTest.php` (già presente, sessione precedente) | ✅ pass |
| `Feature/Billing/BuildWorkOrderPreInvoiceTest.php` (implicito via `BuildPreInvoiceDraftAction`) | `tests/Unit/BuildPreInvoiceDraftActionTest.php` (già presente, sessione precedente) | ✅ pass |
| `Feature/Billing/ReconcileSupplierInvoiceWithDeliveryNotesTest.php` | `tests/Feature/ReconcileSupplierInvoiceWithDeliveryNotesTest.php` | ⏭️ skip (debito #12) |
| `Feature/Billing/InvoiceWorkOrderLinkingTest.php` | `tests/Feature/InvoiceWorkOrderLinkingTest.php` | ⏭️ skip (debito #12) |
| `Feature/Billing/InvoicesTest.php` | `tests/Feature/InvoicesTest.php` | ⏭️ skip (debito #12) |
| `Feature/Billing/SendElectronicInvoiceActionTest.php` | `tests/Feature/SendElectronicInvoiceActionTest.php` (8 casi) | ⏭️ skip (debito #12) |
| `Feature/Billing/BillingModalViewsRenderTest.php` | `tests/Feature/BillingModalViewsRenderTest.php` (4 casi) | 1 pass, 3 skip (debito #12 / translator) |
| `Feature/Billing/GenerateInvoicePdfTest.php` | `tests/Feature/GenerateInvoicePdfTest.php` (2 casi) | ⏭️ skip (DomPDF non installato) |
| `Feature/Billing/SupplierBillingResourcesTest.php` | non portato in questa sessione | vedi nota sotto |
| `Feature/Billing/BuildWorkOrderPreInvoiceTest.php` (scenario integrazione WorkOrder/Intervention/Quotation) | non portato in questa sessione | vedi nota sotto |

## Perché la maggior parte dei Feature test sono skippati, non cancellati

Verificato (2026-07-24): **`laravel/phpunit.xml` forza `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:`** come variabili
d'ambiente di processo, che vincono su `.env.testing` (mysql `techplanner_data_test`) per l'"immutable load" di Dotenv.
Ogni modello con connessione `xot` di default (praticamente tutti i model `XotBaseModel` del modulo, inclusi `Invoice`,
`Supplier`, `SupplierInvoice`, `DeliveryNote`) finisce quindi su uno sqlite `:memory:` **mai migrato**: qualunque test
Feature che scriva su queste tabelle fallisce con `no such table: ...`, indipendentemente dalla correttezza del test
o del codice applicativo. Debito già documentato: **[gestionale-commesse-technical-debt-resolution-guide.md §12](../../../../laravel/docs/gestionale-commesse-technical-debt-resolution-guide.md#12-debito-critico--phpunitxml-forza-sqlite-memory-e-scavalca-envtesting-mysql)**.

I test sono stati portati per intero (stessa logica, stesse asserzioni del sorgente adattate ai namespace reali) e
marcati `->skip('DEBITO CRITICO #12 ...')` invece di essere cancellati o "aggiustati" con mock che nascondano il
problema: una volta risolto il debito #12 (rimuovere `DB_CONNECTION`/`DB_DATABASE` da `phpunit.xml` + migrare le
tabelle contro `techplanner_data_test`), rimuovere gli `->skip(...)` dovrebbe far passare i test così come sono.

Un secondo caso di skip più specifico: in `BillingModalViewsRenderTest`, il primo test (verifica interpolazione
`:amount` nella label "extra preventivo da decidere") fallisce anche a prescindere dal DB, perché
`Modules\Xot\Tests\XotBaseTestCase::setUp()` registra un `Translator` con `ArrayLoader` vuoto quando il binding
`translator` non è già presente: le stringhe `lang/{it,en}/billing.php` reali del modulo non vengono caricate in
test, quindi `__('billing.pre_invoice.undecided_body', ['amount' => ...])` restituisce la chiave letterale senza
interpolazione. Corretto comunque il placeholder mancante `:amount` in `lang/it/billing.php` e `lang/en/billing.php`
(bug reale: la vista lo passava, la stringa lang non lo consumava), ma il test resta skippato finché il translator
di test non carica i lang file reali dei moduli.

## Non portati in questa sessione

- **`SupplierBillingResourcesTest.php`**: test Filament panel (liste/edit `SupplierResource`, `DeliveryNoteResource`,
  `SupplierInvoiceResource` + `ShieldPermissionsSeeder`). Stesso blocco DB del debito #12, aggiunto solo dopo aver
  verificato che i metodi statici usati dal sorgente (`SupplierInvoiceResource::reconcile()`,
  `SupplierInvoiceResource::statusColor()`) esistono già identici nel Resource portato: nessuna logica applicativa
  mancante, solo infra di test bloccata. Da riprendere insieme al resto del debito #12.
- **`BuildWorkOrderPreInvoiceTest.php`** (scenario completo con `Intervention`, `Quotation`, `WorkOrder`): i moduli
  `WorkOrder`, `Intervention`, `Quotation` risultavano **lockati da altri agenti** durante questa sessione
  (`bashscripts/lock/check.sh` → `LOCKED`). `BuildWorkOrderPreInvoiceAction` stesso (già portato, non modificato in
  questa sessione) dipende da `Modules\Intervention\Models\{Intervention,InterventionMaterialLine}` e
  `Modules\WorkOrder\Models\WorkOrder`. Non toccato per rispettare il protocollo di coordinamento multi-agente
  (nessuna scrittura né lettura invasiva su moduli lockati); da riprendere quando quei lock si liberano.

## Comando di verifica

```bash
cd laravel && ./vendor/bin/pest Modules/Billing/tests --no-coverage
```

Esito (2026-07-24): 6 pass, 22 skip (tra cui i test già esistenti prima di questa sessione), 0 fail.
