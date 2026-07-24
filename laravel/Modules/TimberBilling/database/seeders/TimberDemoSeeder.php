<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TimberBilling\Actions\CompleteTimberDryingCycleAction;
use Modules\TimberBilling\Actions\GenerateTimberPackagedUnitsAction;
use Modules\TimberBilling\Actions\MoveTimberMaterialAction;
use Modules\TimberBilling\Actions\PostTimberDocumentStockAction;
use Modules\TimberBilling\Actions\RecordTimberHumidityCheckAction;
use Modules\TimberBilling\Actions\RegisterTimberProcessingAction;
use Modules\TimberBilling\Actions\RegisterTimberPurchaseAction;
use Modules\TimberBilling\Actions\RegisterTimberStockMovementAction;
use Modules\TimberBilling\Actions\StartTimberDryingCycleAction;
use Modules\TimberBilling\Actions\SyncEasyfattCustomersAction;
use Modules\TimberBilling\Actions\SyncEasyfattProductsAction;
use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberPurchaseRecord;
use Modules\TimberBilling\Models\TimberStockMovement;
use Modules\TimberBilling\Models\TimberSupplier;
use Modules\TimberBilling\Models\TimberTransporter;
use RuntimeException;

final class TimberDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('timber_purchase_records')) {
            // @phpstan-ignore isset.property (command is unset, not null, when the seeder runs outside an artisan console context)
            if (isset($this->command)) {
                $this->command->warn('Tabelle Timber non trovate. Esegui prima le migration.');
            }

