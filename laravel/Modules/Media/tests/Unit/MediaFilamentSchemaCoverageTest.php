<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Unit;

use Modules\Media\Tests\TestCase;
use Modules\Xot\Tests\FilamentSchemaCoverage;
use PHPUnit\Framework\Assert;

use function Safe\glob;

uses(TestCase::class)->group('no-media-db');

/**
 * @return array{string, string} radice `app/` del modulo e namespace corrispondente
 */
/** @return list{string, string} */
function mediaFilamentContext(): array
{
    return [dirname(__DIR__, 2).'/app', 'Modules\\Media\\'];
}

describe('Media Filament schema coverage', function (): void {
    test('all form schemas execute getFormSchema', function (): void {
        [$appRoot, $ns] = mediaFilamentContext();
        FilamentSchemaCoverage::testAllForms($appRoot, $ns);
        Assert::assertNotEmpty(FilamentSchemaCoverage::discover($appRoot, $ns, 'Form'));
    });

    test('all table classes execute getTableColumns', function (): void {
        [$appRoot, $ns] = mediaFilamentContext();
        FilamentSchemaCoverage::testAllTables($appRoot, $ns);
        Assert::assertNotEmpty(FilamentSchemaCoverage::discover($appRoot, $ns, 'Table'));
    });

    test('all infolist schemas execute getInfolistSchema', function (): void {
        [$appRoot, $ns] = mediaFilamentContext();
        FilamentSchemaCoverage::testAllInfolists($appRoot, $ns);
    });

    test('all resources expose model and pages', function (): void {
        [$appRoot, $ns] = mediaFilamentContext();
        FilamentSchemaCoverage::testAllResources($appRoot, $ns);
    });

    test('all list pages expose table columns', function (): void {
        [$appRoot, $ns] = mediaFilamentContext();
        FilamentSchemaCoverage::testAllListPages($appRoot, $ns);
    });
});

describe('Media enum and provider coverage', function (): void {
    test('enums expose cases and labels', function (): void {
        [$appRoot, $ns] = mediaFilamentContext();
        foreach (glob($appRoot.'/Enums/*.php') as $file) {
            if (! is_string($file)) {
                continue;
            }
            $class = $ns.str_replace(['/', '.php'], ['\\', ''], substr($file, strlen($appRoot) + 1));
            if (! enum_exists($class)) {
                continue;
            }
            Assert::assertNotEmpty($class::cases());
        }
    });

    test('service providers declare module name', function (): void {
        [$appRoot, $ns] = mediaFilamentContext();
        foreach (glob($appRoot.'/Providers/*ServiceProvider.php') as $file) {
            if (! is_string($file)) {
                continue;
            }
            $class = $ns.str_replace(['/', '.php'], ['\\', ''], substr($file, strlen($appRoot) + 1));
            if (! class_exists($class)) {
                continue;
            }
            $provider = new $class(app());
            if (property_exists($provider, 'name')) {
                Assert::assertSame('Media', $provider->name);
            }
        }
    });
});
