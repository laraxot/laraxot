<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Geo\Database\Factories\RegionFactory;
use Modules\Geo\Models\BaseModel;
use Modules\Geo\Models\Region;
use Modules\Xot\Models\Traits\HasXotFactory;
use PHPUnit\Framework\Assert;

use function Safe\class_uses;

use Sushi\Sushi;

uses(\Modules\Geo\Tests\TestCase::class);
describe('Region Business Logic', function () {
    test('region extends base model', function () {
        Assert::assertInstanceOf(BaseModel::class, new Region());
    });

    test('region has factory trait for testing', function () {
        $traits = class_uses(Region::class);

        Assert::assertArrayHasKey(HasXotFactory::class, $traits);
    });

    test('region uses sushi trait for in-memory data', function () {
        $traits = class_uses(Region::class);

        Assert::assertArrayHasKey(Sushi::class, $traits);
    });

    test('region has correct key type configured', function () {
        $region = new Region();

        Assert::assertSame('integer', $region->getKeyType());
    });

    test('region has schema definition for geographic data', function () {
        $region = new Region();
        $reflection = new \ReflectionClass($region);
        $schemaProperty = $reflection->getProperty('schema');

        Assert::assertTrue($schemaProperty->isProtected());

        $schema = $schemaProperty->getValue($region);
        Assert::assertIsArray($schema);
        /* @var array<string, mixed> $schema */
        Assert::assertSame('integer', $schema['id']);
        Assert::assertSame('string', $schema['name']);
    });

    test('region can be created via factory', function () {
        $region = RegionFactory::new()->createOne();

        Assert::assertInstanceOf(Region::class, $region);
    });

    test('region model can be instantiated without errors', function () {
        $region = new Region();

        Assert::assertInstanceOf(Region::class, $region);
        Assert::assertInstanceOf(BaseModel::class, $region);
    });

    test('region can be queried by name', function () {
        $query = Region::whereName('Lombardia');

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('region can be queried by id', function () {
        $query = Region::whereId(1);

        Assert::assertInstanceOf(Builder::class, $query);
    });
});
