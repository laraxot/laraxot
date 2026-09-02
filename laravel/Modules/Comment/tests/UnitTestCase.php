<?php

declare(strict_types=1);

namespace Modules\Comment\Tests;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Comment\Providers\CommentServiceProvider;
use Modules\Xot\Providers\XotServiceProvider;
use Modules\Xot\Tests\CreatesApplication;

/**
 * Lightweight TestCase for pure Comment unit tests (no database).
 */
abstract class UnitTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            XotServiceProvider::class,
            CommentServiceProvider::class,
        ];
    }
}
