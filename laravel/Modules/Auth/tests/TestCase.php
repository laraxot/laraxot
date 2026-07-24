<?php

declare(strict_types=1);

namespace Modules\Auth\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case per il modulo Auth.
 *
 * Usa MySQL da .env.testing (come Modules/User): utenti, ruoli e permessi
 * (Modules\User\Models\User/Role, Spatie Permission) vivono sulla connessione
 * "user", creata dinamicamente da Modules\Tenant\Providers\TenantServiceProvider
 * a partire da DB_DATABASE in .env.testing (`techplanner_data_test`, DB reale,
 * gia' migrata con `php artisan migrate --env=testing`).
 *
 * NON includere "mysql" in $connectionsToTransact: `phpunit.xml` forza
 * DB_CONNECTION=sqlite / DB_DATABASE=:memory: per la connessione di default,
 * e quel valore :memory: si propaga anche a config('database.connections.mysql.database')
 * (stessa env var DB_DATABASE), rompendo qualunque tentativo di aprire una
 * transazione sulla connessione letterale "mysql".
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['user'];

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.providers.users.model' => User::class]);
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            UserServiceProvider::class,
            AuthServiceProvider::class,
        ];
    }
}
