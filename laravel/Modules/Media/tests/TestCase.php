<?php

declare(strict_types=1);

namespace Modules\Media\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Mockery;
use Mockery\Expectation;
use Mockery\MockInterface;
use Modules\Media\Providers\MediaServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBasePest;
use Modules\Xot\Tests\XotBaseTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Spatie\MediaLibrary\HasMedia;
use Spatie\QueueableAction\QueueableAction;

use function Safe\file_get_contents;

/**
 * Base test case for Media module.
 *
 * Uses MySQL from .env.testing.
 * All module connections are mapped by TenantServiceProvider.
 * Migrations must be run ONCE externally: php artisan migrate --env=testing
 * DatabaseTransactions handles rollback between tests.
 */
abstract class TestCase extends XotBaseTestCase
{
    /**
     * Riga `media` minima ma valida.
     *
     * Lo schema Spatie dichiara NOT NULL `model_type`, `name`, `manipulations`,
     * `custom_properties`, `generated_conversions` e `responsive_images` oltre ai campi
     * ovvi: un payload costruito a mano che li ometta viene rifiutato dal database, non
     * dal codice sotto test. Qui si riempiono una volta sola, e il chiamante sovrascrive
     * solo ciò che gli interessa davvero.
     *
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     */
    public static function mediaBasePayload(array $columns, Model $owner): array
    {
        $payload = [];

        foreach ([
            'model_type' => $owner::class,
            'model_id' => $owner->getKey(),
            'uuid' => (string) Str::uuid(),
            'collection_name' => 'default',
            'name' => 'test-file',
            'file_name' => 'test-file.pdf',
            'mime_type' => 'application/pdf',
            'disk' => 'public',
            'size' => 1024,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '[]',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ] as $column => $value) {
            $payload = self::mediaPayloadSet($payload, $columns, (string) $column, $value);
        }

        return $payload;
    }

    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['media', 'sqlite', 'xot'];

    protected function setUp(): void
    {
        $this->prepareSharedFixcitySqliteForTesting();

        parent::setUp();

        if ($this->shouldSkipForMissingMediaDb()) {
            $this->markTestSkipped('DB `media` non disponibile in ambiente test condiviso.');
        }
    }

    /**
     * Salta Feature / `media-db` offline; Unit puri e `no-media-db` restano verdi.
     *
     * Pest `uses()->group('…')` non sempre riempie i gruppi PHPUnit, e `groups()` è
     * `@internal`: si legge il `group('…')` dal file sorgente del test.
     */
    protected function shouldSkipForMissingMediaDb(): bool
    {
        if (! static::mediaDbUnavailable()) {
            return false;
        }

        $testFile = $this->resolvePestTestFile();

        if ($testFile !== null && is_file($testFile)) {
            try {
                $source = file_get_contents($testFile);
            } catch (\Throwable) {
                $source = '';
            }
            if (str_contains($source, "group('no-media-db')")) {
                return false;
            }
            if (str_contains($source, "group('media-db')")) {
                return true;
            }
        }

        // Unit: esegui offline; i test DB-dependent usano gruppo `media-db`.
        if ($testFile !== null && str_contains($testFile, '/tests/Unit/')) {
            return false;
        }

        return true;
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
     * Lo sqlite condiviso non contiene per forza le tabelle del modulo Media:
     * i test che toccano il DB vanno saltati, non falliti.
     * fixcity_data.sqlite = ambiente offline anche se `media` esiste.
     */
    public static function mediaDbUnavailable(): bool
    {
        try {
            $connection = DB::connection('media');
            $connection->getPdo();
            $database = (string) $connection->getDatabaseName();
            if (str_contains($database, 'fixcity_data.sqlite')) {
                return true;
            }

            return ! $connection->getSchemaBuilder()->hasTable('media');
        } catch (\Throwable) {
            return true;
        }
    }

    /**
     * @param  array<string, mixed>  $where
     */
    public function assertMediaTableHas(string $table, array $where, string $connection = 'media'): void
    {
        $this->assertDatabaseHas($table, $where, $connection);
    }

    /**
     * Controparte di {@see self::assertMediaTableHas()}: la riga non deve esistere.
     *
     * @param  array<string, mixed>  $where
     */
    public function assertMediaTableMissing(string $table, array $where, string $connection = 'media'): void
    {
        $this->assertDatabaseMissing($table, $where, $connection);
    }

    /**
     * Colonne della tabella `media`, filtrate e riindicizzate per l'inferenza PHPStan.
     *
     * @return array<int, string>
     */
    public static function mediaTableColumns(): array
    {
        $columns = Schema::getColumnListing('media');

        return array_values(array_filter(
            $columns,
            static fn (mixed $column): bool => is_string($column) && $column !== '',
        ));
    }

    /**
     * Aggiunge `$column` al payload solo se la tabella `media` ha davvero quella colonna:
     * lo schema varia fra installazioni e un insert cieco fallirebbe.
     *
     * @param  array<string, mixed>  $payload
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     */
    public static function mediaPayloadSet(array $payload, array $columns, string $column, mixed $value): array
    {
        if (in_array($column, $columns, true)) {
            $payload[$column] = $value;
        }

        return $payload;
    }

    /**
     * @param  class-string  $class
     */
    public static function assertMediaUsesQueueableAction(string $class): void
    {
        XotBasePest::assertListContains(
            QueueableAction::class,
            (new ReflectionClass($class))->getTraitNames(),
        );
    }

    /**
     * @param  class-string  $class
     */
    public static function assertMediaDeclaresStrictTypes(string $class): void
    {
        Assert::assertStringContainsString(
            'declare(strict_types=1);',
            XotBasePest::reflectionSource($class),
        );
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T&MockObject
     */
    public function makeTestMock(string $class): object
    {
        return $this->createMock($class);
    }

    /**
     * Mock HasMedia con addMedia/update: l'interfaccia Spatie non espone update(),
     * quindi PHPUnit createMock non basta — serve Mockery.
     *
     * @return HasMedia&MockInterface
     */
    public function makeHasMediaRecordMock(): HasMedia
    {
        /** @var HasMedia&MockInterface $mock */
        $mock = Mockery::mock(HasMedia::class);

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

    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            UserServiceProvider::class,
            MediaServiceProvider::class,
        ];
    }
}
