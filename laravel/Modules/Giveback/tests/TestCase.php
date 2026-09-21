<?php

declare(strict_types=1);

namespace Modules\Giveback\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Giveback\Providers\GivebackServiceProvider;
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
