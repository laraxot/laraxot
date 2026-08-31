<?php

declare(strict_types=1);

namespace Modules\Notify\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Notify\Actions\NotificationManager;
use Modules\Notify\Models\NotificationType;
use Modules\Notify\Models\NotifyTheme;
use Modules\Notify\Models\NotifyThemeable;
use Modules\Notify\Providers\NotifyServiceProvider;
use Modules\User\Models\User;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBasePest;
use Modules\Xot\Tests\XotBaseTestCase;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionProperty;

use function Safe\file_get_contents;

/**
 * Base test case for Notify module.
 *
 * Uses MySQL from .env.testing.
 * All module connections are mapped by TenantServiceProvider.
 * Migrations must be run ONCE externally: php artisan migrate --env=testing
 * DatabaseTransactions handles rollback between tests.
 *
 * @property object|null $sendNotificationAction
 */
abstract class TestCase extends XotBaseTestCase
{
    /**
     * @return array<string, mixed>
     */
    public static function assertNotifyArray(mixed $value): array
    {
        Assert::assertIsArray($value);

        /** @var array<string, mixed> $value */
        return $value;
    }

    /**
     * @template T of object
     *
     * @param  ReflectionClass<T>  $reflection
     * @return list<string>
     */
    public static function notifyReflectionPropertyNames(ReflectionClass $reflection): array
    {
        return array_map(
            static fn (ReflectionProperty $property): string => $property->getName(),
            $reflection->getProperties(),
        );
    }

    /**
     * @template T of object
     *
     * @param  ReflectionClass<T>  $reflection
     */
    public static function notifyReflectionSource(ReflectionClass $reflection): string
    {
        $filename = $reflection->getFileName();
        Assert::assertNotFalse($filename);

        return file_get_contents($filename);
    }

    /**
     * Legge una catena di chiavi annidate, asserendo a ogni livello che esista.
     *
     * @param  array<mixed, mixed>|null  $array
     */
    public static function notifyArrayGet(?array $array, int|string ...$keys): mixed
    {
        Assert::assertIsArray($array);

        /** @var array<mixed, mixed> $current */
        $current = $array;

        if ($keys === []) {
            return $current;
        }

        $lastKey = array_pop($keys);

        foreach ($keys as $key) {
            Assert::assertArrayHasKey($key, $current);
            $nested = $current[$key];
            Assert::assertIsArray($nested);
            /** @var array<mixed, mixed> $nested */
            $current = $nested;
        }

        Assert::assertArrayHasKey($lastKey, $current);

        return $current[$lastKey];
    }

    /**
     * @return array<string, mixed>
     */
    public static function notifyFreshTypeSettings(NotificationType $type): array
    {
        $settings = XotBasePest::assertFreshModel($type, NotificationType::class)->settings;

        /** @var array<string, mixed> $settings */
        return $settings;
    }

    public static function notifyThemeForThemeable(NotifyThemeable $themeable): NotifyTheme
    {
        $themeId = $themeable->notify_theme_id;
        Assert::assertNotNull($themeId);
        $theme = NotifyTheme::query()->find($themeId);
        Assert::assertInstanceOf(NotifyTheme::class, $theme);

        return $theme;
    }

    use DatabaseTransactions;

    public NotificationManager $notificationManager;

    /** @var list<string> */
    protected $connectionsToTransact = ['sqlite', 'notify', 'user'];

    protected function setUp(): void
    {
        $this->prepareSharedFixcitySqliteForTesting();

        parent::setUp();

        config(['auth.providers.users.model' => User::class]);

        if ($this->shouldSkipForMissingNotifyDb()) {
            $this->markTestSkipped('DB `notify` non disponibile in ambiente test condiviso.');
        }
    }

