<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\Role;

/**
<<<<<<< HEAD
<<<<<<< HEAD
=======
 * Factory per il modello Role del modulo User.
 *
>>>>>>> f548be94 (.)
=======
 * Factory per il modello Role del modulo User.
 *
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * The name of the factory's corresponding model.
=======
     * Il nome del modello corrispondente alla factory.
>>>>>>> f548be94 (.)
=======
     * Il nome del modello corrispondente alla factory.
=======
     * The name of the factory's corresponding model.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     *
     * @var class-string<Role>
     */
    protected $model = Role::class;

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'guard_name' => 'web',
            'team_id' => null,
        ];
    }
=======
=======
>>>>>>> 87273113 (.)
     * Definisce lo stato di default del modello.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'editor' => 'Editor',
            'user' => 'User',
            'moderator' => 'Moderator',
            'viewer' => 'Viewer',
            'contributor' => 'Contributor',
            'analyst' => 'Analyst',
            'support' => 'Support Agent',
            'developer' => 'Developer',
        ];

        $role = $this->faker->randomElement($roles);
        $name = array_search($role, $roles, strict: true);

        return [
            'name' => $name,
            'guard_name' => 'web',
        ];
    }

    /**
     * Crea un ruolo admin.
     *
     * @return static
     */
    public function admin(): static
    {
        return $this->state(fn(array $_attributes) => [
            'name' => 'admin',
        ]);
    }

    /**
     * Crea un ruolo manager.
     *
     * @return static
     */
    public function manager(): static
    {
        return $this->state(fn(array $_attributes) => [
            'name' => 'manager',
        ]);
    }

    /**
     * Crea un ruolo user.
     *
     * @return static
     */
    public function user(): static
    {
        return $this->state(fn(array $_attributes) => [
            'name' => 'user',
        ]);
    }

    /**
     * Crea un ruolo con un guard specifico.
     *
     * @param string $guard
     * @return static
     */
    public function withGuard(string $guard): static
    {
        return $this->state(fn(array $_attributes) => [
            'guard_name' => $guard,
        ]);
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'guard_name' => 'web',
            'team_id' => null,
        ];
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
