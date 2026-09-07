<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\Feature;

/**
 * @extends Factory<Feature>
 */
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Feature;

>>>>>>> f548be94 (.)
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Feature;

=======
use Modules\User\Models\Feature;

/**
 * @extends Factory<Feature>
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class FeatureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var class-string<Model>
>>>>>>> f548be94 (.)
=======
     *
     * @var class-string<Model>
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $model = Feature::class;

    /**
     * Define the model's default state.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
=======
=======
>>>>>>> 87273113 (.)
    public function definition(): array
    {
        return [
            // 'user_id' => $this->faker->randomNumber(5),
            'name' => $this->faker->name,
            'personal_team' => $this->faker->boolean,
        ];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
