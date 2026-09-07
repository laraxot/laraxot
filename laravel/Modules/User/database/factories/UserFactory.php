<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @return array<string, mixed>
     */
    /**
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\User\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @return array<string, mixed>
     */
    /**
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'id' => (string) Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'lang' => 'it',
            'type' => 'customer_user',
            'state' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
=======
=======
>>>>>>> 87273113 (.)
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'lang' => fake()->randomElement(['it', 'en', 'de']),
            'is_active' => true,
            'is_otp' => false,
            'password_expires_at' => fake()->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }

    /**
     * Indicate that the user should be active.
     */
    public function active(): static
    {
        return $this->state(fn(array $_attributes) => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'id' => (string) Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'lang' => 'it',
            'type' => 'customer_user',
            'state' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'is_active' => true,
        ]);
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function inactive(): static
    {
        return $this->state(fn (): array => [
=======
=======
>>>>>>> 87273113 (.)
    /**
     * Indicate that the user should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $_attributes) => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function inactive(): static
    {
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'is_active' => false,
        ]);
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function verified(): static
    {
        return $this->state(fn (): array => [
            'email_verified_at' => now(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (): array => [
=======
=======
>>>>>>> 87273113 (.)
    /**
     * Indicate that the user's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $_attributes) => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function verified(): static
    {
        return $this->state(fn (): array => [
            'email_verified_at' => now(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'email_verified_at' => null,
        ]);
    }
}