            return;
        }

        $suppliers = $this->seedSuppliers();
        $transporter = $this->seedTransporters();
        $this->seedAgents();

        $purchaseAction = app(RegisterTimberPurchaseAction::class);
        $moveAction = app(MoveTimberMaterialAction::class);
        $processingAction = app(RegisterTimberProcessingAction::class);
        $startDryingAction = app(StartTimberDryingCycleAction::class);
        $recordHumidityAction = app(RecordTimberHumidityCheckAction::class);
        $completeDryingAction = app(CompleteTimberDryingCycleAction::class);
        $packageAction = app(GenerateTimberPackagedUnitsAction::class);

        // Lotto A: appena scaricato, ancora fermo in piazzale.
        $purchaseAction->execute(
            $suppliers[0],
            $transporter,
            'Bosco del Cansiglio',
            'DDT-2026-00101',
            now()->subDays(3)->toDateString(),
            'abete',
            3200,
            'kg',
            3200,
            3050,
            0.14,
            'FSC',
            null,
            'Carico ricevuto in buone condizioni.',
            null,
        );

        // Lotto B: spostato in zona taglio, lavorato con una resa e uno scarto; il derivato buono va in essiccazione (ciclo aperto).
        $recordB = $purchaseAction->execute(
            $suppliers[1],
            $transporter,
            'Foresta della Lessinia',
            'DDT-2026-00102',
            now()->subDays(20)->toDateString(),
            'faggio',
            2400,
            'kg',
            2400,
            2280,
            0.19,
            'FSC, PEFC',
            null,
            null,
            null,
        );
        $lotB = $this->requireLot($recordB);
        $moveAction->execute($lotB, 'zona_taglio', null, 'Spostato per taglio.');
        $stepB = $processingAction->execute(
            [$lotB->id],
            [],
            'taglio',
            null,
            80,
            'kg',
            'Taglio standard con selezione scarti.',
            [
                ['wood_species' => 'faggio', 'quantity' => 1900, 'unit_of_measure' => 'kg', 'is_waste' => false],
                ['wood_species' => 'faggio', 'quantity' => 300, 'unit_of_measure' => 'kg', 'is_waste' => true],
            ],
        );
        $materialB = $stepB->derivedMaterials->firstWhere('is_waste', false);
        if ($materialB !== null) {
            $dryingCycleB = $startDryingAction->execute($materialB, now()->addDays(30)->toIso8601String(), 18, null);
            $recordHumidityAction->execute($dryingCycleB, 42, null, 'Prima rilevazione dopo carico essiccatoio.');
            $recordHumidityAction->execute($dryingCycleB, 31, null, 'Controllo intermedio.');
        }

        // Lotto C: lavorato, essiccato e completato, poi confezionato e pronto per la vendita.
        $recordC = $purchaseAction->execute(
            $suppliers[0],
            null,
            'Bosco di Paneveggio',
            'DDT-2026-00103',
            now()->subDays(60)->toDateString(),
            'larice',
            1800,
            'kg',
            1800,
            1700,
            0.22,
            'FSC',
            null,
            null,
            null,
        );
        $lotC = $this->requireLot($recordC);
        $moveAction->execute($lotC, 'zona_taglio', null, null);
        $stepC = $processingAction->execute(
            [$lotC->id],
            [],
            'spaccatura',
            null,
            50,
            'kg',
            null,
            [
                ['wood_species' => 'larice', 'quantity' => 1600, 'unit_of_measure' => 'kg', 'is_waste' => false],
                ['wood_species' => 'larice', 'quantity' => 150, 'unit_of_measure' => 'kg', 'is_waste' => true],
            ],
        );
        $materialC = $stepC->derivedMaterials->firstWhere('is_waste', false);
        if ($materialC !== null) {
            $dryingCycleC = $startDryingAction->execute($materialC, now()->subDays(10)->toIso8601String(), 18, null);
            $recordHumidityAction->execute($dryingCycleC, 45, null, null);
            $recordHumidityAction->execute($dryingCycleC, 18, null, 'Essiccazione completata, pronta per confezionamento.');
            $completeDryingAction->execute($dryingCycleC);
            $materialC->refresh();
            $packageAction->execute($materialC, 'bancale', 8, 200, null);
        }

        // Lotto D: legname grezzo essiccato direttamente (senza taglio preventivo), ciclo completato.
        $recordD = $purchaseAction->execute(
            $suppliers[2],
            $transporter,
            'Selva di Sila',
            'DDT-2026-00104',
            now()->subDays(90)->toDateString(),
            'castagno',
            2100,
            'kg',
            2100,
            2000,
            0.17,
            'PEFC',
            null,
            null,
            null,
        );
        $lotD = $this->requireLot($recordD);
        $dryingCycleD = $startDryingAction->execute($lotD, now()->subDays(20)->toIso8601String(), 18, null);
        $recordHumidityAction->execute($dryingCycleD, 40, null, null);
        $recordHumidityAction->execute($dryingCycleD, 17, null, null);
        $completeDryingAction->execute($dryingCycleD);

        // Lotto E: acquistato da tempo e mai movimentato, per popolare l'alert "materiale fermo".
        $recordE = $purchaseAction->execute(
            $suppliers[1],
            null,
            'Bosco del Cansiglio',
            'DDT-2026-00080',
            now()->subDays(75)->toDateString(),
            'quercia',
            1500,
            'kg',
            1500,
            1420,
            0.28,
            'FSC',
            null,
            'Da verificare, giacenza ferma.',
            null,
        );
        DB::table('timber_lots')
            ->where('id', $this->requireLot($recordE)->id)
            ->update(['updated_at' => now()->subDays(45)]);

        app(SyncEasyfattCustomersAction::class)->execute();
        app(SyncEasyfattProductsAction::class)->execute();

        // Anagrafiche e magazzino in stile Easyfatt (clienti, prodotti, movimenti, documenti).
        $customers = $this->seedCustomers();
        $products = $this->seedProducts($suppliers);
        $this->seedWarehouseDocuments($customers, $products);

        // @phpstan-ignore isset.property (command is unset, not null, when the seeder runs outside an artisan console context)
        if (isset($this->command)) {
            $this->command->info('Demo Timber popolata: fornitori, trasportatori, agenti, lotti in vari stadi, essiccazione, confezioni, clienti, prodotti, movimenti e documenti di magazzino.');
        }
    }

    /**
     * Anagrafica clienti/fornitori in stile Easyfatt, con collegamento agli agenti.
     *
     * @return array<int, TimberCustomer>
     */
    private function seedCustomers(): array
    {
        $marco = TimberAgent::query()->where('full_name', 'Marco Ferretti')->first();
        $sara = TimberAgent::query()->where('full_name', 'Sara Colombo')->first();

        return [
            TimberCustomer::query()->firstOrCreate(
                ['business_name' => 'Falegnameria Rossi Srl'],
                [
                    'code' => 'CL-0001',
                    'vat_number' => 'IT01234567890',
                    'is_customer' => true,
                    'is_supplier' => false,
                    'address_line1' => 'Via Cristina Belgioioso, 70/28',
                    'postal_code' => '20157',
                    'city' => 'Milano',
                    'province' => 'MI',
                    'country' => 'Italia',
                    'sdi_recipient_code' => 'A4707H7',
                    'contact_person' => 'Maria Grazia Pagliara',
                    'phone' => '02 3820091',
                    'email' => 'ordini@falegnameriarossi.example',
                    'payment_terms' => 'RIBA 90 gg D.F.F.M.',
                    'price_list_code' => 'ben_b2b',
                    'agent_id' => $marco?->id,
                    'send_documents_by_email' => true,
                    'is_active' => true,
                ],
            ),
            TimberCustomer::query()->firstOrCreate(
                ['business_name' => 'Legnami Bianchi & Figli Snc'],
                [
                    'code' => 'CL-0002',
                    'vat_number' => 'IT09876543210',
                    'is_customer' => true,
                    'is_supplier' => true,
                    'address_line1' => 'Via Roma, 19',
                    'postal_code' => '26041',
                    'city' => 'Casalmaggiore',
                    'province' => 'CR',
                    'country' => 'Italia',
                    'contact_person' => 'Giuseppe Bianchi',
                    'phone' => '0375 200100',
                    'email' => 'amministrazione@legnamibianchi.example',
                    'payment_terms' => 'Bonifico 60 gg',
                    'price_list_code' => 'sboli_b2b',
                    'agent_id' => $sara?->id,
                    'is_active' => true,
                ],
            ),
            TimberCustomer::query()->firstOrCreate(
                ['business_name' => 'Iper Mobili Spa'],
                [
                    'code' => 'CL-0003',
                    'vat_number' => 'IT05566778899',
                    'is_customer' => true,
                    'is_supplier' => false,
                    'address_line1' => 'Viale Industria, 5',
                    'postal_code' => '35010',
                    'city' => 'Vigonza',
                    'province' => 'PD',
                    'country' => 'Italia',
                    'contact_person' => 'Ufficio acquisti',
                    'phone' => '049 5566778',
                    'email' => 'acquisti@ipermobili.example',
                    'payment_terms' => 'RIBA 30 gg',
                    'price_list_code' => 'ben_b2c',
                    'is_active' => true,
                ],
            ),
        ];
    }

    /**
     * Catalogo prodotti in stile Easyfatt, con listini multipli e fornitore collegato.
     *
     * @param  array<int, TimberSupplier>  $suppliers
     * @return array<int, TimberProduct>
     */
    private function seedProducts(array $suppliers): array
    {
        return [
            TimberProduct::query()->firstOrCreate(
                ['code' => 'PF-0001'],
                [
                    'description' => 'Legna in stanga essenza conifera/pioppo',
                    'product_type_code' => 'lot_managed',
                    'category' => 'Sboli legnami',
                    'unit_of_measure' => 'quintali',
                    'list_prices' => [
                        ['price_list_code' => 'ben_b2c', 'net_price' => 12.5],
                        ['price_list_code' => 'ben_b2b', 'net_price' => 10.0],
                    ],
                    'barcode' => '0000000000077',
                    'vat_rate_code' => '10',
                    'supplier_id' => $suppliers[0]->id ?? null,
                    'supplier_product_code' => 'ABE-STANGA',
                    'supplier_net_price' => 7.6554,
                    'minimum_stock' => 100,
                    'is_active' => true,
                ],
            ),
            TimberProduct::query()->firstOrCreate(
                ['code' => 'PF-0002'],
                [
                    'description' => 'Pellet di faggio in sacco da 15 kg',
                    'product_type_code' => 'stock_managed',
                    'category' => 'Smokey Ben',
                    'unit_of_measure' => 'quintali',
                    'list_prices' => [
                        ['price_list_code' => 'ben_b2c', 'net_price' => 6.5],
                        ['price_list_code' => 'ben_b2b', 'net_price' => 5.2],
                    ],
                    'barcode' => '8059001000021',
                    'vat_rate_code' => '22',
                    'supplier_id' => $suppliers[1]->id ?? null,
                    'supplier_product_code' => 'PELLET-FAG-15',
                    'supplier_net_price' => 4.2,
                    'minimum_stock' => 50,
                    'is_active' => true,
                ],
            ),
            TimberProduct::query()->firstOrCreate(
                ['code' => 'PF-0003'],
                [
                    'description' => 'Chips per affumicatura essenza Ciliegio',
                    'product_type_code' => 'stock_managed',
                    'category' => 'Smokey Ben',
                    'unit_of_measure' => 'kg',
                    'list_prices' => [
                        ['price_list_code' => 'smoke_b2c', 'net_price' => 2.35],
                        ['price_list_code' => 'smoke_b2b', 'net_price' => 1.9],
                    ],
                    'barcode' => '80590040004C',
                    'vat_rate_code' => '22',
                    'supplier_id' => $suppliers[2]->id ?? null,
                    'supplier_product_code' => 'CHIPS-CIL',
                    'supplier_net_price' => 1.5,
                    'minimum_stock' => 20,
                    'is_active' => true,
                ],
            ),
        ];
    }

    /**
     * Documenti di magazzino (arrivo merce, DDT, ordine) e movimenti coerenti.
     * L'arrivo carica lo stock, il DDT lo scarica verso un cliente, la rettifica corregge una giacenza.
     *
     * @param  array<int, TimberCustomer>  $customers
     * @param  array<int, TimberProduct>  $products
     */
    private function seedWarehouseDocuments(array $customers, array $products): void
    {
        if ($customers === [] || count($products) < 3) {
            return;
        }

        $postAction = app(PostTimberDocumentStockAction::class);
        $movementAction = app(RegisterTimberStockMovementAction::class);

        // Arrivo merce dal fornitore: carica il magazzino e valorizza i costi.
        $inbound = TimberDocument::query()->firstOrCreate(
            ['document_type_code' => 'arrivo_merce', 'number' => 'AM-2026-0001'],
            [
                'document_date' => now()->subDays(15)->toDateString(),
                'customer_id' => $customers[1]->id,
                'status_code' => 'confirmed',
                'reference' => 'Arrivo merce da fornitore',
            ],
        );
        if ($inbound->wasRecentlyCreated) {
            $inbound->lines()->create([
                'product_id' => $products[0]->id,
                'description' => $products[0]->description,
                'quantity' => 500,
                'unit_of_measure' => 'quintali',
                'unit_price' => 7.8,
                'discount_percent' => 0,
                'line_total' => 0,
            ]);
            $inbound->lines()->create([
                'product_id' => $products[1]->id,
                'description' => $products[1]->description,
                'quantity' => 300,
                'unit_of_measure' => 'quintali',
                'unit_price' => 4.5,
                'discount_percent' => 0,
                'line_total' => 0,
            ]);
            $inbound->recalculateTotals();
            $postAction->execute($inbound);
        }

        // DDT di vendita: scarica parte della giacenza verso un cliente.
        $ddt = TimberDocument::query()->firstOrCreate(
            ['document_type_code' => 'ddt', 'number' => 'DDT-2026-0001'],
            [
                'document_date' => now()->subDays(5)->toDateString(),
                'customer_id' => $customers[0]->id,
                'status_code' => 'confirmed',
                'reference' => 'Consegna cliente',
            ],
        );
        if ($ddt->wasRecentlyCreated) {
            $ddt->lines()->create([
                'product_id' => $products[0]->id,
                'description' => $products[0]->description,
                'quantity' => 60,
                'unit_of_measure' => 'quintali',
                'unit_price' => 10.0,
                'discount_percent' => 5,
                'line_total' => 0,
                'lot_code' => 'LOT-2026-0001',
            ]);
            $ddt->recalculateTotals();
            $postAction->execute($ddt);
        }

        // Ordine cliente ancora aperto (nessun impatto di magazzino), per popolare l'elenco ordini.
        $order = TimberDocument::query()->firstOrCreate(
            ['document_type_code' => 'ordine_cliente', 'number' => 'OC-2026-0001'],
            [
                'document_date' => now()->subDays(2)->toDateString(),
                'customer_id' => $customers[2]->id,
                'status_code' => 'confirmed',
                'reference' => 'Ordine da confermare',
            ],
        );
        if ($order->wasRecentlyCreated) {
            $order->lines()->create([
                'product_id' => $products[2]->id,
                'description' => $products[2]->description,
                'quantity' => 40,
                'unit_of_measure' => 'kg',
                'unit_price' => 1.9,
                'discount_percent' => 0,
                'line_total' => 0,
            ]);
            $order->recalculateTotals();
        }

        // Rettifica manuale di inventario su un prodotto (idempotente sul riferimento).
        $adjustmentExists = TimberStockMovement::query()
            ->where('document_reference', 'RETT-DEMO-01')
            ->exists();
        if (! $adjustmentExists) {
            $movementAction->execute(
                product: $products[1],
                directionCode: 'adjustment',
                quantity: -8,
                unitOfMeasure: 'quintali',
                documentReference: 'RETT-DEMO-01',
                notes: 'Rettifica inventario di fine mese.',
            );
        }
    }

    private function requireLot(TimberPurchaseRecord $record): TimberLot
    {
        $lot = $record->lot;
        if (! $lot instanceof TimberLot) {
            throw new RuntimeException('Il lotto atteso non e stato generato per il record di acquisto '.$record->id.'.');
        }

        return $lot;
    }

    /**
     * @return array<int, TimberSupplier>
     */
    private function seedSuppliers(): array
    {
        return [
            TimberSupplier::query()->firstOrCreate(
                ['legal_name' => 'Boschi Veneti Srl'],
                [
                    'vat_number' => 'IT01111100111',
                    'email' => 'acquisti@boschiveneti.example',
                    'phone' => '0421 555 111',
                    'city' => 'Vittorio Veneto',
                    'province' => 'TV',
                    'country_code' => 'IT',
                    'certifications' => 'FSC, PEFC',
                    'is_active' => true,
                ],
            ),
            TimberSupplier::query()->firstOrCreate(
                ['legal_name' => 'Legnami Alpini di Bianchi & C.'],
                [
                    'vat_number' => 'IT02222200222',
                    'email' => 'ordini@legnamialpini.example',
                    'phone' => '0461 555 222',
                    'city' => 'Trento',
                    'province' => 'TN',
                    'country_code' => 'IT',
                    'certifications' => 'FSC',
                    'is_active' => true,
                ],
            ),
            TimberSupplier::query()->firstOrCreate(
                ['legal_name' => 'Foreste del Sud Srl'],
                [
                    'vat_number' => 'IT03333300333',
                    'email' => 'commerciale@forestedelsud.example',
                    'phone' => '0984 555 333',
                    'city' => 'Cosenza',
                    'province' => 'CS',
                    'country_code' => 'IT',
                    'certifications' => 'PEFC',
                    'is_active' => true,
                ],
            ),
        ];
    }

    private function seedTransporters(): TimberTransporter
    {
        return TimberTransporter::query()->firstOrCreate(
            ['legal_name' => 'Trasporti Legnami Nord Est Srl'],
            [
                'vat_number' => 'IT04444400444',
                'phone' => '0422 555 444',
                'vehicle_plate' => 'TV123AB',
                'is_active' => true,
            ],
        );
    }

    private function seedAgents(): void
    {
        TimberAgent::query()->firstOrCreate(
            ['full_name' => 'Marco Ferretti'],
            [
                'email' => 'marco.ferretti@iter7-demo.example',
                'phone' => '333 1112223',
                'zone' => 'Nord-Est',
                'commission_percent' => 4.5,
                'is_active' => true,
            ],
        );

        TimberAgent::query()->firstOrCreate(
            ['full_name' => 'Sara Colombo'],
            [
                'email' => 'sara.colombo@iter7-demo.example',
                'phone' => '333 4445556',
                'zone' => 'Nord-Ovest',
                'commission_percent' => 5,
                'is_active' => true,
            ],
        );
    }
}
