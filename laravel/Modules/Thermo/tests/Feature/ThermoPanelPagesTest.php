<?php

declare(strict_types=1);

use Modules\User\Database\Factories\UserFactory;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

/*
 * Le pagine di questo verticale si dichiarano non scopribili e vivono solo
 * nell'elenco del PanelProvider: aprirle davvero e' l'unico modo per accorgersi
 * subito di una pagina dimenticata, che altrimenti resta un 404 fino alla demo.
 */
uses(\Modules\Thermo\Tests\TestCase::class);

it('opens every page of the panel', function (string $path): void {
    Role::findOrCreate('thermo_admin');
    $user = UserFactory::new()->createOne();
    $user->syncRoles(['thermo_admin']);

    actingAs($user)->get($path)->assertOk();
})->with([
    '/thermo',
    '/thermo/calendar-dispatch',
    '/thermo/crm-systems',
    '/thermo/account-access',
    '/thermo/survey-quote',
    '/thermo/field-app',
    '/thermo/compliance-billing',
    '/thermo/bluetooth-pump',
    '/thermo/ai-documents',
]);

it('sends a guest to the login', function (): void {
    get('/thermo')->assertRedirect();
});
