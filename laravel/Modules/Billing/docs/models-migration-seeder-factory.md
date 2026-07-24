# Billing — Stato Modelli ↔ Migration ↔ Seeder ↔ Factory

Nota di allineamento generata in Ponytail Mode (YAGNI). Ogni modello concreto di
dominio deve avere migration + seeder + factory. Le migration erano già tutte
presenti; sono stati aggiunti factory e seeder mancanti.

Factory resolver: i modelli estendono `Modules\Xot\Models\XotBaseModel` che usa
`HasXotFactory`. La factory viene risolta per convenzione da
`Modules\Xot\Actions\Factory\GetFactoryAction` come
`Modules\Billing\Database\Factories\<Model>Factory`. Nessuna modifica ai modelli
è stata necessaria.

## Matrice di copertura

| Modello | Tabella | Migration | Factory | Seeder | Note |
|---|---|---|---|---|---|
| Supplier | suppliers | esistente | **creata** | **creato** | Root di dominio (ciclo passivo) |
| DeliveryNote | delivery_notes | esistente | **creata** | **creato** | DDT fornitore |
| DeliveryNoteLine | delivery_note_lines | esistente | **creata** | **creato** | Riga documento (come QuotationItem) |
| Invoice | invoices | esistente | **creata** | **creato** | Fattura attiva |
| SupplierInvoice | supplier_invoices | esistente | **creata** | **creato** | Fattura passiva |
| SupplierInvoiceLine | supplier_invoice_lines | esistente | **creata** | **creato** | Riga fattura passiva |
| InvoiceWorkOrderLink | invoice_work_order_links | esistente | *skip* | *skip* | Vedi sotto |

## Skip motivati

- **InvoiceWorkOrderLink** — tabella di giunzione pura (`invoice_id` +
  `work_order_id`, nessun payload di dominio). È un pivot/junction tra Invoice e
  WorkOrder: secondo le regole (SALTA modelli pivot) non riceve factory/seeder
  dedicati. La relazione va popolata tramite gli aggregati (Invoice) quando serve,
  evitando boilerplate inutile (YAGNI).

## File creati

Factory (`database/factories/`):
- SupplierFactory.php
- DeliveryNoteFactory.php
- DeliveryNoteLineFactory.php
- InvoiceFactory.php
- SupplierInvoiceFactory.php
- SupplierInvoiceLineFactory.php

Seeder (`database/seeders/`):
- SupplierSeeder.php
- DeliveryNoteSeeder.php
- DeliveryNoteLineSeeder.php
- InvoiceSeeder.php
- SupplierInvoiceSeeder.php
- SupplierInvoiceLineSeeder.php
- BillingDatabaseSeeder.php (orchestratore, chiama i seeder in ordine di dipendenza)

## Convenzioni applicate

- Seeder idempotenti: `if (Model::query()->exists()) { return; }` prima del create.
- Factory `definition()` con campi realistici basati su `$fillable`/casts.
- FK verso altri moduli via factory (`Customer::factory()`), FK interne via factory
  del modello Billing correlato.
- Migration non toccate (forward-only, `updateTimestamps` in `tableUpdate`).
