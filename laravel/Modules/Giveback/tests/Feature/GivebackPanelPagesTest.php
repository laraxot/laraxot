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
uses(\Modules\Giveback\Tests\TestCase::class);

it('opens every page of the panel', function (string $path): void {
    Role::findOrCreate('giveback_admin');
    $user = UserFactory::new()->createOne();
    $user->syncRoles(['giveback_admin']);

    actingAs($user);
    get($path)->assertOk();
})->with([
    '/giveback-platform',
    '/giveback-platform/value-flow',
    '/giveback-platform/widget-embed',
    '/giveback-platform/merchant-onboarding',
    '/giveback-platform/merchant-dashboard',
    '/giveback-platform/admin-payouts',
    '/giveback-platform/lottery-compliance',
    '/giveback-platform/roadmap',
    '/giveback-platform/documentation',
]);

it('sends a guest to the login', function (): void {
    get('/giveback-platform')->assertRedirect();
});
