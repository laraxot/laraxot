<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\DeliveryNoteLine;

/**
 * @extends Factory<DeliveryNoteLine>
 */
class DeliveryNoteLineFactory extends Factory
{
    /** @var class-string<DeliveryNoteLine> */
    protected $model = DeliveryNoteLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'delivery_note_id' => DeliveryNote::factory(),
            'description' => $this->faker->sentence(4),
            'quantity' => $this->faker->numberBetween(1, 20),
            'unit_price' => $this->faker->randomFloat(2, 5, 800),
        ];
    }
}
