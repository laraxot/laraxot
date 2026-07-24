# TimberBilling — Stato Modelli ↔ Migration ↔ Seeder ↔ Factory

> Aggiornato: 2026-07-24 — Ponytail Mode. Copertura completa: ogni modello concreto ha migration, seeder e factory.

## Sintesi numerica

| Elemento              | Prima | Dopo |
|-----------------------|-------|------|
| Modelli concreti      | 28    | 28   |
| Migration (file)      | 17    | 17   |
| Tabelle create        | 28*   | 28*  |
| Seeder per-modello    | 0     | 28   |
| Factory               | 23    | 28   |

\* più 1 tabella pivot (`timber_processing_step_processable`) priva di modello dedicato.

## Perché migration (17) < modelli (28)

Il conteggio dei **file** di migration non coincide con quello dei modelli perché:

- **3 migration creano più tabelle** ciascuna:
  - `2026_07_03_100000_create_timber_tables.php` → batch iniziale (lotti, movimenti, fornitori, trasportatori, essiccazione, controlli umidità, lavorazioni, confezioni, materiali derivati, etichette, acquisti).
  - `2026_07_07_100300_create_timber_documents_tables.php` → `timber_documents` + `timber_document_lines`.
  - `2026_07_10_100000_create_timber_price_lists_tables.php` → `timber_price_lists` + `timber_price_list_items` + `timber_customer_prices` + `timber_price_list_versions`.
- **4 migration sono ALTER** (non creano tabelle nuove), quindi forward-only additive:
  - `2026_07_09_100000_add_purchase_price_unit_to_timber_purchase_records.php`
  - `2026_07_09_100200_add_traceability_to_timber_derived_materials.php`
  - `2026_07_09_100300_drop_unique_label_code_on_timber_label_prints.php`
  - `2026_07_09_100400_add_article_to_timber_packaged_units.php`

**Verifica:** tutte le 28 tabelle dei modelli risultano coperte da una `Schema::create`/`tableCreate`. **Nessuna migration mancante** → non è stata creata alcuna migration.

## Skip motivati

- **Nessun modello astratto/trait/enum/contract** in `app/Models/`: tutte le 28 classi sono `final class ... extends Model|XotBaseModel` con tabella propria → nessuno skip lato modelli.
- **Pivot `timber_processing_step_processable`**: relazione many-to-many `TimberProcessingStep` ↔ processabili (lotti/materiali). Gestita via `belongsToMany`, priva di modello Eloquent dedicato → **nessun seeder/factory dedicato** (popolata implicitamente dalle lavorazioni).

## Factory create (5)

Modelli che prima erano privi di factory; aggiunta la factory + trait `HasFactory` + `newFactory()` sul modello:

| Modello                  | Factory                          | Note dipendenze |
|--------------------------|----------------------------------|-----------------|
| `TimberAgentCommission`  | `TimberAgentCommissionFactory`   | `agent_id` → `TimberAgent::factory()` |
| `TimberContactNote`      | `TimberContactNoteFactory`       | morph `noteable` → `TimberCustomer::factory()` |
| `TimberCustomerPrice`    | `TimberCustomerPriceFactory`     | `customer_id`, `product_id` |
| `TimberPriceListItem`    | `TimberPriceListItemFactory`     | `price_list_id`, `product_id` |
| `TimberPriceListVersion` | `TimberPriceListVersionFactory`  | `price_list_id`; `items` json |

Le altre 23 factory erano già presenti e sono state riusate.

## Seeder creati (28)

Un seeder per modello concreto, namespace `Modules\TimberBilling\Database\Seeders`, pattern del modulo `WorkOrder`:
idempotente (`if (Model::query()->exists()) return;`) + `Model::factory()->count(3)->create()`.
Le factory risolvono da sé le entità padre, quindi ciascun seeder è eseguibile in autonomia.

Orchestrati da `TimberBillingDatabaseSeeder` in ordine dipendenza-consapevole (anagrafiche → catalogo/listini → acquisti/lavorazione → documenti/fatture).

`TimberDemoSeeder` resta invariato: è un seeder dimostrativo action-based (dati coerenti end-to-end) invocabile separatamente.

## Tabella completa Modello ↔ Migration ↔ Seeder ↔ Factory

| Modello | Tabella | Migration | Seeder | Factory |
|---------|---------|-----------|--------|---------|
| TimberAgent | timber_agents | create_timber_tables | ✅ | ✅ |
| TimberAgentCommission | timber_agent_commissions | create_timber_agent_commissions_table | ✅ | ✅ nuova |
| TimberContactNote | timber_contact_notes | create_timber_contact_notes_table | ✅ | ✅ nuova |
| TimberCustomer | timber_customers | create_timber_customers_table | ✅ | ✅ |
| TimberCustomerPrice | timber_customer_prices | create_timber_price_lists_tables | ✅ | ✅ nuova |
| TimberDerivedMaterial | timber_derived_materials | create_timber_tables (+ALTER) | ✅ | ✅ |
| TimberDocument | timber_documents | create_timber_documents_tables | ✅ | ✅ |
| TimberDocumentLine | timber_document_lines | create_timber_documents_tables | ✅ | ✅ |
| TimberDryingCycle | timber_drying_cycles | create_timber_tables | ✅ | ✅ |
| TimberEInvoice | timber_e_invoices | create_timber_e_invoices_table | ✅ | ✅ |
| TimberHumidityCheck | timber_humidity_checks | create_timber_tables | ✅ | ✅ |
| TimberInvoice | timber_invoices | create_timber_invoices_table | ✅ | ✅ |
| TimberInvoiceLine | timber_invoice_lines | create_timber_invoice_lines_table | ✅ | ✅ |
| TimberLabelPrint | timber_label_prints | create_timber_tables (+ALTER) | ✅ | ✅ |
| TimberLot | timber_lots | create_timber_tables | ✅ | ✅ |
| TimberMovement | timber_movements | create_timber_tables | ✅ | ✅ |
| TimberPackagedUnit | timber_packaged_units | create_timber_tables (+ALTER) | ✅ | ✅ |
| TimberPaymentInstallment | timber_payment_installments | create_timber_payment_installments_table | ✅ | ✅ |
| TimberPriceList | timber_price_lists | create_timber_price_lists_tables | ✅ | ✅ |
| TimberPriceListItem | timber_price_list_items | create_timber_price_lists_tables | ✅ | ✅ nuova |
| TimberPriceListVersion | timber_price_list_versions | create_timber_price_lists_tables | ✅ | ✅ nuova |
| TimberProcessingStep | timber_processing_steps | create_timber_tables | ✅ | ✅ |
| TimberProduct | timber_products | create_timber_products_table | ✅ | ✅ |
| TimberPurchaseRecord | timber_purchase_records | create_timber_tables (+ALTER) | ✅ | ✅ |
| TimberStockMovement | timber_stock_movements | create_timber_stock_movements_table | ✅ | ✅ |
| TimberSupplier | timber_suppliers | create_timber_tables | ✅ | ✅ |
| TimberTransporter | timber_transporters | create_timber_tables | ✅ | ✅ |
| TimberWoodSpecies | timber_wood_species | create_timber_wood_species_table | ✅ | ✅ |

**Copertura: 28/28 modelli con migration + seeder + factory.**
