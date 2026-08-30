---
title: "Import status — Billing"
type: concept
tags: [import, gestionale-commesse, billing, phpstan]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Billing normalize phpstan enabled"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Billing

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape)
- [x] Namespace `Modules\Billing\`
- [x] `BillingServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] QueueableAction
- [x] Migrazioni 1-per-modello
- [x] Resources → XotBaseResource (`getFormSchema`)
- [x] **PHPStan L10** — 0 errori reali (2026-07-24)
- [x] `modules_statuses=true`
- [ ] Pages/RelationManagers → XotBase* (wave UX; oggi alcuni `extends` Filament diretto)
- [ ] DomPDF vendor: `composer require barryvdh/laravel-dompdf` (umano)

> Soft-dep PDF: non blocca PHPStan se tipizzato/soft; install solo con ordine.

## Delta seconda tranche (2026-08-30) — controllo di gestione

Portato il delta `15f07e80..295d281^`: le tre domande che un titolare fa sui soldi —
chi mi deve pagare, avrò abbastanza per pagare, quali commesse guadagnano davvero.

- **Migrazioni**: `invoices` e `supplier_invoices` guadagnano `due_on`, `paid_on`,
  `paid_amount` (bump della `create_` con timestamp nuovo + guard `hasColumn`).
  Entrambe le tabelle ricevono le stesse tre colonne così crediti e debiti si
  invecchiano con le stesse regole. Bumpate a cascata anche le dipendenti
  (`supplier_invoice_lines`, `invoice_work_order_links`,
  `public_procurement_payment_certificates`) per non rompere l'ordine delle FK.
- **Datas**: `AgingBucket`, `AgingBuckets`, `CashFlowEntry`, `CashFlowProjection`,
  `JobMargin`, `JobMarginReport`, `OpenItem`, `ReceivablesAging` (readonly, `Money`
  del modulo Quotation). I costi restano separati per famiglia — manodopera,
  materiali, terzisti — perché quando una commessa va male i tre si comportano in
  modo diverso e un totale unico nasconderebbe quale dei tre è successo.
- **Actions**: `BuildReceivablesAgingAction`, `CalculateJobMarginAction`,
  `ProjectCashFlowAction`. **Queries**: `GetReceivablesAgingQuery`,
  `GetCashFlowProjectionQuery`, `GetJobMarginsQuery`.
- **Filament**: `FinancialControlPage` (gated, feature `admin.financial_control`)
  + blade; `InvoiceResource`, `SupplierInvoiceResource`, `SupplierResource`,
  `DeliveryNoteResource` passano a `GatedXotBaseResource`.
- **Lang** it/en: `management_accounting`.
- **Test**: +3 file dal sorgente (48 test) tutti verdi.

PHPStan: **zero errori sul codice di produzione** (erano 62 di debito storico).

### Debiti chiusi in questo ciclo

1. **TestCase senza fixture condivisa**: mancava
   `prepareSharedFixcitySqliteForTesting()` → i test giravano su un sqlite senza le
   tabelle (`no such table: customers/suppliers/permissions`). **30 test rossi → 0.**
2. **22 chiamate a `skip('...')`**, funzione che in Pest non esiste: erano marcatori
   di un debito ("phpunit.xml forza sqlite :memory: senza tabelle") che il punto 1
   ha superato, ma facevano **fallire** i test invece di skipparli. Rimosse: i test
   sottostanti passano davvero.
3. **Test PDF svuotati** perché dompdf mancava: dompdf **c'è**, ripristinati dal
   sorgente e verdi (generano PDF reali, `%PDF` + peso > 1 KB).
4. **Chiavi lang senza namespace** (149 riferimenti): stesso difetto già visto in
   WorkOrder. Sintomo concreto: la modale pre-fattura stampava
   `billing.pre_invoice.undecided_body` invece del testo, quindi l'importo `:amount`
   degli extra non decisi **non veniva mai mostrato** — proprio il numero che il
   messaggio esiste per far vedere.
5. **`DB::table('users')->insertGetId()`** (users.id è UUID varchar) → `UserFactory`.
6. **Assertion vs escaping Blade**: i test confrontavano `Quantita' eccedente` mentre
   Blade rende `Quantita&#039; eccedente`. Ora passano da `e()`.

### Difetto della base corretto: policy che esplodono su permesso mancante

`VehiclePolicy` usava `hasPermissionTo()`, che in spatie **lancia**
`PermissionDoesNotExist` quando il permesso non è ancora seedato. Bastava quello per
mandare in 500 l'intera sidebar Filament (una resource nel panel → una policy →
eccezione → render fallito). Aggiunto `allows()` in `VehicleBasePolicy` che cattura
e risponde `false`: una policy deve dire "no", non esplodere.

**Il pattern è diffuso**: decine di policy in Cms, Activity, Gdpr, Geo, Lang, Job…
usano `hasPermissionTo()` allo stesso modo. La correzione generale richiede di
esporre `checkPermissionTo()` su `UserContract` (modulo Xot) e poi uno sweep:
è un ciclo di hardening a sé, registrato come debito.
