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
uses(\Modules\AsdSoci\Tests\TestCase::class);

it('sends a guest to the login', function (): void {
    get('/asd-soci')->assertRedirect();
});

it('opens every page of the panel', function (): void {
    Role::findOrCreate('asd_admin');
    $user = UserFactory::new()->createOne();
    $user->syncRoles(['asd-soci', 'asd_admin']);

    actingAs($user);

    $paths = [
        '/asd-soci',
        '/asd-soci/enrollment',
        '/asd-soci/member-register',
        '/asd-soci/accounting',
        '/asd-soci/events',
        '/asd-soci/member-area',
        '/asd-soci/documentation',
    ];

    foreach ($paths as $path) {
        get($path)->assertOk();
    }
});
