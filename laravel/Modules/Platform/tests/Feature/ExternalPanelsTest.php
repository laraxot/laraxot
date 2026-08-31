<?php

declare(strict_types=1);

use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

/*
 * I pannelli cliente e fornitore aprono il gestionale a utenti esterni: quello che
 * NON vedono conta piu' di quello che vedono, quindi il test guarda entrambi i lati.
 */
uses(\Modules\Platform\Tests\TestCase::class);

function externalPanelUser(string $role): User
{
    Role::findOrCreate($role);
    $user = UserFactory::new()->createOne();
    $user->syncRoles([$role]);

    return $user;
}

it('lets a customer into their own panel', function (): void {
    actingAs(externalPanelUser('customer'))
        ->get('/customer')
        ->assertOk();
});

it('lets a supplier into their own panel', function (): void {
    actingAs(externalPanelUser('supplier'))
        ->get('/supplier')
        ->assertOk();
});

it('keeps a customer out of the supplier panel and vice versa', function (): void {
    actingAs(externalPanelUser('customer'))->get('/supplier')->assertForbidden();
    actingAs(externalPanelUser('supplier'))->get('/customer')->assertForbidden();
});

it('does not expose the audit log to a customer', function (): void {
    // Con la discovery accesa il pannello cliente ereditava le resource
    // amministrative del modulo Platform, e l'audit log rispondeva 200 a un
    // cliente qualunque. Ora quella rotta nel pannello non esiste proprio.
    actingAs(externalPanelUser('customer'))
        ->get('/customer/audit-logs')
        ->assertNotFound();
});

it('sends a guest to the login of each external panel', function (string $path): void {
    get($path)->assertRedirect();
})->with(['/customer', '/supplier']);
