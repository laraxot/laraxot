<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\User\Models\User;


it('creates a dedicated timber panel user and assigns the default role', function (): void {
    $this->artisan('auth:create-timber-user', [
        'email' => 'timber.panel@test.local',
        'name' => 'Timber Panel User',
        '--password' => 'secret-timber-password',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'timber.panel@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->name)->toBe('Timber Panel User')
        ->and($user?->hasRole('timber_operator'))->toBeTrue();
});

it('creates a timber admin user when the role option is provided', function (): void {
    $this->artisan('auth:create-timber-user', [
        'email' => 'timber.admin@test.local',
        'name' => 'Timber Admin User',
        '--password' => 'secret-timber-password',
        '--role' => 'timber_admin',
    ])->assertExitCode(0);

    $user = User::query()->where('email', 'timber.admin@test.local')->first();

    expect($user?->hasRole('timber_admin'))->toBeTrue();
});

it('rejects an invalid role option', function (): void {
    $this->artisan('auth:create-timber-user', [
        'email' => 'timber.invalid@test.local',
        'name' => 'Invalid Role User',
        '--password' => 'secret-timber-password',
        '--role' => 'not-a-role',
    ])->assertExitCode(1);

    expect(User::query()->where('email', 'timber.invalid@test.local')->exists())->toBeFalse();
});

it('updates an existing dedicated timber user without resetting the password by default', function (): void {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'existing.timber@test.local',
        'password' => 'old-password',
    ]);

    $this->artisan('auth:create-timber-user', [
        'email' => 'existing.timber@test.local',
        'name' => 'New Name',
        '--password' => 'new-password',
    ])->assertExitCode(0);

    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->hasRole('timber_operator'))->toBeTrue()
        ->and(password_verify('old-password', (string) $user->getAuthPassword()))->toBeTrue();
});
