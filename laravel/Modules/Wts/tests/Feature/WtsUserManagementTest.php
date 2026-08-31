<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\User\Models\User;
use Modules\Wts\Filament\Resources\UserResource;
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
