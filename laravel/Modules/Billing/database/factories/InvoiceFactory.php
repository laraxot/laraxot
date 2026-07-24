<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\Invoice;
use Modules\Customer\Models\Customer;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /** @var class-string<Invoice> */
    protected $model = Invoice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'work_order_id' => null,
            'number' => 'INV-'.$this->faker->unique()->numerify('#####'),
            'issued_on' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'total' => $this->faker->randomFloat(2, 100, 10000),
            'notes' => null,
            'electronic_invoice_provider' => null,
            'electronic_invoice_external_id' => null,
            'electronic_invoice_sent_at' => null,
            'electronic_invoice_last_error' => null,
        ];
    }
}
