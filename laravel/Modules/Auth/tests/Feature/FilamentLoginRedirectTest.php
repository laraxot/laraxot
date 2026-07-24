<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use App\Filament\Pages\Auth\PanelLogin;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\User\Models\User;
use Modules\User\Models\Role;

afterEach(function (): void {
    filament()->setCurrentPanel(null);
});

it('redirects admin users to the admin panel after Filament login (even from operator login)', function (): void {
    // Arrange
    $adminRole = Role::findOrCreate('admin');

    $user = User::factory()->create([
        'email' => 'admin-filament@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole]);

    filament()->setCurrentPanel('operator');

    // Act + Assert
    Livewire::test(PanelLogin::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'secret123')
        ->set('data.accept_legal', true)
        ->call('authenticate')
        ->assertRedirect('/admin');
});

it('redirects administrator users to the admin panel after Filament login', function (): void {
    $adminRole = Role::findOrCreate('administrator');

    $user = User::factory()->create([
        'email' => 'administrator-filament@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole]);

    filament()->setCurrentPanel('operator');

    Livewire::test(PanelLogin::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'secret123')
        ->set('data.accept_legal', true)
        ->call('authenticate')
        ->assertRedirect('/admin');
});

it('redirects operator users to the operator panel after Filament login', function (): void {
    // Arrange
    $operatorRole = Role::findOrCreate('operator');

    $user = User::factory()->create([
        'email' => 'operator-filament@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$operatorRole]);

    filament()->setCurrentPanel('operator');

    // Act + Assert
    Livewire::test(PanelLogin::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'secret123')
        ->set('data.accept_legal', true)
        ->call('authenticate')
        ->assertRedirect('/operator');
});

it('redirects the gerardo account to the operator dashboard after Filament login', function (): void {
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');
    $user = User::factory()->create([
        'email' => 'info@gsbuildingservice.it',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole, $operatorRole]);

    filament()->setCurrentPanel('operator');

    Livewire::test(PanelLogin::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'secret123')
        ->set('data.accept_legal', true)
        ->call('authenticate')
        ->assertRedirect('/operator');
});

it('redirects users with both roles to the panel they are logging into (operator)', function (): void {
    // Arrange
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');

    $user = User::factory()->create([
        'email' => 'both-roles@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole, $operatorRole]);

    filament()->setCurrentPanel('operator');

    // Act + Assert
    Livewire::test(PanelLogin::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'secret123')
        ->set('data.accept_legal', true)
        ->call('authenticate')
        ->assertRedirect('/operator');
});

it('redirects users with both roles to the panel they are logging into (admin)', function (): void {
    // Arrange
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');

    $user = User::factory()->create([
        'email' => 'both-roles-admin@test.local',
        'password' => Hash::make('secret123'),
    ]);
    $user->syncRoles([$adminRole, $operatorRole]);

    filament()->setCurrentPanel('admin');

    // Act + Assert
    Livewire::test(PanelLogin::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'secret123')
        ->set('data.accept_legal', true)
        ->call('authenticate')
        ->assertRedirect('/admin');
});
