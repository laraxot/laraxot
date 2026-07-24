---
title: "Import status — Billing"
type: concept
tags: [import, gestionale-commesse, billing]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Billing normalize"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
---

# Import status — Billing

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape) — **redo 2026-07-23**: `Domain/{Actions,DTO}` → `app/{Actions,Datas}`
- [x] Namespace `Modules\Billing\`
- [x] `BillingServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] Action con trait `Spatie\QueueableAction\QueueableAction` (8/8)
- [x] Migrazioni consolidate 1-per-modello (7 modelli, `XotBaseMigration`, `tableCreate`/`tableUpdate`/`updateTimestamps`);
      rimossa `add_einvoice_tracking_to_invoices_table` (merged in `create_invoices_table`)
- [x] Resources → XotBaseResource (4/4: Invoice, DeliveryNote, SupplierInvoice, Supplier) —
      `form()` → `getFormSchema(): array`
- [x] `lang/{it,en}/{billing,suppliers}.php` (creati, non esistevano)
- [x] `tests/` (TestCase + Pest.php + 2 Unit test per le Action ex-Domain)
- [ ] Resources\*\Pages (List/Create/Edit/View) e RelationManager restano `extends` diretto Filament
      (`ListRecords`/`CreateRecord`/`EditRecord`/`ViewRecord`/`RelationManager`) invece di `XotBase*` —
      **non convertiti in questa sessione**: la conversione a `XotBaseViewRecord` richiede implementare
      `getInfolistSchema(): array` per ciascuna Resource (nessuna la definisce oggi), una feature nuova di
      UX non nel perimetro "redo reshape struttura", rischio di regressione senza design esplicito.
      Prossimo step tracciato: wave dedicata per Pages/RelationManagers.
- [ ] PHPStan: 62 errori residui dopo il reshape, quasi tutti pre-esistenti e non introdotti da questa sessione:
      accesso a proprieta' Eloquent dinamiche (`property.notFound`, pattern diffuso in tutto il repo per i model
      con `$guarded = []` senza `@property` docblock — debito codebase-wide, non specifico di Billing) e
      `Barryvdh\DomPDF\Facade\Pdf` non installato (`barryvdh/laravel-dompdf` mancante da vendor, aggiunto ora in
      `composer.json.require` per documentare la dipendenza reale ma non installato in questa sessione per non
      toccare il vendor condiviso multi-agente). Fix applicati invece: `preg_replace` → `Illuminate\Support\Str`
      in `ReconcileSupplierInvoiceAction`, riferimenti Blade/PHPDoc a `Domain\DTO\*` aggiornati a `Datas\*`.
