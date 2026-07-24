<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Billing\Models\SupplierInvoiceLine;

/**
 * @extends Factory<SupplierInvoiceLine>
 */
class SupplierInvoiceLineFactory extends Factory
{
    /** @var class-string<SupplierInvoiceLine> */
    protected $model = SupplierInvoiceLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_invoice_id' => SupplierInvoice::factory(),
            'delivery_note_line_id' => null,
            'description' => $this->faker->sentence(4),
            'quantity' => $this->faker->numberBetween(1, 20),
            'unit_price' => $this->faker->randomFloat(2, 5, 800),
        ];
    }
}
