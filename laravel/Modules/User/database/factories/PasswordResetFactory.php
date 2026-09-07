<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\PasswordReset;

/**
 * @extends Factory<PasswordReset>
 */
=======
=======
>>>>>>> 87273113 (.)
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\PasswordReset;

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\PasswordReset;

/**
 * @extends Factory<PasswordReset>
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class PasswordResetFactory extends Factory
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
    protected $model = PasswordReset::class;

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
     * @return array<(DateTime|string)>
     *
     * @psalm-return array{email: string, token: string, created_at: DateTime}
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->email,
            'token' => $this->faker->word,
            'created_at' => $this->faker->dateTime,
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
