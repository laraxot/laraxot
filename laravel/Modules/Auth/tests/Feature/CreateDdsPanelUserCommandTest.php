<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\User\Models\User;


it('creates a dedicated dds admin user by default', function (): void {
    $this->artisan('auth:create-dds-user', [
        'email' => 'dds.panel@test.local',
        'name' => 'DDS Panel User',
        '--password' => 'secret-dds-password',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'dds.panel@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->name)->toBe('DDS Panel User')
        ->and($user?->hasRole('dds_admin'))->toBeTrue();
});

it('creates a dedicated dds technician user with the selected role', function (): void {
    $this->artisan('auth:create-dds-user', [
        'email' => 'dds.tech@test.local',
        'name' => 'DDS Technician',
        '--password' => 'secret-dds-password',
        '--role' => 'dds_technician',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'dds.tech@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->hasRole('dds_technician'))->toBeTrue();
});

it('rejects unsupported dds roles', function (): void {
    $this->artisan('auth:create-dds-user', [
        'email' => 'dds.invalid@test.local',
        'name' => 'DDS Invalid',
        '--password' => 'secret-dds-password',
        '--role' => 'dds_unknown',
    ])->assertExitCode(1);

    expect(User::query()->where('email', 'dds.invalid@test.local')->exists())->toBeFalse();
});
