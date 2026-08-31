<?php

declare(strict_types=1);

namespace Modules\Lang\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\Expectation;
use Mockery\MockInterface;
use Modules\Lang\Actions\SaveTransAction;
use Modules\Lang\Providers\LangServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;
use PHPUnit\Framework\Assert;

/**
 * Base test case for Lang module.
 *
 * Uses MySQL from .env.testing.
 * All module connections are mapped by TenantServiceProvider.
 * Migrations must be run ONCE externally: php artisan migrate --env=testing
 * DatabaseTransactions handles rollback between tests.
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'lang', 'user'];

    protected function setUp(): void
    {
        parent::setUp();

        $database = database_path('fixcity_data.sqlite');

        /** @var array<string, array<string, mixed>> $connections */
        $connections = config('database.connections', []);

        foreach (array_keys($connections) as $connection) {
            if ('sqlite' !== config("database.connections.{$connection}.driver")) {
                continue;
            }

            $this->app['config']->set("database.connections.{$connection}.database", $database);
            DB::purge($connection);
        }

        config(['auth.providers.users.model' => User::class]);
    }

    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            UserServiceProvider::class,
            LangServiceProvider::class,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function assertDatabaseHasRow(string $table, array $data, ?string $connection = null): void
    {
        $this->assertDatabaseHas($table, $data, $connection ?? 'lang');
    }

    /**
     * @param class-string<\Throwable> $exceptionClass
     */
    public function expectApplicationException(string $exceptionClass, ?string $message = null): void
    {
        $this->expectException($exceptionClass);
        if (null !== $message) {
            $this->expectThrowableMessage($message);
        }
    }

    /**
     * @template T of object
     *
     * @param class-string<T>                        $abstract
     * @param (\Closure(MockInterface&T): void)|null $callback
     *
     * @return MockInterface&T
     */
    public static function mockServiceStatic(string $abstract, ?\Closure $callback = null): MockInterface
    {
        /** @var MockInterface&T $mock */
        $mock = Mockery::mock($abstract);

        if (null !== $callback) {
            $callback($mock);
        }

        app()->instance($abstract, $mock);

        return $mock;
    }

    public static function mockExpectation(MockInterface $mock, string $method): Expectation
    {
        $mock->shouldReceive($method);
        $director = $mock->mockery_getExpectationsFor($method);
        Assert::assertNotNull($director);
        $expectation = $director->getExpectations()[0] ?? null;
        Assert::assertInstanceOf(Expectation::class, $expectation);

        return $expectation;
    }

    public static function mockExpects(MockInterface $mock, string $method): Expectation
    {
        return self::mockExpectation($mock, $method);
    }

    public static function mockAllows(MockInterface $mock, string $method): Expectation
    {
        return self::mockExpectation($mock, $method);
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    public static function createTranslationFile(string $filePath, array $translations): void
    {
        $phpContent = "<?php\n\nreturn ".var_export($translations, true).";\n";
        \Safe\file_put_contents($filePath, $phpContent);
    }

    public static function bindRealSaveTransAction(): void
    {
        app()->instance(SaveTransAction::class, new SaveTransAction());
    }

    public static function restoreSaveTransActionNoOp(): void
    {
        /** @var MockInterface&SaveTransAction $mock */
        $mock = Mockery::mock(SaveTransAction::class);
        $mock->shouldReceive('execute')->andReturnNull();
        app()->instance(SaveTransAction::class, $mock);
    }

    public static function forceSqliteTranslations(): void
    {
        $database = database_path('fixcity_data.sqlite');

        /** @var array<string, array<string, mixed>> $connections */
        $connections = config('database.connections', []);

        foreach (array_keys($connections) as $connection) {
            if ('sqlite' !== config("database.connections.{$connection}.driver")) {
                continue;
            }

            config(["database.connections.{$connection}.database" => $database]);
            DB::purge($connection);
        }
    }
}
