<?php

declare(strict_types=1);

namespace Modules\HR\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\HR\Providers\HrServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case per il modulo HR.
 *
 * Usa fixcity_data.sqlite condiviso (no RefreshDatabase / migrate:fresh).
 * prepareSharedFixcitySqliteForTesting() gira prima dell'inizio delle transazioni.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite'];

    protected function setUp(): void
    {
        $this->prepareSharedFixcitySqliteForTesting();

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
            HrServiceProvider::class,
        ];
    }
}
