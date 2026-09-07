<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\OauthAccessToken;
use Modules\User\Models\OauthClient;
use Modules\User\Models\User;

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * OauthAccessToken Factory.
=======
 * OauthAccessToken Factory
>>>>>>> f548be94 (.)
=======
 * OauthAccessToken Factory
=======
 * OauthAccessToken Factory.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * Factory for creating OauthAccessToken model instances for testing and seeding.
 *
 * @extends Factory<OauthAccessToken>
 */
class OauthAccessTokenFactory extends Factory
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<OauthAccessToken>
     */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected $model = OauthAccessToken::class;

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
            'id' => $this->faker->uuid(),
            'user_id' => User::factory(),
            'client_id' => OauthClient::factory(),
            'name' => $this->faker->optional()->words(2, true),
<<<<<<< HEAD
<<<<<<< HEAD
            'scopes' => $this->faker->randomElements(
                ['read', 'write', 'admin', 'user'],
                $this->faker->numberBetween(1, 3),
            ),
            'revoked' => $this->faker->boolean(10),
=======
=======
>>>>>>> 87273113 (.)
            'scopes' => $this->faker->optional()->randomElements(
                [
                    'read',
                    'write',
                    'admin',
                    'user',
                ],
                $this->faker->numberBetween(1, 3),
            ),
            'revoked' => $this->faker->boolean(10), // 10% revoked
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'scopes' => $this->faker->randomElements(
                ['read', 'write', 'admin', 'user'],
                $this->faker->numberBetween(1, 3),
            ),
            'revoked' => $this->faker->boolean(10),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }

    /**
     * Create a revoked token.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function revoked(): static
    {
        return $this->state(fn (): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return static
     */
    public function revoked(): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function revoked(): static
    {
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'revoked' => true,
        ]);
    }

    /**
     * Create an active token.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function active(): static
    {
        return $this->state(fn (): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return static
     */
    public function active(): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function active(): static
    {
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'revoked' => false,
            'expires_at' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
        ]);
    }

    /**
     * Create token for a specific user.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (): array => [
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
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'user_id' => $user->id,
        ]);
    }

    /**
     * Create token for a specific client.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function forClient(OauthClient $client): static
    {
        return $this->state(fn (): array => [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param OauthClient $client
     * @return static
     */
    public function forClient(OauthClient $client): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function forClient(OauthClient $client): static
    {
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'client_id' => $client->id,
        ]);
    }

    /**
     * Create token with specific scopes.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param list<string> $scopes
     */
    public function withScopes(array $scopes): static
    {
        return $this->state(fn (): array => [
=======
=======
>>>>>>> 87273113 (.)
     * @param array<string> $scopes
     * @return static
     */
    public function withScopes(array $scopes): static
    {
        return $this->state(fn(array $_attributes): array => [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @param list<string> $scopes
     */
    public function withScopes(array $scopes): static
    {
        return $this->state(fn (): array => [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'scopes' => $scopes,
        ]);
    }
}
