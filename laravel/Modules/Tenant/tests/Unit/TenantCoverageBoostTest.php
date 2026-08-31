<?php

declare(strict_types=1);

namespace Modules\Tenant\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Mockery;
use Mockery\MockInterface;
use Modules\Tenant\Actions\Config\FilterConfigStringKeysAction;
use Modules\Tenant\Actions\Config\GetTenantConfigArrayAction;
use Modules\Tenant\Actions\Config\GetTenantConfigNamesAction;
use Modules\Tenant\Actions\Config\GetTenantConfigPathAction;
use Modules\Tenant\Actions\Config\GetTenantFilePathAction;
use Modules\Tenant\Actions\Config\MergeRecursiveStringKeyConfigAction;
use Modules\Tenant\Actions\Config\ResolveTenantConfigValueAction;
use Modules\Tenant\Actions\Config\SaveTenantConfigAction;
use Modules\Tenant\Actions\Domains\GetDomainsArrayAction;
use Modules\Tenant\Actions\GetTenantNameAction;
use Modules\Tenant\Actions\Models\ResolveTenantModelClassAction;
use Modules\Tenant\Actions\Modules\GetTenantModulesAction;
use Modules\Tenant\Actions\Translations\TranslateTenantKeyAction;
use Modules\Tenant\Filament\Resources\DomainResource;
use Modules\Tenant\Filament\Resources\DomainResource\Schemas\DomainForm;
use Modules\Tenant\Filament\Resources\DomainResource\Schemas\DomainInfolist;
use Modules\Tenant\Filament\Resources\DomainResource\Tables\DomainsTable;
use Modules\Tenant\Models\BaseModelJsons;
use Modules\Tenant\Models\DatabaseConfig;
use Modules\Tenant\Models\Domain;
use Modules\Tenant\Models\Policies\DomainPolicy;
use Modules\Tenant\Models\Policies\TenantBasePolicy;
use Modules\Tenant\Models\Tenant;
use Modules\Tenant\Models\Traits\SushiToCsv;
use Modules\Tenant\Services\Config\ConfigResolverRegistry;
use Modules\Tenant\Services\Config\ConfigStringKeyFilter;
use Modules\Tenant\Services\Config\Contracts\ConfigResolverInterface;
use Modules\Tenant\Services\Config\Resolvers\DatabaseConfigResolver;
use Modules\Tenant\Services\Config\Resolvers\MorphMapConfigResolver;
use Modules\Tenant\Services\Config\Resolvers\StandardConfigResolver;
use Modules\Tenant\Services\TenantService;
use Modules\Tenant\Tests\TestCase;
use Modules\User\Models\SocialProvider;
use Modules\Xot\Contracts\UserContract;
use PHPUnit\Framework\Assert;

use function Safe\json_encode;

uses(\Modules\Tenant\Tests\TestCase::class);

afterEach(function (): void {
    Mockery::close();
});

describe('Tenant coverage boost — Config actions', function (): void {
    test('FilterConfigStringKeysAction keeps only string keys', function (): void {
        $result = app(FilterConfigStringKeysAction::class)->execute([
            'valid' => 1,
            0 => 'ignored',
            'nested' => ['a' => 1],
        ]);

        Assert::assertSame(['valid' => 1, 'nested' => ['a' => 1]], $result);
        Assert::assertArrayNotHasKey(0, $result);
    });

    test('MergeRecursiveStringKeyConfigAction merges nested configs', function (): void {
        $merged = app(MergeRecursiveStringKeyConfigAction::class)->execute(
            ['mail' => ['driver' => 'smtp', 'host' => 'localhost']],
            ['mail' => ['host' => 'tenant-host'], 1 => 'skip'],
        );

        $mail = $merged['mail'];
        Assert::assertIsArray($mail);
        Assert::assertSame('tenant-host', $mail['host']);
        Assert::assertSame('smtp', $mail['driver']);
    });
});

describe('Tenant coverage boost — Domain sushi', function (): void {
    test('Domain getRows loads from GetDomainsArrayAction', function (): void {
        TestCase::mockAppService(GetDomainsArrayAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => [
                ['id' => 1, 'name' => 'tenant.example.com'],
            ]]);
        });

        $rows = (new Domain())->getRows();

        Assert::assertCount(1, $rows);
        Assert::assertSame('tenant.example.com', $rows[0]['name']);
    });
});

