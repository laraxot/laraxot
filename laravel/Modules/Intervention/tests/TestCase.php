<?php

declare(strict_types=1);

namespace Modules\Intervention\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Intervention\Providers\InterventionServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case per il modulo Intervention.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            InterventionServiceProvider::class,
        ];
    }
}
