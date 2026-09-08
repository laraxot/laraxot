<?php

declare(strict_types=1);

namespace Modules\Catalog\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\Catalog\Providers\CatalogServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Feature Catalog: condividono lo schema sqlite di `database/fixcity_data.sqlite`
 * (già con products/metel_* dopo migrate path Catalog). Senza
 * prepareSharedSqliteForTesting() phpunit forza :memory: vuoto → no such table.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    public static ?self $currentTest = null;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'xot'];

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            CatalogServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        $this->prepareSharedSqliteForTesting();

        // Schema PRIMA di DatabaseTransactions: altrimenti DDL sul sqlite file
        // resta nella transazione del test e sparisce al rollback.
        $this->ensureCatalogSchema();

        parent::setUp();

        self::$currentTest = $this;
    }

    protected function tearDown(): void
    {
        self::$currentTest = null;

        parent::tearDown();
    }

    /**
     * Idempotente: se lo schema Catalog manca sul fixture sqlite, applica solo
     * Modules/Catalog/database/migrations (mai migrate globale, mai --force).
     */
    private function ensureCatalogSchema(): void
    {
        if (Schema::connection('sqlite')->hasTable('products')
            && Schema::connection('sqlite')->hasTable('metel_catalogs')) {
            return;
        }

        Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--path' => 'Modules/Catalog/database/migrations',
            '--no-interaction' => true,
        ]);
    }
}