describe('Tenant coverage boost — Models and resolvers', function (): void {
    test('Tenant isActive reflects is_active attribute', function (): void {
        $active = new Tenant(['is_active' => true]);
        $inactive = new Tenant(['is_active' => false]);

        Assert::assertTrue($active->isActive());
        Assert::assertFalse($inactive->isActive());
    });

    test('StandardConfigResolver resolves existing config keys', function (): void {
        config(['app' => ['name' => 'Base App', 'locale' => 'it']]);

        $resolver = new StandardConfigResolver();

        Assert::assertTrue($resolver->canResolve('app.name'));
        Assert::assertSame('Base App', $resolver->resolve('app.name'));

        expect(fn (): mixed => $resolver->resolve('app.missing', 'fallback'))
            ->toThrow(\Exception::class, 'Configuration key not found: app.missing');
    });
});

describe('Tenant coverage boost — TenantService facade', function (): void {
    test('TenantService delegates to actions', function (): void {
        TestCase::mockAppService(GetTenantNameAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => 'tenant-a']);
        });
        TestCase::mockAppService(GetTenantFilePathAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => '/tmp/tenant/settings.json']);
        });
        TestCase::mockAppService(ResolveTenantConfigValueAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => 'Tenant App']);
        });
        TestCase::mockAppService(GetTenantConfigPathAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => '/tmp/tenant/config/app.php']);
        });
        TestCase::mockAppService(GetTenantConfigArrayAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => ['name' => 'Tenant App']]);
        });
        TestCase::mockAppService(SaveTenantConfigAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => null]);
        });
        TestCase::mockAppService(GetTenantConfigNamesAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => [['id' => 1, 'name' => 'app']]]);
        });
        TestCase::mockAppService(GetTenantModulesAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => ['Tenant', 'Lang']]);
        });
        TestCase::mockAppService(TranslateTenantKeyAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => 'Benvenuto']);
        });
        TestCase::mockAppService(ResolveTenantModelClassAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => Tenant::class]);
        });

        Assert::assertSame('tenant-a', TenantService::getName());
        Assert::assertSame('/tmp/tenant/settings.json', TenantService::filePath('settings.json'));
        Assert::assertSame('Tenant App', TenantService::config('app.name'));
        Assert::assertSame('/tmp/tenant/config/app.php', TenantService::getConfigPath('app'));
        Assert::assertSame(['name' => 'Tenant App'], TenantService::getConfig('app'));
        TenantService::saveConfig('app', ['name' => 'Saved']);
        Assert::assertSame([['id' => 1, 'name' => 'app']], TenantService::getConfigNames());
        Assert::assertSame(['Tenant', 'Lang'], TenantService::allModules());
        Assert::assertSame('Benvenuto', TenantService::trans('welcome'));
        Assert::assertSame(Tenant::class, TenantService::modelClass('tenant'));
    });
});

describe('Tenant coverage boost — Filament and policy surface', function (): void {
    test('domain resource schemas and tables are executable', function (): void {
        $resourcePages = DomainResource::getPages();
        $formSchema = DomainForm::getFormSchema();
        $infolistSchema = DomainInfolist::getInfolistSchema();
        $tableColumns = (new DomainsTable())->getTableColumns();

        Assert::assertArrayHasKey('index', $resourcePages);
        Assert::assertArrayHasKey('title', $formSchema);
        Assert::assertArrayHasKey('id', $infolistSchema);
        Assert::assertArrayHasKey('updated_at', $tableColumns);
        Assert::assertSame([], DomainResource::getRelations());
    });

    test('tenant policies and config helpers enforce business rules', function (): void {
        /** @var MockInterface&UserContract $superAdmin */
        $superAdmin = Mockery::mock(UserContract::class);
        TestCase::expectMockery($superAdmin, 'hasRole')->with('super-admin')->andReturn(true);
        TestCase::expectMockery($superAdmin, 'hasPermissionTo')->andReturn(false);

        /** @var MockInterface&UserContract $editor */
        $editor = Mockery::mock(UserContract::class);
        TestCase::expectMockery($editor, 'hasRole')->with('super-admin')->andReturn(false);
        TestCase::expectMockery($editor, 'hasPermissionTo')->andReturnUsing(
            static fn (string $permission): bool => in_array($permission, ['domain.view', 'domain.update'], true),
        );

        $policy = new DomainPolicy();
        $domain = new Domain();
        $domain->exists = true;

        Assert::assertTrue((new class() extends TenantBasePolicy {})->before($superAdmin, 'viewAny'));
        Assert::assertTrue($policy->view($editor, $domain));
        Assert::assertTrue($policy->update($editor, $domain));
        Assert::assertFalse($policy->delete($editor, $domain));

        Assert::assertSame(['alpha' => 1], ConfigStringKeyFilter::onlyStringKeys(['alpha' => 1]));
        Assert::assertEquals(
            ['mail' => ['host' => 'tenant', 'driver' => 'smtp']],
            ConfigStringKeyFilter::mergeRecursive(['mail' => ['driver' => 'smtp']], ['mail' => ['host' => 'tenant']]),
        );
    });

    test('config resolver registry prefers matching resolvers and database config casts', function (): void {
        $registry = new ConfigResolverRegistry();

        $databaseResolver = $registry->findResolver('database');
        $fallbackResolver = $registry->findResolver('custom.key');

        Assert::assertInstanceOf(DatabaseConfigResolver::class, $databaseResolver);
        Assert::assertInstanceOf(StandardConfigResolver::class, $fallbackResolver);
        Assert::assertFalse((new MorphMapConfigResolver())->canResolve('morph_map'));

        $model = new DatabaseConfig();
        Assert::assertSame('integer', $model->getCasts()['port']);
        Assert::assertSame('array', $model->getCasts()['options']);

        $resolver = new class() implements ConfigResolverInterface
        {
            public function canResolve(string $key): bool
            {
                return $key === 'special.key';
            }

            public function resolve(string $key, string|int|array|null $default = null): float|int|string|array|null
            {
                return match ($key) {
                    'float' => 1.5,
                    'int' => 1,
                    'array' => [],
                    'null' => null,
                    default => 'ok',
                };
            }
        };

        Assert::assertTrue($resolver->canResolve('special.key'));
        Assert::assertSame('ok', $resolver->resolve('special.key'));
    });
});

