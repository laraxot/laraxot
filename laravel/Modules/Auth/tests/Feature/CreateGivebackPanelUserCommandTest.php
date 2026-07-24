<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\User\Models\User;


it('creates a dedicated giveback admin user by default', function (): void {
    $this->artisan('auth:create-giveback-user', [
        'email' => 'giveback.panel@test.local',
        'name' => 'GiveBack Panel User',
        '--password' => 'secret-giveback-password',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'giveback.panel@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->name)->toBe('GiveBack Panel User')
        ->and($user?->hasRole('giveback_admin'))->toBeTrue()
        ->and($user?->approved_at)->not->toBeNull();
});

it('creates a dedicated giveback merchant user with the selected role', function (): void {
    $this->artisan('auth:create-giveback-user', [
        'email' => 'giveback.merchant@test.local',
        'name' => 'GiveBack Merchant',
        '--password' => 'secret-giveback-password',
        '--role' => 'giveback_merchant',
    ])->assertExitCode(0);

    /** @var User|null $user */
    $user = User::query()->where('email', 'giveback.merchant@test.local')->first();

    expect($user)->not->toBeNull()
        ->and($user?->hasRole('giveback_merchant'))->toBeTrue()
        ->and($user?->approved_at)->not->toBeNull();
});

it('rejects unsupported giveback roles', function (): void {
    $this->artisan('auth:create-giveback-user', [
        'email' => 'giveback.invalid@test.local',
        'name' => 'GiveBack Invalid',
        '--password' => 'secret-giveback-password',
        '--role' => 'giveback_unknown',
    ])->assertExitCode(1);

    expect(User::query()->where('email', 'giveback.invalid@test.local')->exists())->toBeFalse();
});
