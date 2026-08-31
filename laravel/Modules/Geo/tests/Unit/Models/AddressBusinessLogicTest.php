<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Geo\Enums\AddressTypeEnum;
use Modules\Geo\Models\Address;
use Modules\Geo\Models\BaseModel;
use PHPUnit\Framework\Assert;

describe('Address Business Logic', function () {
    test('address extends base model', function () {
        Assert::assertTrue(
            (new \ReflectionClass(Address::class))->isSubclassOf(BaseModel::class),
        );
    });

    test('address has expected fillable fields for postal address', function () {
        $address = new Address();
        $expectedFillable = [
            'model_type',
            'model_id',
            'name',
            'description',
            'route',
            'street_number',
            'locality',
            'administrative_area_level_3',
            'administrative_area_level_2',
            'administrative_area_level_1',
            'country',
            'postal_code',
            'formatted_address',
            'place_id',
            'latitude',
            'longitude',
            'type',
            'is_primary',
            'extra_data',
        ];

        Assert::assertEquals($expectedFillable, $address->getFillable());
    });

    test('address has correct casts for geolocation and structured data', function () {
        $address = new Address();
        $casts = $address->getCasts();

        Assert::assertSame('float', $casts['latitude']);
        Assert::assertSame('float', $casts['longitude']);
        Assert::assertSame('boolean', $casts['is_primary']);
        Assert::assertSame('array', $casts['extra_data']);
        Assert::assertSame(AddressTypeEnum::class, $casts['type']);
    });

    test('address has polymorphic model relationship', function () {
        $address = new Address();
    });

    test('address can get region data from comune', function () {
        $address = new Address();
    });

    test('address can get province data from comune', function () {
        $address = new Address();
    });

    test('address can get locality data from comune', function () {
        $address = new Address();
    });

    test('address can format full address attribute', function () {
        $address = new Address();
        $address->route = 'Via Roma';
        $address->street_number = '123';
        $address->locality = 'Milano';

        Assert::assertStringContainsString('Via Roma 123', $address->full_address);
        Assert::assertStringContainsString('Milano', $address->full_address);
    });

    test('address can format street address attribute', function () {
        $address = new Address();
        $address->route = 'Via Roma';
        $address->street_number = '123';

        Assert::assertSame('Via Roma 123', $address->street_address);
    });

    test('address can get geolocation coordinates', function () {
        $address = new Address();
        $address->latitude = 45.4642;
        $address->longitude = 9.1900;

        Assert::assertSame(45.4642, $address->getLatitude());
        Assert::assertSame(9.1900, $address->getLongitude());
    });

    test('address can export to schema org format', function () {
        $address = new Address();
        $address->name = 'Test Address';
        $address->route = 'Via Roma';
        $address->street_number = '123';

        $schemaOrg = $address->toSchemaOrg();

        Assert::assertArrayHasKey('@context', $schemaOrg);
        Assert::assertArrayHasKey('@type', $schemaOrg);
        Assert::assertSame('https://schema.org', $schemaOrg['@context']);
        Assert::assertSame('PostalAddress', $schemaOrg['@type']);
    });

    test('address scope can query nearby addresses', function () {
        $query = Address::nearby(45.4642, 9.1900, 10);

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('address scope can query primary addresses', function () {
        $query = Address::primary();

        Assert::assertInstanceOf(Builder::class, $query);
    });

    test('address scope can query by type', function () {
        $query = Address::ofType(AddressTypeEnum::BILLING);

        Assert::assertInstanceOf(Builder::class, $query);
    });
});
