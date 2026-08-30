<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Models\MetalSupplierPrice;

/**
 * @extends Factory<MetalSupplierPrice>
 */
final class MetalSupplierPriceFactory extends Factory
{
    protected $model = MetalSupplierPrice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'raw_material_id' => MetalRawMaterial::factory(),
            'supplier_name' => $this->faker->company(),
            'invoice_number' => $this->faker->unique()->numerify('2026FV######'),
            'delivery_note_number' => $this->faker->numerify('####'),
            'recorded_on' => now()->toDateString(),
            'quantity' => 100,
            'unit_code' => 'kg',
            'unit_price' => 0.95,
            'line_total' => 95.0,
            'source_file' => 'invoice.pdf',
        ];
    }
}
