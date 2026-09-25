<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Datas;

use Modules\Geo\Datas\GoogleMaps\GoogleMapAddressComponentData;
use Modules\Geo\Datas\GoogleMaps\GoogleMapBoundsData;
use Modules\Geo\Datas\GoogleMaps\GoogleMapComponentData;
use Modules\Geo\Datas\GoogleMaps\GoogleMapGeometryData;
use Modules\Geo\Datas\GoogleMaps\GoogleMapLocationData;
use Modules\Geo\Datas\GoogleMaps\GoogleMapResponseData;
use Modules\Geo\Datas\GoogleMaps\GoogleMapResultData;
use Modules\Geo\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('GoogleMapGeometryData can be instantiated', function () {
    $geometry = GoogleMapGeometryData::from([
        'location' => [
            'lat' => 41.9028,
            'lng' => 12.4964,
        ],
        'location_type' => 'ROOFTOP',
        'viewport' => [
            'northeast' => ['lat' => 41.904, 'lng' => 12.498],
            'southwest' => ['lat' => 41.901, 'lng' => 12.495],
        ],
    ]);

    Assert::assertInstanceOf(GoogleMapGeometryData::class, $geometry);
});

test('GoogleMapAddressComponentData can be instantiated', function () {
    $component = GoogleMapAddressComponentData::from([
        'long_name' => 'Rome',
        'short_name' => 'Rome',
        'types' => ['locality', 'political'],
    ]);

    Assert::assertInstanceOf(GoogleMapAddressComponentData::class, $component);
});

test('GoogleMapResultData can be instantiated', function () {
    $result = GoogleMapResultData::from([
        'address_components' => [
            [
                'long_name' => 'Rome',
                'short_name' => 'Rome',
                'types' => ['locality', 'political'],
            ],
        ],
        'formatted_address' => 'Rome, Italy',
        'geometry' => [
            'location' => ['lat' => 41.9028, 'lng' => 12.4964],
            'location_type' => 'APPROXIMATE',
            'viewport' => [
                'northeast' => ['lat' => 41.904, 'lng' => 12.498],
                'southwest' => ['lat' => 41.901, 'lng' => 12.495],
            ],
        ],
        'place_id' => 'ChIJuQF2rcJ4t4kR2_1gJGeWQ0Y',
        'types' => ['locality', 'political'],
    ]);

    Assert::assertInstanceOf(GoogleMapResultData::class, $result);
});

test('GoogleMapResponseData can be instantiated', function () {
    $response = GoogleMapResponseData::from([
        'results' => [
            [
                'address_components' => [
                    [
                        'long_name' => 'Rome',
                        'short_name' => 'Rome',
                        'types' => ['locality', 'political'],
                    ],
                ],
                'formatted_address' => 'Rome, Italy',
                'geometry' => [
                    'location' => ['lat' => 41.9028, 'lng' => 12.4964],
                    'location_type' => 'APPROXIMATE',
                ],
                'place_id' => 'ChIJuQF2rcJ4t4kR2_1gJGeWQ0Y',
                'types' => ['locality', 'political'],
            ],
        ],
        'status' => 'OK',
    ]);

    Assert::assertInstanceOf(GoogleMapResponseData::class, $response);
});

test('GoogleMapBoundsData can be instantiated', function () {
    $bounds = GoogleMapBoundsData::from([
        'northeast' => ['lat' => 41.904, 'lng' => 12.498],
        'southwest' => ['lat' => 41.901, 'lng' => 12.495],
    ]);

    Assert::assertInstanceOf(GoogleMapBoundsData::class, $bounds);
});

test('GoogleMapLocationData can be instantiated', function () {
    $location = GoogleMapLocationData::from([
        'lat' => 41.9028,
        'lng' => 12.4964,
    ]);

    Assert::assertInstanceOf(GoogleMapLocationData::class, $location);
});

test('GoogleMapComponentData can be instantiated', function () {
    $component = GoogleMapComponentData::from([
        'name' => 'Rome',
        'type' => 'locality',
    ]);

    Assert::assertInstanceOf(GoogleMapComponentData::class, $component);
});
