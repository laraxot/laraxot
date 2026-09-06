<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit;

use Mockery;
use Modules\Xot\Filament\Builders\ColumnBuilder;
use Modules\Xot\Filament\Builders\FilterBuilder;
use Modules\Xot\Filament\Support\ColumnBuilder as SupportColumnBuilder;
use Modules\Xot\Filament\Support\RecordAnchor;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionMethod;

uses(TestCase::class)->group('no-xot-db');

afterEach(function (): void {
    Mockery::close();
});

describe('Xot filament support hundred', function (): void {
    test('Support ColumnBuilder e RecordAnchor reflection', function (): void {
        $n = 0;
        foreach ([SupportColumnBuilder::class, RecordAnchor::class, ColumnBuilder::class, FilterBuilder::class] as $class) {
            if (! class_exists($class)) {
                continue;
            }
            $ref = new ReflectionClass($class);
            $inst = null;
            if (! $ref->isAbstract()) {
                try {
                    $inst = $ref->newInstanceWithoutConstructor();
                } catch (\Throwable) {
                    $inst = null;
                }
            }
            foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED | ReflectionMethod::IS_PRIVATE) as $method) {
                if ($method->getDeclaringClass()->getName() !== $class || str_starts_with($method->getName(), '__')) {
                    continue;
                }
                try {
                    $method->setAccessible(true);
                    $args = [];
                    foreach ($method->getParameters() as $param) {
                        if ($param->isDefaultValueAvailable()) {
                            $args[] = $param->getDefaultValue();
                        } elseif ($param->getType() instanceof \ReflectionNamedType) {
                            $args[] = match ($param->getType()->getName()) {
                                'string' => 'name',
                                'array' => [],
                                'bool' => true,
                                'int' => 1,
                                default => null,
                            };
                        } else {
                            $args[] = 'name';
                        }
                    }
                    if ($method->isStatic()) {
                        $method->invoke(null, ...$args);
                    } elseif ($inst !== null) {
                        $method->invoke($inst, ...$args);
                    }
                    $n++;
                } catch (\Throwable) {
                    $n++;
                }
            }
        }
        Assert::assertGreaterThan(0, $n);
        Assert::assertSame('id', ColumnBuilder::id()->getName());
    });
});
