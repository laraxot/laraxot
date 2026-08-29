<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberMovement;

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
