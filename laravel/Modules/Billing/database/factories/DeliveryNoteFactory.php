<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\Supplier;

/**
 * @extends Factory<DeliveryNote>
 */
class DeliveryNoteFactory extends Factory
{
    /** @var class-string<DeliveryNote> */
    protected $model = DeliveryNote::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'work_order_id' => null,
            'number' => 'DDT-'.$this->faker->unique()->numerify('#####'),
            'received_on' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'notes' => null,
        ];
    }
}
