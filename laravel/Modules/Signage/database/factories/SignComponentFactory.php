<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignComponent;
use Modules\Signage\Models\SignSpec;

/**
 * @extends Factory<SignComponent>
 */
final class SignComponentFactory extends Factory
{
    protected $model = SignComponent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sign_spec_id' => SignSpec::factory(),
            'position' => $this->faker->numberBetween(0, 20),
            'role_code' => $this->faker->randomElement(['body', 'face', 'led', 'cable', 'fixing', 'paint']),
            'label' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'material' => $this->faker->randomElement(['ACRYLBLOCK', 'OPAL PLEX', 'STRIPLED', 'OTTONE']),
            'color_ral' => $this->faker->optional()->numberBetween(1000, 9018),
            'thickness_mm' => $this->faker->optional()->randomFloat(2, 3, 40),
            'lighting' => $this->faker->optional()->randomElement(['3000K', '3500K']),
            'source_code' => $this->faker->randomElement(['callout', 'layer', 'title_block', 'code']),
        ];
    }
}
