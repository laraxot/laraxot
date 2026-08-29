<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberInvoice;

/**
 * @extends Factory<TimberInvoice>
 */
final class TimberInvoiceFactory extends Factory
{
    protected $model = TimberInvoice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kind' => 'fattura',
            'number' => strtoupper($this->faker->unique()->bothify('FT-2026-####')),
            'invoice_date' => now()->toDateString(),
            'status_code' => 'draft',
            'payment_method_code' => 'bonifico',
            'payment_terms' => 'Bonifico 30 gg',
            'taxable_amount' => 0,
            'vat_amount' => 0,
            'total_amount' => 0,
        ];
    }
}
