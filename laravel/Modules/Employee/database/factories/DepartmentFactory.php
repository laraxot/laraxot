<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\Department;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Department>
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word().' Department',
            'description' => $this->faker->optional()->sentence(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'status' => $this->faker->randomElement(['attivo', 'inattivo']),
            'manager_id' => null, // Will be set when needed
        ];
    }

    /**
     * Indicate that the department is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $_attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the department is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $_attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Set a specific department name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $_attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Set a specific description.
     */
    public function withDescription(string $description): static
    {
        return $this->state(fn (array $_attributes) => [
            'description' => $description,
        ]);
    }
}
