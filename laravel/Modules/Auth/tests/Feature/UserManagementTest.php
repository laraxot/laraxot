<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Modules\User\Models\Role;

it('creates a user and assigns roles', function (): void {
    Role::query()->firstOrCreate(['name' => 'admin']);
    Role::query()->firstOrCreate(['name' => 'operator']);

    /** @var User $user */
    $user = User::query()->create([
        'name' => 'Test User',
        'email' => 'user-mgmt@test.local',
        'password' => Hash::make('secret123'),
    ]);

    $user->syncRoles(['admin', 'operator']);

    $user->refresh();
    expect($user->getRoleNames()->sort()->values()->all())->toBe(['admin', 'operator']);
});
