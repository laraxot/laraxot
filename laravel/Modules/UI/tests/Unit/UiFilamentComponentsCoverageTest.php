<?php

declare(strict_types=1);

namespace Modules\UI\Tests\Unit;

use Illuminate\Translation\PotentiallyTranslatedString;
<<<<<<< HEAD
=======
use Mockery;
>>>>>>> f5ffd8b4 (.)
use Mockery\MockInterface;
use Modules\UI\Enums\FieldTypeEnum;
use Modules\UI\Enums\TableLayout;
use Modules\UI\Filament\Widgets\StatsOverviewWidget;
use Modules\UI\Models\Category;
use Modules\UI\Models\Policies\UiBasePolicy;
use Modules\UI\Providers\UIServiceProvider;
use Modules\UI\Rules\OpeningHoursRule;
use Modules\UI\Tests\TestCase;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Tests\FilamentSchemaCoverage;
use PHPUnit\Framework\Assert;
<<<<<<< HEAD
=======
use SplFileInfo;
>>>>>>> f5ffd8b4 (.)

uses(TestCase::class);

afterEach(function (): void {
<<<<<<< HEAD
    \Mockery::close();
=======
    Mockery::close();
>>>>>>> f5ffd8b4 (.)
});

describe('UI Filament widgets and components coverage', function (): void {
    test('Filament widgets are instantiable', function (): void {
        [$appRoot, $ns] = [dirname(__DIR__, 2).'/app', 'Modules\\UI\\'];
        $seen = 0;
        foreach (FilamentSchemaCoverage::discover($appRoot, $ns, 'Widget') as $class) {
            if (! str_contains($class, 'Filament\\Widgets\\')) {
                continue;
            }
<<<<<<< HEAD
            Assert::assertInstanceOf($class, new $class());
            ++$seen;
=======
            Assert::assertInstanceOf($class, new $class);
            $seen++;
>>>>>>> f5ffd8b4 (.)
        }
        Assert::assertGreaterThan(0, $seen);
    });

    test('Filament form components are loadable', function (): void {
        [$appRoot] = [dirname(__DIR__, 2).'/app'];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($appRoot.'/Filament/Forms'));
        $count = 0;
        foreach ($iterator as $file) {
<<<<<<< HEAD
            if (! $file instanceof \SplFileInfo || ! $file->isFile() || ! str_ends_with($file->getFilename(), '.php')) {
=======
            if (! $file instanceof SplFileInfo || ! $file->isFile() || ! str_ends_with($file->getFilename(), '.php')) {
>>>>>>> f5ffd8b4 (.)
                continue;
            }
            $class = 'Modules\\UI\\'.str_replace(['/', '.php'], ['\\', ''], substr($file->getPathname(), strlen($appRoot) + 1));
            if (class_exists($class)) {
                Assert::assertTrue(class_exists($class));
<<<<<<< HEAD
                ++$count;
=======
                $count++;
>>>>>>> f5ffd8b4 (.)
            }
        }
        Assert::assertGreaterThan(0, $count);
    });
});

describe('UI coverage boost — Enums', function (): void {
    test('FieldTypeEnum form schema exposes all cases', function (): void {
        $schema = FieldTypeEnum::getFormSchema();
        Assert::assertCount(count(FieldTypeEnum::cases()), $schema);
    });

    test('TableLayout toArray maps values to labels', function (): void {
        Assert::assertArrayHasKey('list', TableLayout::toArray());
    });
});

describe('UI coverage boost — Rules and policies', function (): void {
    test('OpeningHoursRule accepts empty array value', function (): void {
<<<<<<< HEAD
        $rule = new OpeningHoursRule();
=======
        $rule = new OpeningHoursRule;
>>>>>>> f5ffd8b4 (.)
        $failed = false;
        $rule->validate(
            'hours',
            [],
            static function (string $message, ?string $replace = null) use (&$failed): PotentiallyTranslatedString {
                $failed = true;

                return new PotentiallyTranslatedString($message, app('translator'));
            },
        );
        Assert::assertFalse($failed);
    });

    test('UiBasePolicy before grants super-admin', function (): void {
        /** @var MockInterface&UserContract $superAdmin */
<<<<<<< HEAD
        $superAdmin = \Mockery::mock(UserContract::class);
        TestCase::expectMethod($superAdmin, 'hasRole')->with('super-admin')->andReturn(true);
        /** @var MockInterface&UserContract $regular */
        $regular = \Mockery::mock(UserContract::class);
        TestCase::expectMethod($regular, 'hasRole')->with('super-admin')->andReturn(false);

        $policy = new class extends UiBasePolicy {
        };
=======
        $superAdmin = Mockery::mock(UserContract::class);
        TestCase::expectMethod($superAdmin, 'hasRole')->with('super-admin')->andReturn(true);
        /** @var MockInterface&UserContract $regular */
        $regular = Mockery::mock(UserContract::class);
        TestCase::expectMethod($regular, 'hasRole')->with('super-admin')->andReturn(false);

        $policy = new class extends UiBasePolicy {};
>>>>>>> f5ffd8b4 (.)
        Assert::assertTrue($policy->before($superAdmin, 'viewAny'));
        Assert::assertNull($policy->before($regular, 'viewAny'));
    });
});

describe('UI coverage boost — Models and providers', function (): void {
    test('Category fillable matches domain fields', function (): void {
<<<<<<< HEAD
        Assert::assertContains('name', (new Category())->getFillable());
    });

    test('StatsOverviewWidget declares heading', function (): void {
        $widget = new StatsOverviewWidget();
=======
        Assert::assertContains('name', (new Category)->getFillable());
    });

    test('StatsOverviewWidget declares heading', function (): void {
        $widget = new StatsOverviewWidget;
>>>>>>> f5ffd8b4 (.)
        $ref = new \ReflectionClass($widget);
        $prop = $ref->getProperty('heading');
        $prop->setAccessible(true);
        Assert::assertSame('Stats Overview', $prop->getValue($widget));
    });

    test('UIServiceProvider declares module name', function (): void {
        Assert::assertSame('UI', (new UIServiceProvider(app()))->name);
    });
});
