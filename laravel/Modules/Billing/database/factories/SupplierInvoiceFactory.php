<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\Supplier;
use Modules\Billing\Models\SupplierInvoice;

/**
 * @extends Factory<SupplierInvoice>
 */
class SupplierInvoiceFactory extends Factory
{
    /** @var class-string<SupplierInvoice> */
    protected $model = SupplierInvoice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'number' => 'SUP-'.$this->faker->unique()->numerify('#####'),
            'issued_on' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'total' => $this->faker->randomFloat(2, 100, 10000),
            'status_code' => $this->faker->randomElement(['pending', 'reconciled', 'disputed']),
            'notes' => null,
        ];
    }
}
