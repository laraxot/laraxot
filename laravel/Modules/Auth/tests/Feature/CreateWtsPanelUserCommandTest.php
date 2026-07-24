<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\User\Models\User;


it('creates a dedicated wts panel user and assigns the wts role', function (): void {
    $this->artisan('auth:create-wts-user', [
        'email' => 'wts.panel@test.local',
        'name' => 'WTS Panel User',
        '--password' => 'secret-wts-password',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'wts.panel@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->name)->toBe('WTS Panel User')
        ->and($user?->hasRole('wts'))->toBeTrue();
});

it('updates an existing dedicated wts user without resetting the password by default', function (): void {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'existing.wts@test.local',
        'password' => 'old-password',
    ]);

    $this->artisan('auth:create-wts-user', [
        'email' => 'existing.wts@test.local',
        'name' => 'New Name',
        '--password' => 'new-password',
    ])->assertExitCode(0);

    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->hasRole('wts'))->toBeTrue()
        ->and(password_verify('old-password', (string) $user->getAuthPassword()))->toBeTrue();
});