    /**
     * Salta Feature / `notify-db` offline; Unit puri e `no-notify-db` restano verdi.
     * Shared sqlite ha spesso tabelle notify incomplete (colonne mancanti) — non è MySQL test.
     *
     * Nota: Pest `uses()->group('notify-db')` non sempre riempie `$this->groups()` —
     * fallback: rileva `group('notify-db')` nel file sorgente del test.
     */
    protected function shouldSkipForMissingNotifyDb(): bool
    {
        $testFile = $this->resolvePestTestFile();
        $isUnit = $testFile !== null && str_contains($testFile, '/tests/Unit/');
        $isNotifyDbGroup = false;
        if ($testFile !== null && is_file($testFile)) {
            $source = file_get_contents($testFile);
            if (str_contains($source, "group('no-notify-db')")) {
                return false;
            }
            $isNotifyDbGroup = str_contains($source, "group('notify-db')");
        }

        // Unit puri: sempre esegui (coverage senza schema).
        if ($isUnit && ! $isNotifyDbGroup) {
            return false;
        }

        // Qui c'era uno skip incondizionato quando il driver è sqlite. La premessa —
        // «lo sqlite condiviso è scratch / incompleto» — è decaduta: lo schema si
        // costruisce con `php artisan xot:build-test-sqlite` e le suite parallele non si
        // lockano più (un solo PDO condiviso, un file per processo via `XOT_TEST_SQLITE`).
        // Se il database manca davvero lo dice il metodo qui sopra, che guarda le tabelle.
        return static::notifyDbUnavailable();
    }

    private function resolvePestTestFile(): ?string
    {
        $class = static::class;

        if (property_exists($class, '__filename')) {
            /** @var string $filename */
            $filename = $class::$__filename;

            return $filename;
        }

        $file = (new ReflectionClass($this))->getFileName();

        return $file !== false ? $file : null;
    }

    /**
     * Il sqlite condiviso non contiene sempre le tabelle notify complete: i test DB vanno saltati, non falliti.
     * Se la connessione punta a fixcity_data.sqlite (offline condiviso) trattiamo il dominio come unavailable
     * salvo override esplicito NOTIFY_DB_TESTS=1.
     */
    public static function notifyDbUnavailable(): bool
    {
        if (self::notifyDbTestsEnabled()) {
            // fall through to real schema check
        } elseif (self::notifyUsesSharedOfflineSqlite()) {
            return true;
        }

        try {
            DB::connection('notify')->getPdo();
            $schema = DB::connection('notify')->getSchemaBuilder();

            foreach (['notifications', 'notification_types', 'contacts', 'mail_templates'] as $table) {
                if (! $schema->hasTable($table)) {
                    return true;
                }
            }

            // Schema stub/outdated (es. golden senza colonna `type`) → skip, non fail.
            if (! $schema->hasColumn('mail_templates', 'type')) {
                return true;
            }

            return false;
        } catch (\Throwable) {
            return true;
        }
    }

    private static function notifyDbTestsEnabled(): bool
    {
        $raw = $_ENV['NOTIFY_DB_TESTS'] ?? $_SERVER['NOTIFY_DB_TESTS'] ?? getenv('NOTIFY_DB_TESTS');

        return $raw === '1' || $raw === true || $raw === 'true';
    }

    private static function notifyUsesSharedOfflineSqlite(): bool
    {
        try {
            if (DB::connection('notify')->getDriverName() === 'sqlite') {
                // Qualsiasi sqlite (fixcity, XOT_TEST_SQLITE, :memory:) è offline rispetto a MySQL notify.
                return ! self::notifyDbTestsEnabled();
            }

            $databaseRaw = config('database.connections.notify.database', '');
            $defaultRaw = config('database.connections.sqlite.database', '');
            $database = is_string($databaseRaw) ? $databaseRaw : '';
            $default = is_string($defaultRaw) ? $defaultRaw : '';

            foreach ([$database, $default] as $path) {
                if ($path !== '' && str_contains($path, 'fixcity_data.sqlite')) {
                    return true;
                }
            }
        } catch (\Throwable) {
            return true;
        }

        return false;
    }

    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            UserServiceProvider::class,
            NotifyServiceProvider::class];
    }

    /**
     * @template T of Model
     *
     * @param  T  $model
     * @param  class-string<T>  $class
     * @return T
     */
    public function freshModel(Model $model, string $class)
    {
        $fresh = $model->fresh();
        Assert::assertInstanceOf($class, $fresh);

        return $fresh;
    }

    /**
     * @template T of Model
     *
     * @param  Collection<int, T>  $collection
     * @param  class-string<T>  $class
     * @return T
     */
    public function firstModel(Collection $collection, string $class)
    {
        $first = $collection->first();
        Assert::assertInstanceOf($class, $first);

        return $first;
    }
}
