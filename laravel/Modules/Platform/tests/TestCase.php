<?php

declare(strict_types=1);

namespace Modules\Platform\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Platform\Providers\PlatformServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case for Platform module.
 *
 * Uses shared fixcity_data.sqlite (no RefreshDatabase / migrate:fresh).
 * prepareSharedFixcitySqliteForTesting() runs before transactions begin.
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
            PlatformServiceProvider::class,
        ];
    }
}
