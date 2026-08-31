<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsOperationalParameter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsOperationalParameter>
 */
final class WtsOperationalParameterFactory extends Factory
{
    protected $model = WtsOperationalParameter::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'parameter_'.$this->faker->unique()->numberBetween(1000, 9999),
            'label' => $this->faker->words(3, true),
            'value' => '10',
            'unit' => 'kg',
            'description' => null,
            'updated_by_user_id' => null,
        ];
    }
}
