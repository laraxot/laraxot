<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\TeamPermission;

/**
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\Team;
use Modules\User\Models\TeamPermission;

/**
 * TeamPermission Factory
 *
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Models\TeamPermission;

/**
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @extends Factory<TeamPermission>
 */
class TeamPermissionFactory extends Factory
{
<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TeamPermission::class;

    /**
     * Define the model's default state.
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
    protected $model = TeamPermission::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'permission' => $this->faker->randomElement([
                'create_projects',
                'edit_projects',
                'delete_projects',
                'manage_members',
                'view_analytics',
            ]),
        ];
    }

    public function forTeam(Team $team): static
    {
        return $this->state(['team_id' => $team->id]);
    }

    public function createProjects(): static
    {
        return $this->state(['permission' => 'create_projects']);
    }

    public function manageMembers(): static
    {
        return $this->state(['permission' => 'manage_members']);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TeamPermission::class;

    /**
     * Define the model's default state.
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
