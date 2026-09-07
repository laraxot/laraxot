<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\ModelHasRole;

/**
 * @extends Factory<ModelHasRole>
 */
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\ModelHasRole;

>>>>>>> f548be94 (.)
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\ModelHasRole;

=======
use Modules\User\Models\ModelHasRole;

/**
 * @extends Factory<ModelHasRole>
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class ModelHasRoleFactory extends Factory
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
    protected $model = ModelHasRole::class;

    /**
     * Define the model's default state.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return array<int|string>
     *
     * @psalm-return array{role_id: int, model_type: string, model_id: int}
     */
    public function definition(): array
    {
        return [
            'role_id' => $this->faker->randomNumber(5, false),
            'model_type' => $this->faker->word,
            'model_id' => $this->faker->randomNumber(5, false),
        ];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
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
