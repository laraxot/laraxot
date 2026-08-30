<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Models\SignSupplierPriceEntry;

/**
 * @extends Factory<SignSupplierPriceEntry>
 */
final class SignSupplierPriceEntryFactory extends Factory
{
    protected $model = SignSupplierPriceEntry::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sign_price_list_item_id' => SignPriceListItem::factory(),
            'invoice_number' => $this->faker->bothify('##V########'),
            'recorded_on' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(4, 1, 500),
        ];
    }
}
