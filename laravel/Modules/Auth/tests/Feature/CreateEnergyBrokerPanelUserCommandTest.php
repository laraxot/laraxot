<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\User\Models\User;


it('creates a dedicated energy broker admin user by default', function (): void {
    $this->artisan('auth:create-energy-broker-user', [
        'email' => 'energy.broker@test.local',
        'name' => 'Energy Broker Admin',
        '--password' => 'secret-energy-password',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'energy.broker@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->name)->toBe('Energy Broker Admin')
        ->and($user?->hasRole('energy_broker_admin'))->toBeTrue()
        ->and($user?->approved_at)->not->toBeNull();
});

it('creates a dedicated energy broker agent with the selected role', function (): void {
    $this->artisan('auth:create-energy-broker-user', [
        'email' => 'energy.agent@test.local',
        'name' => 'Energy Broker Agent',
        '--password' => 'secret-energy-password',
        '--role' => 'energy_broker_agent',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'energy.agent@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->hasRole('energy_broker_agent'))->toBeTrue();
});

it('rejects unsupported energy broker roles', function (): void {
    $this->artisan('auth:create-energy-broker-user', [
        'email' => 'energy.invalid@test.local',
        'name' => 'Energy Invalid',
        '--password' => 'secret-energy-password',
        '--role' => 'energy_broker_unknown',
    ])->assertExitCode(1);

    expect(User::query()->where('email', 'energy.invalid@test.local')->exists())->toBeFalse();
});
