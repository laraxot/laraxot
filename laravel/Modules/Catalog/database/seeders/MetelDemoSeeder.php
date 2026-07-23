<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Catalog\Models\MetelCatalogItem;

/**
 * Demo METEL catalog with realistic idrotermosanitario articles, so the import,
 * browsing and quotation price-matching flows work end-to-end without a real
 * Metel contract. Idempotent: safe to run repeatedly.
 */
final class MetelDemoSeeder extends Seeder
{
    public function run(): void
    {
        /** @var MetelCatalog $catalog */
        $catalog = MetelCatalog::query()->updateOrCreate(
            ['code' => 'demo-idro'],
            [
                'brand_code' => 'DMO',
                'name' => 'Catalogo Demo Idrotermosanitario',
                'manufacturer' => 'Demo Idraulica S.p.A.',
                'source_type' => 'file',
                'version' => '2026/01',
                'imported_at' => now(),
                'is_active' => true,
            ],
        );

        foreach ($this->demoItems() as $item) {
            MetelCatalogItem::query()->updateOrCreate(
                ['metel_catalog_id' => $catalog->getKey(), 'metel_code' => $item['metel_code']],
                [
                    'description' => $item['description'],
                    'unit_of_measure' => $item['unit_of_measure'],
                    'packaging' => $item['packaging'],
                    'gross_price' => $item['gross_price'],
                    'net_price' => $item['net_price'],
                    'barcode' => $item['barcode'],
                    'etim_code' => $item['etim_code'],
                ],
            );
        }
    }

    /**
     * @return list<array{metel_code: string, description: string, unit_of_measure: string, packaging: string, gross_price: float, net_price: float, barcode: string, etim_code: string}>
     */
    private function demoItems(): array
    {
        return [
            ['metel_code' => 'DMO0100010', 'description' => 'Tubo multistrato PEX-AL-PEX 16x2 mm', 'unit_of_measure' => 'MT', 'packaging' => '100', 'gross_price' => 1.85, 'net_price' => 0.92, 'barcode' => '8000000010017', 'etim_code' => 'EC000123'],
            ['metel_code' => 'DMO0100020', 'description' => 'Tubo multistrato PEX-AL-PEX 20x2 mm', 'unit_of_measure' => 'MT', 'packaging' => '100', 'gross_price' => 2.60, 'net_price' => 1.30, 'barcode' => '8000000010024', 'etim_code' => 'EC000123'],
            ['metel_code' => 'DMO0200016', 'description' => 'Raccordo a pressare gomito 90° 16x1/2" F', 'unit_of_measure' => 'PZ', 'packaging' => '10', 'gross_price' => 4.90, 'net_price' => 2.45, 'barcode' => '8000000020016', 'etim_code' => 'EC000210'],
            ['metel_code' => 'DMO0200020', 'description' => 'Raccordo a pressare TEE 20x16x20', 'unit_of_measure' => 'PZ', 'packaging' => '10', 'gross_price' => 6.30, 'net_price' => 3.15, 'barcode' => '8000000020023', 'etim_code' => 'EC000210'],
            ['metel_code' => 'DMO0300012', 'description' => 'Valvola a sfera ottone 1/2" M-F PN25', 'unit_of_measure' => 'PZ', 'packaging' => '20', 'gross_price' => 7.80, 'net_price' => 3.90, 'barcode' => '8000000030014', 'etim_code' => 'EC000345'],
            ['metel_code' => 'DMO0300034', 'description' => 'Valvola a sfera ottone 3/4" M-F PN25', 'unit_of_measure' => 'PZ', 'packaging' => '20', 'gross_price' => 9.60, 'net_price' => 4.80, 'barcode' => '8000000030038', 'etim_code' => 'EC000345'],
            ['metel_code' => 'DMO0400050', 'description' => 'Collettore componibile 1" 4 derivazioni', 'unit_of_measure' => 'PZ', 'packaging' => '1', 'gross_price' => 34.00, 'net_price' => 17.00, 'barcode' => '8000000040051', 'etim_code' => 'EC000512'],
            ['metel_code' => 'DMO0500001', 'description' => 'Miscelatore monocomando lavabo cromato', 'unit_of_measure' => 'PZ', 'packaging' => '1', 'gross_price' => 58.00, 'net_price' => 29.00, 'barcode' => '8000000050013', 'etim_code' => 'EC000701'],
            ['metel_code' => 'DMO0600002', 'description' => 'Scaldabagno elettrico 80 lt verticale', 'unit_of_measure' => 'PZ', 'packaging' => '1', 'gross_price' => 210.00, 'net_price' => 128.00, 'barcode' => '8000000060029', 'etim_code' => 'EC000890'],
            ['metel_code' => 'DMO0700003', 'description' => 'Sifone a bottiglia PP 1"1/4 estensibile', 'unit_of_measure' => 'PZ', 'packaging' => '25', 'gross_price' => 3.20, 'net_price' => 1.60, 'barcode' => '8000000070036', 'etim_code' => 'EC000934'],
            ['metel_code' => 'DMO0800004', 'description' => 'Guarnizione OR set assortito (100 pz)', 'unit_of_measure' => 'CF', 'packaging' => '1', 'gross_price' => 12.50, 'net_price' => 6.25, 'barcode' => '8000000080043', 'etim_code' => 'EC000988'],
            ['metel_code' => 'DMO0900005', 'description' => 'Nastro teflon PTFE 12mm x 12mt', 'unit_of_measure' => 'PZ', 'packaging' => '50', 'gross_price' => 0.90, 'net_price' => 0.42, 'barcode' => '8000000090050', 'etim_code' => 'EC001010'],
        ];
    }
}
