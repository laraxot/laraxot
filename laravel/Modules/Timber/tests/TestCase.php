<?php

declare(strict_types=1);

namespace Modules\Timber\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Timber\Providers\TimberServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'xot', 'timber'];

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            TimberServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        $this->prepareSharedSqliteForTesting();

        parent::setUp();
    }
}
