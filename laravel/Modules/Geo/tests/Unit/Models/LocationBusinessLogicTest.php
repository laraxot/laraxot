<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Geo\Models\BaseModel;
use Modules\Geo\Models\Location;
use PHPUnit\Framework\Assert;

use function Safe\class_uses;

describe('Location Business Logic', function () {
    test('location extends base model', function () {
        $parent = (new \ReflectionClass(Location::class))->getParentClass();
        Assert::assertInstanceOf(\ReflectionClass::class, $parent);
        Assert::assertSame(BaseModel::class, $parent->getName());
    });

    test('location has factory trait for testing', function () {
        $traits = class_uses(Location::class);

        Assert::assertArrayHasKey(HasFactory::class, $traits);
    });

    test('location can be queried within distance scope', function () {
        $query = Location::withinDistance(45.4642, 9.1900, 10.0);

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('location has geographic coordinate properties', function () {
        $location = new Location();
        $location->lat = 45.4642;
        $location->lng = 9.1900;

        Assert::assertSame(45.4642, $location->lat);
        Assert::assertSame(9.1900, $location->lng);
    });

    test('location can store address components', function () {
        $location = new Location();
        $location->street = 'Via Roma 123';
        $location->city = 'Milano';
        $location->state = 'Lombardia';
        $location->zip = '20121';

        Assert::assertSame('Via Roma 123', $location->street);
        Assert::assertSame('Milano', $location->city);
        Assert::assertSame('Lombardia', $location->state);
        Assert::assertSame('20121', $location->zip);
    });

    test('location has processing status tracking', function () {
        $location = new Location();
        $location->processed = true;

        Assert::assertSame(true, $location->processed);
    });

    test('location can store formatted address', function () {
        $location = new Location();
        $location->formatted_address = 'Via Roma 123, 20121 Milano MI, Italy';

        Assert::assertSame('Via Roma 123, 20121 Milano MI, Italy', $location->formatted_address);
    });

    test('location can be queried by city', function () {
        $query = Location::whereCity('Milano');

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('location can be queried by coordinates', function () {
        $query = Location::whereLat(45.4642)->whereLng(9.1900);

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('location can be queried by processing status', function () {
        $query = Location::whereProcessed(true);

        Assert::assertInstanceOf(Builder::class, $query);
    });
});
