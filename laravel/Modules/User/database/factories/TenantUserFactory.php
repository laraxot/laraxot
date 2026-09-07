<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\TenantUser;

/**
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\Tenant;
use Modules\User\Models\TenantUser;
use Modules\User\Models\User;

/**
 * TenantUser Factory
 *
 * Factory for creating TenantUser model instances for testing and seeding.
 *
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Models\TenantUser;

/**
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @extends Factory<TenantUser>
 */
class TenantUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TenantUser>
     */
    protected $model = TenantUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => fake()->uuid(),
            'user_id' => fake()->uuid(),
        ];
    }
=======
=======
>>>>>>> 87273113 (.)
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Create tenant-user relationship for a specific tenant.
     *
     * @param Tenant $tenant
     * @return static
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn(array $_attributes): array => [
            'tenant_id' => $tenant->id,
        ]);
    }

    /**
     * Create tenant-user relationship for a specific user.
     *
     * @param User $user
     * @return static
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $_attributes): array => [
            'user_id' => $user->id,
        ]);
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => fake()->uuid(),
            'user_id' => fake()->uuid(),
        ];
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
