<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignSpec;

/**
 * @extends Factory<SignSpec>
 */
final class SignSpecFactory extends Factory
{
    protected $model = SignSpec::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source_file' => $this->faker->word().'.ai',
            'drawing_title' => $this->faker->sentence(3),
            'customer_name' => $this->faker->company(),
            'referent_name' => $this->faker->name(),
            'brand' => $this->faker->company(),
            'shop' => $this->faker->city(),
            'sign_type_code' => strtoupper($this->faker->bothify('??_????##_####_######')),
            'quantity' => $this->faker->numberBetween(1, 20),
            'family' => strtoupper($this->faker->word()),
            'color_ral' => (string) $this->faker->numberBetween(1000, 9018),
            'thickness_mm' => $this->faker->randomFloat(2, 3, 40),
            'width_mm' => $this->faker->numberBetween(100, 3000),
            'height_mm' => $this->faker->numberBetween(50, 900),
            'painting' => 'RAL '.$this->faker->numberBetween(1000, 9018),
            'finishing' => $this->faker->randomElement(['MATT', 'GLOSSY', 'SATIN']),
            'lighting' => $this->faker->randomElement(['2700K', '3000K', '3500K', '4000K']),
            'scale' => '1:'.(int) $this->faker->randomElement([5, 10, 20]),
            'sheet_size' => $this->faker->randomElement(['A4', 'A3']),
            'revision' => 'RS '.$this->faker->numberBetween(0, 5),
            'creator' => 'Adobe Illustrator',
            'layers' => ['LED lighting', 'Fixing holes'],
            'notes' => [],
            'raw_extraction' => [],
        ];
    }
}
