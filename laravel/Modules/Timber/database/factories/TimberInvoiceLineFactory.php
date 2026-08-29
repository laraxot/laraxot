<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberInvoice;
use Modules\Timber\Models\TimberInvoiceLine;

/**
 * @extends Factory<TimberInvoiceLine>
 */
final class TimberInvoiceLineFactory extends Factory
{
    protected $model = TimberInvoiceLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(3, 1, 100);
        $unitPrice = $this->faker->randomFloat(4, 1, 50);

        return [
            'invoice_id' => TimberInvoice::factory(),
            'description' => $this->faker->words(3, true),
            'quantity' => $quantity,
            'unit_of_measure' => 'quintali',
            'unit_price' => $unitPrice,
            'discount_percent' => 0,
            'vat_rate' => 22,
            'line_total' => round($quantity * $unitPrice, 2),
        ];
    }
}
