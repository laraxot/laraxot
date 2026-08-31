<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\User\Models\User;
use Livewire\Livewire;
use Modules\Wts\Filament\Resources\UserResource;
use Modules\Wts\Filament\Resources\UserResource\Pages\ListUsers;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

/*
 * Nel sorgente il filtro WTS viveva dentro l'unica UserResource dell'app. Qui il
 * modulo User e' condiviso e non deve conoscere il WTS: la resource ristretta e'
 * Modules\Wts\Filament\Resources\UserResource, ed e' quella che il pannello monta.
 */
it('lists only wts_admin and wts_operator users in the wts panel', function (): void {
    foreach (['wts_admin', 'wts_operator', 'admin'] as $roleName) {
        Role::findOrCreate($roleName);
    }

    $wtsAdmin = User::factory()->create();
    $wtsAdmin->syncRoles(['wts_admin']);
    $wtsOperator = User::factory()->create();
    $wtsOperator->syncRoles(['wts_operator']);
    $systemAdmin = User::factory()->create();
    $systemAdmin->syncRoles(['admin']);

    filament()->setCurrentPanel('wts');
    actingAs($wtsAdmin);

    $ids = UserResource::getEloquentQuery()->pluck('id')->all();

    expect($ids)->toContain($wtsAdmin->id)
        ->toContain($wtsOperator->id)
        ->not->toContain($systemAdmin->id)
        ->and(UserResource::canViewAny())->toBeTrue()
        ->and(UserResource::canCreate())->toBeTrue();
});

it('hides user management from wts_operator', function (): void {
    Role::findOrCreate('wts_operator');
    $operator = User::factory()->create();
    $operator->syncRoles(['wts_operator']);

    filament()->setCurrentPanel('wts');
    actingAs($operator);

    expect(UserResource::canViewAny())->toBeFalse();
});

it('lets a wts_admin edit and view the plant users', function (): void {
    Role::findOrCreate('wts_admin');
    $admin = User::factory()->create();
    $admin->syncRoles(['wts_admin']);
    $other = User::factory()->create();
    $other->syncRoles(['wts_admin']);

    filament()->setCurrentPanel('wts');
    actingAs($admin);

    expect(UserResource::canEdit($other))->toBeTrue()
        ->and(UserResource::canView($other))->toBeTrue();
});

it('denies edit and view to a wts_operator', function (): void {
    foreach (['wts_admin', 'wts_operator'] as $roleName) {
        Role::findOrCreate($roleName);
    }
    $operator = User::factory()->create();
    $operator->syncRoles(['wts_operator']);
    $target = User::factory()->create();
    $target->syncRoles(['wts_admin']);

    filament()->setCurrentPanel('wts');
    actingAs($operator);

    expect(UserResource::canEdit($target))->toBeFalse()
        ->and(UserResource::canView($target))->toBeFalse();
});

it('offers the create action on the wts user list', function (): void {
    Role::findOrCreate('wts_admin');
    $admin = User::factory()->create();
    $admin->syncRoles(['wts_admin']);

    filament()->setCurrentPanel('wts');

    Livewire::actingAs($admin)
        ->test(ListUsers::class)
        ->assertActionExists('create');
});
