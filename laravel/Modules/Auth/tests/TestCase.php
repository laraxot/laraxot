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
 * "user"/mysql reale, già migrata esternamente (`php artisan migrate --env=testing`).
 * Non usare il fixture sqlite condiviso (fixcity_data.sqlite): non contiene le
 * tabelle users/roles/permissions.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['mysql', 'user'];

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
