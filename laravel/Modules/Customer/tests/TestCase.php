<?php

declare(strict_types=1);

namespace Modules\Customer\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Customer\Providers\CustomerServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'xot', 'customer'];

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            CustomerServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        $this->prepareSharedFixcitySqliteForTesting();

        parent::setUp();
    }
}
