<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\Authentication;
<<<<<<< HEAD
<<<<<<< HEAD

/**
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\User;

/**
 * Authentication Factory
 *
 * Factory for creating Authentication model instances for testing and seeding.
 *
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

/**
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @extends Factory<Authentication>
 */
class AuthenticationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var class-string<Authentication>
>>>>>>> f548be94 (.)
=======
     *
     * @var class-string<Authentication>
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $model = Authentication::class;

    /**
     * Define the model's default state.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    /**
=======
     *
>>>>>>> f548be94 (.)
=======
     *
=======
     */
    /**
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return array<string, mixed>
     */
    public function definition(): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return [];
=======
=======
>>>>>>> 87273113 (.)
        $loginSuccessful = $this->faker->boolean(85); // 85% success rate
        $loginAt = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'type' => $this->faker->randomElement(['login', 'logout', 'password_reset', 'email_verification']),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'location' => $this->faker->optional(0.7)->city() . ', ' . $this->faker->optional(0.7)->country(),
            'login_successful' => $loginSuccessful,
            'login_at' => $loginAt,
            'logout_at' => $loginSuccessful && $this->faker->boolean(60)
                ? $this->faker->dateTimeBetween($loginAt, 'now')
                : null,
            'authenticatable_type' => User::class,
            'authenticatable_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the authentication was successful.
     */
    public function successful(): static
    {
        return $this->state(fn(array $_attributes): array => [
            'login_successful' => true,
        ]);
    }

    /**
     * Indicate that the authentication failed.
     */
    public function failed(): static
    {
        return $this->state(fn(array $_attributes): array => [
            'login_successful' => false,
            'logout_at' => null,
        ]);
    }

    /**
     * Set the authentication type to login.
     */
    public function login(): static
    {
        return $this->state(fn(array $_attributes): array => [
            'type' => 'login',
        ]);
    }

    /**
     * Set the authentication type to logout.
     */
    public function logout(): static
    {
        return $this->state(fn(array $_attributes): array => [
            'type' => 'logout',
            'logout_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Create authentication record for a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $_attributes): array => [
            'authenticatable_type' => User::class,
            'authenticatable_id' => $user->id,
        ]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        return [];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
