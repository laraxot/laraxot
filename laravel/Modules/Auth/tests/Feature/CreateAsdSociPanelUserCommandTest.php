<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\User\Models\User;


it('creates a dedicated asd admin user by default', function (): void {
    $this->artisan('auth:create-asd-soci-user', [
        'email' => 'asd.panel@test.local',
        'name' => 'ASD Panel User',
        '--password' => 'secret-asd-password',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'asd.panel@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->name)->toBe('ASD Panel User')
        ->and($user?->hasRole('asd_admin'))->toBeTrue()
        ->and($user?->approved_at)->not->toBeNull();
});

it('creates a dedicated asd secretary user with the selected role', function (): void {
    $this->artisan('auth:create-asd-soci-user', [
        'email' => 'asd.secretary@test.local',
        'name' => 'ASD Secretary',
        '--password' => 'secret-asd-password',
        '--role' => 'asd_secretary',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'asd.secretary@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->hasRole('asd_secretary'))->toBeTrue()
        ->and($user?->approved_at)->not->toBeNull();
});

it('rejects unsupported asd roles', function (): void {
    $this->artisan('auth:create-asd-soci-user', [
        'email' => 'asd.invalid@test.local',
        'name' => 'ASD Invalid',
        '--password' => 'secret-asd-password',
        '--role' => 'asd_unknown',
    ])->assertExitCode(1);

    expect(User::query()->where('email', 'asd.invalid@test.local')->exists())->toBeFalse();
});
