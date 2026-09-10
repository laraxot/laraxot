<?php

declare(strict_types=1);

namespace Modules\Wts\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\User\Models\Role;
use Modules\User\Models\User;
use Modules\Wts\Providers\WtsServiceProvider;
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
        Role::findOrCreate('wts');
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),

            WtsServiceProvider::class,
        ];
    }

    /**
     * Create a user instance with proper type narrowing for PHPStan.
     *
     * Factory() return type resolution is a known Larastan limitation.
     * This method wraps it to provide type-safe usage throughout tests.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function createUser(array $attributes = []): User
    {
        /** @phpstan-ignore-next-line method.nonObject, return.type */
        return User::factory()->create($attributes);
    }
}
