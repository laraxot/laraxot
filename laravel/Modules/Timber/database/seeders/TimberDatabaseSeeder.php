<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orchestratore dei seeder del modulo Timber.
 *
 * Richiama un seeder per ogni modello concreto (anagrafiche, listini, magazzino,
 * documenti, fatture). L'ordine rispetta le dipendenze fra tabelle: prima le
 * entita' padre (fornitori, clienti, prodotti), poi quelle figlie (righe, movimenti).
 * Ogni seeder e' idempotente: salta se la tabella contiene gia' record.
 */
final class TimberDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Anagrafiche di base.
            TimberSupplierSeeder::class,
            TimberTransporterSeeder::class,
            TimberAgentSeeder::class,
            TimberAgentCommissionSeeder::class,
            TimberCustomerSeeder::class,
            TimberContactNoteSeeder::class,

            // Catalogo e listini.
            TimberWoodSpeciesSeeder::class,
            TimberProductSeeder::class,
            TimberPriceListSeeder::class,
            TimberPriceListItemSeeder::class,
            TimberPriceListVersionSeeder::class,
            TimberCustomerPriceSeeder::class,

            // Acquisti, lotti e lavorazione.
            TimberPurchaseRecordSeeder::class,
            TimberLotSeeder::class,
            TimberMovementSeeder::class,
            TimberProcessingStepSeeder::class,
            TimberProcessingStepProcessableSeeder::class,
            TimberDerivedMaterialSeeder::class,
            TimberDryingCycleSeeder::class,
            TimberHumidityCheckSeeder::class,
            TimberPackagedUnitSeeder::class,
            TimberLabelPrintSeeder::class,
            TimberStockMovementSeeder::class,

            // Documenti, fatture e incassi.
            TimberDocumentSeeder::class,
            TimberDocumentLineSeeder::class,
            TimberInvoiceSeeder::class,
            TimberInvoiceLineSeeder::class,
            TimberPaymentInstallmentSeeder::class,
            TimberEInvoiceSeeder::class,
        ]);
    }
}
