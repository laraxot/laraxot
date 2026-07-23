<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\Product;

/**
 * Catalog products referenced by the parametric quotation rules
 * (config/quotation_technical.php), so the voice quotation can price flue,
 * radiator valves and thermostat lines out of the box. Idempotent.
 */
final class ThermoQuotationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['sku' => 'THERMO-CANNA-1M', 'name' => 'Canna fumaria coibentata (al metro)', 'unit_price' => 28.00],
            ['sku' => 'THERMO-VALV-TERM', 'name' => 'Valvola termostatica per radiatore', 'unit_price' => 12.50],
            ['sku' => 'THERMO-CRONO', 'name' => 'Cronotermostato programmabile WiFi', 'unit_price' => 79.00],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'name' => $product['name'],
                    'unit_price' => $product['unit_price'],
                    'is_active' => true,
                ],
            );
        }
    }
}
