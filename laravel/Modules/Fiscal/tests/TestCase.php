<?php

declare(strict_types=1);

namespace Modules\Fiscal\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Modules\Fiscal\Providers\FiscalServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case per il modulo Fiscal.
 *
 * Il modulo Fiscal non ha modelli persistibili: le Action sono calcolatori
 * puri (totali fattura, piano rate, liquidazione IVA, XML FatturaPA) prive
 * di dipendenze dal database.
 */
abstract class TestCase extends XotBaseTestCase
{
    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            FiscalServiceProvider::class,
        ];
    }
}
