<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Tests\Feature\Passport;

use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use PHPUnit\Framework\Assert;
=======
=======
>>>>>>> 87273113 (.)
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
namespace Modules\User\Tests\Feature\Passport;

use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use PHPUnit\Framework\Assert;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

uses(TestCase::class);

/**
 * @return array{client: Client, secret: string}
 */
function createPassportClient(): array
{
    $repository = app(ClientRepository::class);

    $client = $repository->createClientCredentialsGrantClient('Flow Test Client');

<<<<<<< HEAD
<<<<<<< HEAD
    $secret = $client->plainSecret ?? SafeStringCastAction::cast($client->getAttribute('secret'));

    return [
        'client' => $client,
        'secret' => $secret,
    ];
}

test('client credentials grant returns token', function (): void {
    /* @var TestCase $this */
=======
=======
>>>>>>> 87273113 (.)
    return ['client' => $client, 'secret' => $client->plainSecret ?? $client->secret];
}

test('client credentials grant returns token', function (): void {
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    $secret = $client->plainSecret ?? SafeStringCastAction::cast($client->getAttribute('secret'));

    return [
        'client' => $client,
        'secret' => $secret,
    ];
}

test('client credentials grant returns token', function (): void {
    /* @var TestCase $this */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ['client' => $client, 'secret' => $secret] = createPassportClient();

    $response = $this->post('/oauth/token', [
        'grant_type' => 'client_credentials',
        'client_id' => $client->id,
        'client_secret' => $secret,
        'scope' => '',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token_type', 'expires_in', 'access_token'])
        ->assertJsonPath('token_type', 'Bearer');
});

test('client credentials can be associated to a specific user', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
    /* @var TestCase $this */
    ['client' => $client] = createPassportClient();
    $user = UserFactory::new()->createOne();
=======
    ['client' => $client] = createPassportClient();
    $user = User::factory()->create();
>>>>>>> 60a2c9a9 (.)
=======
    ['client' => $client] = createPassportClient();
    $user = User::factory()->create();
=======
    /* @var TestCase $this */
    ['client' => $client] = createPassportClient();
    $user = UserFactory::new()->createOne();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    $client->owner()->associate($user);
    $client->forceFill([
        'user_id' => $user->getKey(),
<<<<<<< HEAD
<<<<<<< HEAD
        'owner_id' => SafeStringCastAction::cast($user->getKey()),
        'owner_type' => User::class,
=======
        'owner_id' => (string) $user->getKey(),
        'owner_type' => $user::class,
>>>>>>> 60a2c9a9 (.)
=======
        'owner_id' => (string) $user->getKey(),
        'owner_type' => $user::class,
=======
        'owner_id' => SafeStringCastAction::cast($user->getKey()),
        'owner_type' => User::class,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ]);
    $client->save();
    $client->refresh();

<<<<<<< HEAD
<<<<<<< HEAD
    Assert::assertNotNull($client->owner);
    Assert::assertTrue($client->owner->is($user));
    Assert::assertSame($user->getKey(), $client->getAttribute('user_id'));
=======
    expect($client->owner)->not->toBeNull()
        ->and($client->owner->is($user))->toBeTrue()
        ->and($client->user_id)->toBe($user->getKey());
>>>>>>> 60a2c9a9 (.)
=======
    expect($client->owner)->not->toBeNull()
        ->and($client->owner->is($user))->toBeTrue()
        ->and($client->user_id)->toBe($user->getKey());
=======
    Assert::assertNotNull($client->owner);
    Assert::assertTrue($client->owner->is($user));
    Assert::assertSame($user->getKey(), $client->getAttribute('user_id'));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
