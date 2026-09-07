<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\Membership;
use Modules\User\Models\Team;
use Modules\User\Models\User;

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * Membership Factory.
=======
 * Membership Factory
>>>>>>> f548be94 (.)
=======
 * Membership Factory
=======
 * Membership Factory.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * Factory for creating Membership model instances for testing and seeding.
 *
 * @extends Factory<Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Membership>
     */
    protected $model = Membership::class;

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
=======
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<string, mixed>
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
<<<<<<< HEAD
<<<<<<< HEAD
            'role' => fake()->randomElement(['admin', 'editor', 'member', 'viewer']),
            'customer_id' => fake()->optional(0.3)->uuid(),
=======
            'role' => $this->faker->randomElement(['admin', 'editor', 'member', 'viewer']),
            'customer_id' => $this->faker->optional(0.3)->uuid(),
>>>>>>> f548be94 (.)
=======
            'role' => $this->faker->randomElement(['admin', 'editor', 'member', 'viewer']),
            'customer_id' => $this->faker->optional(0.3)->uuid(),
=======
            'role' => fake()->randomElement(['admin', 'editor', 'member', 'viewer']),
            'customer_id' => fake()->optional(0.3)->uuid(),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ];
    }

    /**
     * Create membership for a specific team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function forTeam(Team $team): static
    {
        return $this->state(fn (array $_attributes): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param Team $team
     * @return static
     */
    public function forTeam(Team $team): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function forTeam(Team $team): static
    {
        return $this->state(fn (array $_attributes): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'team_id' => $team->id,
        ]);
    }

    /**
     * Create membership for a specific user.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $_attributes): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param User $user
     * @return static
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $_attributes): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'user_id' => $user->id,
        ]);
    }

    /**
     * Set the role to admin.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function admin(): static
    {
        return $this->state(fn (array $_attributes): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return static
     */
    public function admin(): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function admin(): static
    {
        return $this->state(fn (array $_attributes): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'role' => 'admin',
        ]);
    }

    /**
     * Set the role to editor.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function editor(): static
    {
        return $this->state(fn (array $_attributes): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return static
     */
    public function editor(): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function editor(): static
    {
        return $this->state(fn (array $_attributes): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'role' => 'editor',
        ]);
    }

    /**
     * Set the role to member.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function member(): static
    {
        return $this->state(fn (array $_attributes): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return static
     */
    public function member(): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function member(): static
    {
        return $this->state(fn (array $_attributes): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'role' => 'member',
        ]);
    }

    /**
     * Set the role to viewer.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function viewer(): static
    {
        return $this->state(fn (array $_attributes): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return static
     */
    public function viewer(): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function viewer(): static
    {
        return $this->state(fn (array $_attributes): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'role' => 'viewer',
        ]);
    }
}
