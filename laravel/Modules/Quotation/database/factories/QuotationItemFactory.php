<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;

/**
 * @extends Factory<QuotationItem>
 */
class QuotationItemFactory extends Factory
{
    /** @var class-string<QuotationItem> */
    protected $model = QuotationItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quotation_id' => Quotation::factory(),
            'description' => $this->faker->sentence(),
            'quantity' => 1,
            'unit_price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
