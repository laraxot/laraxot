<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\WhatsApp\Providers\WhatsAppServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    public static ?self $currentTest = null;

    public int $userId = 1;

    protected function setUp(): void
    {
        

        parent::setUp();

        self::$currentTest = $this;
    }

    protected function tearDown(): void
    {
        self::$currentTest = null;

        parent::tearDown();
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            WhatsAppServiceProvider::class,
        ];
    }
}
