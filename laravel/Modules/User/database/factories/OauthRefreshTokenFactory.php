<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\OauthAccessToken;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\OauthClient;
use Modules\User\Models\OauthRefreshToken;

/**
 * OauthRefreshToken Factory.
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\OauthRefreshToken;

/**
 * OauthRefreshToken Factory
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Models\OauthClient;
use Modules\User\Models\OauthRefreshToken;

/**
 * OauthRefreshToken Factory.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * @extends Factory<OauthRefreshToken>
 */
class OauthRefreshTokenFactory extends Factory
{
    protected $model = OauthRefreshToken::class;

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
            'id' => $this->faker->sha256(),
<<<<<<< HEAD
<<<<<<< HEAD
            'access_token_id' => fn (): string => $this->newAccessTokenId(),
=======
=======
>>>>>>> 87273113 (.)
            'access_token_id' => fn() => OauthAccessToken::create([
                'id' => $this->faker->sha256(),
                'user_id' => null,
                'client_id' => $this->faker->sha256(),
                'name' => 'Test Token',
                'scopes' => [],
                'revoked' => false,
                'expires_at' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
            ])->id,
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'access_token_id' => fn (): string => $this->newAccessTokenId(),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'revoked' => $this->faker->boolean(5),
            'expires_at' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
        ];
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    protected function newAccessTokenId(): string
    {
        /** @var OauthAccessToken $token */
        $token = (new OauthAccessTokenFactory())->create([
            'id' => $this->faker->uuid(),
            'user_id' => null,
            'client_id' => OauthClient::factory(),
            'name' => 'Test Token',
            'scopes' => [],
            'revoked' => false,
            'expires_at' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
        ]);

        return (string) $token->id;
    }

<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function revoked(): static
    {
        return $this->state(['revoked' => true]);
    }

    public function expired(): static
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->state([
            'expires_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
=======
        return $this->state(['expires_at' => $this->faker->dateTimeBetween('-1 month', 'now')]);
>>>>>>> f548be94 (.)
=======
        return $this->state(['expires_at' => $this->faker->dateTimeBetween('-1 month', 'now')]);
=======
        return $this->state([
            'expires_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
