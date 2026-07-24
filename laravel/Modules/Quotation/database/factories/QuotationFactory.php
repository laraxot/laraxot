<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Models\Quotation;

/**
 * @extends Factory<Quotation>
 */
class QuotationFactory extends Factory
{
    /** @var class-string<Quotation> */
    protected $model = Quotation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'work_order_id' => null,
            'source_quotation_id' => null,
            'code' => 'Q-'.$this->faker->unique()->numerify('####'),
            'status_code' => 'draft',
            'title' => $this->faker->sentence(3),
            'notes' => null,
            'subtotal' => 0,
            'discount_amount' => 0,
            'taxable_amount' => 0,
            'tax_percent' => 22,
            'tax_amount' => 0,
            'total' => 0,
        ];
    }
}
