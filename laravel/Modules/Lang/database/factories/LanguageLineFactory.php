<?php

declare(strict_types=1);

namespace Modules\Lang\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD

/**
 * @extends Factory<\Modules\Lang\Models\LanguageLine>
 */
class LanguageLineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Lang\Models\LanguageLine::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
=======
use Modules\Lang\Models\LanguageLine;

/**
 * @extends Factory<LanguageLine>
 */
class LanguageLineFactory extends Factory
{
    /** @var class-string<LanguageLine> */
    protected $model = LanguageLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group' => $this->faker->randomElement(['validation', 'auth', 'pagination', 'passwords']),
            'key' => $this->faker->unique()->slug(2),
            'text' => [
                'it' => $this->faker->sentence(),
                'en' => $this->faker->sentence(),
            ],
            'locale' => $this->faker->randomElement(['it', 'en']),
        ];
>>>>>>> 677f1f5 (.)
    }
}
