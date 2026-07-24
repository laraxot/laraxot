<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberMovement;

/**
 * @extends Factory<TimberMovement>
 */
final class TimberMovementFactory extends Factory
{
    protected $model = TimberMovement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movable_type' => TimberLot::class,
            'movable_id' => TimberLot::factory(),
            'from_area_code' => 'piazzale',
            'to_area_code' => 'zona_taglio',
            'moved_at' => now(),
            'notes' => null,
        ];
    }
}