describe('Tenant coverage boost — Sushi file traits', function (): void {
    test('sushi to jsons collects rows from tenant json files', function (): void {
        $baseDir = sys_get_temp_dir().'/tenant_jsons_'.uniqid();
        File::ensureDirectoryExists($baseDir.'/database/content/catalog');
        File::put($baseDir.'/database/content/catalog/1.json', json_encode(['name' => 'Alpha', 'meta' => ['x' => 1]]));

        TestCase::mockAppService(GetTenantFilePathAction::class, static function (MockInterface $mock) use ($baseDir): void {
            TestCase::expectMockery($mock, 'execute')->andReturnUsing(
                static fn (string $path): string => $baseDir.'/'.ltrim($path, '/'),
            );
        });

        $model = new class() extends BaseModelJsons
        {
            protected $table = 'catalog';

            /** @var array<string, mixed> */
            protected array $schema = ['name' => null, 'meta' => null];
        };

        $rows = $model->getSushiRows();

        Assert::assertCount(1, $rows);
        Assert::assertSame('Alpha', $rows[0]['name']);
        Assert::assertIsString($rows[0]['meta']);
        Assert::assertStringContainsString('"x": 1', $rows[0]['meta']);
    });

    test('sushi to csv reads rows and header from tenant csv file', function (): void {
        $baseDir = sys_get_temp_dir().'/tenant_csv_'.uniqid();
        File::ensureDirectoryExists($baseDir);
        File::put($baseDir.'/catalog.csv', "id,name\n1,Alpha\n2,Beta\n");

        TestCase::mockAppService(GetTenantFilePathAction::class, static function (MockInterface $mock) use ($baseDir): void {
            TestCase::expectMockery($mock, 'execute')->andReturnUsing(
                static fn (string $path): string => $baseDir.'/'.basename($path),
            );
        });

        $model = new class() extends Model
        {
            use SushiToCsv;

            protected $table = 'catalog';

            /** @var array<string, string> */
            protected array $schema = [
                'id' => 'integer',
                'name' => 'string',
            ];

            /** @return array<int, array<string, mixed>> */
            public function getRows(): array
            {
                return $this->getSushiRows();
            }
        };

        Assert::assertSame(['id', 'name'], $model->getCsvHeader());
        Assert::assertCount(2, $model->getSushiRows());
        Assert::assertSame('Alpha', $model->getSushiRows()[0]['name']);
    });

    test('sushi to php array normalizes tenant config rows', function (): void {
        TestCase::mockAppService(GetTenantConfigArrayAction::class, static function (MockInterface $mock): void {
            $mock->allows(['execute' => [
                ['name' => 'Alpha', 'meta' => null, 0 => 'skip'],
                ['name' => 'Beta', 'meta' => '{"x":1}'],
            ]]);
        });
        $model = new class() extends SocialProvider
        {
            protected $table = 'tenant_configs';

            /** @return array<int, array<string, mixed>> */
            public function getRows(): array
            {
                return $this->getSushiRows();
            }
        };

        $rows = $model->getSushiRows();

        Assert::assertCount(2, $rows);
        Assert::assertSame(['name' => 'Alpha', 'meta' => null], $rows[0]);
        Assert::assertSame('Beta', $rows[1]['name']);
        Assert::assertSame('{"x":1}', $rows[1]['meta']);
    });
});
