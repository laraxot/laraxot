<?php

declare(strict_types=1);

namespace Modules\Giveback\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Giveback\Providers\GivebackServiceProvider;
use Modules\User\Models\Role;
use Modules\Xot\Tests\XotBaseTestCase;

abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var array<int, string> */
    protected $connectionsToTransact = ['sqlite', 'xot'];

    protected function setUp(): void
    {
        $this->prepareSharedSqliteForTesting();

        parent::setUp();

        // Il pannello si apre a chi ha il ruolo omonimo al suo id: in produzione lo
        // crea PanelRoleSeeder (vedi Xot), qui basta quello di questo pannello.
        Role::findOrCreate('giveback-platform');
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            GivebackServiceProvider::class,
        ];
    }
}
