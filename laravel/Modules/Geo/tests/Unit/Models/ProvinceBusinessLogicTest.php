<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Geo\Models\BaseModel;
use Modules\Geo\Models\Province;
use Modules\Xot\Models\Traits\HasXotFactory;
use PHPUnit\Framework\Assert;

use function Safe\class_uses;

use Sushi\Sushi;

uses(\Modules\Geo\Tests\TestCase::class);
describe('Province Business Logic', function () {
    test('province extends base model', function () {
        Assert::assertInstanceOf(BaseModel::class, new Province());
    });

    test('province has factory trait for testing', function () {
        $traits = class_uses(Province::class);

        Assert::assertArrayHasKey(HasXotFactory::class, $traits);
    });

    test('province uses sushi trait for in-memory data', function () {
        $traits = class_uses(Province::class);

        Assert::assertArrayHasKey(Sushi::class, $traits);
    });

    test('province has schema definition for geographic hierarchy', function () {
        $province = new Province();
        $reflection = new \ReflectionClass($province);
        $schemaProperty = $reflection->getProperty('schema');

        Assert::assertTrue($schemaProperty->isProtected());

        $schema = $schemaProperty->getValue($province);
        Assert::assertIsArray($schema);
        /* @var array<string, mixed> $schema */
        Assert::assertSame('integer', $schema['region_id']);
        Assert::assertSame('integer', $schema['id']);
        Assert::assertSame('string', $schema['name']);
    });

    test('province can get rows from comune data', function () {
        $province = new Province();
        $rows = $province->getRows();

        Assert::assertNotEmpty($rows);
    });

    test('province model can be instantiated without errors', function () {
        $province = new Province();

        Assert::assertInstanceOf(Province::class, $province);
        Assert::assertInstanceOf(BaseModel::class, $province);
    });

    test('province can be queried by name', function () {
        $query = Province::whereName('Milano');

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('province can be queried by region id', function () {
        $query = Province::whereRegionId(1);

        Assert::assertInstanceOf(Builder::class, $query);
    });
});
