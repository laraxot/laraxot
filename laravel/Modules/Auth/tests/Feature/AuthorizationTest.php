<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\User\Models\User;
use Modules\User\Models\Role;


it('blocks operator users from accessing the admin panel', function (): void {
    $operatorRole = Role::findOrCreate('operator');

    $user = User::factory()->create();
    $user->syncRoles([$operatorRole]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertStatus(403);
});

it('allows admin users to access the admin panel', function (): void {
    $adminRole = Role::findOrCreate('admin');

    $user = User::factory()->create();
    $user->syncRoles([$adminRole]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertStatus(200);
});
