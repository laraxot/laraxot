<?php

declare(strict_types=1);

namespace Modules\Activity\Tests;

<<<<<<< HEAD
use Filament\Notifications\Notification;
=======
>>>>>>> 13cf1831 (.)
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Activity\Filament\Pages\ListLogActivities;
<<<<<<< HEAD
use Modules\Activity\Filament\Resources\ActivityResource;
=======
>>>>>>> 13cf1831 (.)
use Modules\Activity\Providers\ActivityServiceProvider;
use Modules\Activity\Providers\EventServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case for Activity module.
 *
 * Uses shared fixcity_data.sqlite (no RefreshDatabase / migrate:fresh).
<<<<<<< HEAD
 * prepareSharedSqliteForTesting() runs before transactions begin.
=======
 * prepareSharedFixcitySqliteForTesting() runs before transactions begin.
>>>>>>> 13cf1831 (.)
 *
 * @property ListLogActivities|null $page
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    public ?ListLogActivities $page = null;

    /**
     * Shared test data for Activity entity.
     *
     * @var array<string, mixed>
     */
    public array $activityData = [];

    /**
     * Shared test data for stored event entity.
     *
     * @var array<string, mixed>
     */
    public array $storedEventData = [];

    /**
     * Shared test data for snapshot entity.
     *
     * @var array<string, mixed>
     */
    public array $snapshotData = [];

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'activity', 'user'];

    protected function setUp(): void
    {
        $this->prepareSharedSqliteForTesting();

        parent::setUp();

        config(['auth.providers.users.model' => User::class]);
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            UserServiceProvider::class,
            ActivityServiceProvider::class,
            EventServiceProvider::class,
        ];
    }

    public function requirePage(): ListLogActivities
    {
        if ($this->page === null) {
            $this->fail('ListLogActivities page is not initialized.');
        }

        return $this->page;
    }
<<<<<<< HEAD

    /**
     * Builds a fresh ListLogActivities page instance exposing the
     * restore-notification helpers for coverage tests.
     *
     * Static so it can be called directly from within Pest test closures
     * (including nested describe()/test() closures) without relying on
     * $this, which PHPStan/Larastan cannot type-infer in that context.
     */
    public static function makeListLogActivitiesPage(): ListLogActivities
    {
        return new class() extends ListLogActivities
        {
            public static function getResource(): string
            {
                return ActivityResource::class;
            }

            public function exposeRestoreSuccess(): Notification
            {
                return $this->sendRestoreSuccessNotification();
            }

            public function exposeRestoreFailure(?string $message = null): Notification
            {
                return $this->sendRestoreFailureNotification($message);
            }
        };
    }
=======
>>>>>>> 13cf1831 (.)
}
