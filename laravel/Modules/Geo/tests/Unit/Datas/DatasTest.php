<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Datas;

use Modules\Geo\Datas\Elevation\ElevationData;
use Modules\Geo\Datas\Elevation\ElevationResultDTO;
use Modules\Geo\Datas\Geocoding\AddressData;
use Modules\Geo\Datas\Geocoding\GeocodingData;
use Modules\Geo\Datas\Geocoding\PlaceData;
use Modules\Geo\Datas\GeoData;
use Modules\Geo\Datas\Location\CoordinatesData;
use Modules\Geo\Datas\Location\IPLocationData;
use Modules\Geo\Datas\LocationData;
use Modules\Geo\Datas\LocationDTO;
use Modules\Geo\Datas\MapPlatforms\BingMapData;
use Modules\Geo\Datas\MapPlatforms\MapboxMapData;
use Modules\Geo\Datas\Routing\RouteData;
use Modules\Geo\Datas\Routing\TravelTimeData;
use Modules\Geo\Datas\TimeZoneData;
use Modules\Geo\Datas\UpdateCoordinatesResult;
use Modules\Geo\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('CoordinatesData can be instantiated', function () {
    Assert::assertTrue(class_exists(CoordinatesData::class));

    $coordinates = CoordinatesData::from(['lat' => 41.9028, 'lng' => 12.4964]);
    Assert::assertInstanceOf(CoordinatesData::class, $coordinates);
});

test('AddressData can be instantiated', function () {
    Assert::assertTrue(class_exists(AddressData::class));

    $address = AddressData::from(['city' => 'Roma']);
    Assert::assertInstanceOf(AddressData::class, $address);
});

test('LocationData can be instantiated', function () {
    Assert::assertTrue(class_exists(LocationData::class));

    $location = LocationData::from(['name' => 'Rome']);
    Assert::assertInstanceOf(LocationData::class, $location);
});

test('PlaceData can be instantiated', function () {
    Assert::assertTrue(class_exists(PlaceData::class));

    $place = PlaceData::from(['name' => 'Test Place']);
    Assert::assertInstanceOf(PlaceData::class, $place);
});

test('GeoData can be instantiated', function () {
    Assert::assertTrue(class_exists(GeoData::class));

    $geoData = GeoData::from([]);
    Assert::assertInstanceOf(GeoData::class, $geoData);
});

test('ElevationData can be instantiated', function () {
    Assert::assertTrue(class_exists(ElevationData::class));

    $elevationData = ElevationData::from([
        'elevation' => 100.0,
        'latitude' => 41.9028,
        'longitude' => 12.4964,
        'resolution' => 30.0,
    ]);
    Assert::assertInstanceOf(ElevationData::class, $elevationData);
});

test('ElevationResultDTO can be instantiated', function () {
    Assert::assertTrue(class_exists(ElevationResultDTO::class));

    $elevationResult = new ElevationResultDTO(
        elevation: 100.0,
        latitude: 41.9028,
        longitude: 12.4964
    );
    Assert::assertInstanceOf(ElevationResultDTO::class, $elevationResult);
});

test('LocationDTO can be instantiated', function () {
    Assert::assertTrue(class_exists(LocationDTO::class));

    $locationDTO = new LocationDTO(
        latitude: 41.9028,
        longitude: 12.4964,
        address: 'Test Address'
    );
    Assert::assertInstanceOf(LocationDTO::class, $locationDTO);
});

test('BingMapData can be instantiated', function () {
    Assert::assertTrue(class_exists(BingMapData::class));

    $bingMapData = new BingMapData([
        'point' => [
            'coordinates' => [12.4964, 41.9028],
        ],
        'address' => [
            'countryRegion' => 'Italy',
            'adminDistrict' => null,
            'adminDistrict2' => null,
            'locality' => 'Rome',
            'postalCode' => null,
            'addressLine' => null,
            'countryRegionIso2' => null,
            'neighborhood' => null,
            'houseNumber' => null,
        ],
    ]);
    Assert::assertInstanceOf(BingMapData::class, $bingMapData);
});

test('MapboxMapData can be instantiated', function () {
    Assert::assertTrue(class_exists(MapboxMapData::class));

    $mapboxMapData = new MapboxMapData([
        'center' => [12.4964, 41.9028],
        'text' => 'Rome',
        'address' => null,
        'context' => [
            'country' => 'Italy',
            'country_code' => null,
            'place' => 'Rome',
            'postcode' => null,
            'locality' => null,
            'region' => null,
            'neighborhood' => null,
        ],
    ]);
    Assert::assertInstanceOf(MapboxMapData::class, $mapboxMapData);
});

test('UpdateCoordinatesResult can be instantiated', function () {
    Assert::assertTrue(class_exists(UpdateCoordinatesResult::class));

    $updateResult = UpdateCoordinatesResult::from([
        'success' => true,
        'message' => 'Coordinates updated',
    ]);
    Assert::assertInstanceOf(UpdateCoordinatesResult::class, $updateResult);
});

test('RouteData can be instantiated', function () {
    Assert::assertTrue(class_exists(RouteData::class));

    $routeData = RouteData::from([
        'origin' => ['lat' => 41.9028, 'lng' => 12.4964],
        'destination' => ['lat' => 41.8931, 'lng' => 12.4778],
        'distance' => 1000.0,
    ]);
    Assert::assertInstanceOf(RouteData::class, $routeData);
});

test('TravelTimeData can be instantiated', function () {
    Assert::assertTrue(class_exists(TravelTimeData::class));

    $travelTimeData = TravelTimeData::from([
        'duration' => 1800,
        'distance' => 10000,
    ]);
    Assert::assertInstanceOf(TravelTimeData::class, $travelTimeData);
});

test('IPLocationData can be instantiated', function () {
    Assert::assertTrue(class_exists(IPLocationData::class));

    $ipLocationData = IPLocationData::from([
        'ip' => '8.8.8.8',
        'latitude' => 37.751,
        'longitude' => -97.822,
    ]);
    Assert::assertInstanceOf(IPLocationData::class, $ipLocationData);
});

test('GeocodingData can be instantiated', function () {
    Assert::assertTrue(class_exists(GeocodingData::class));

    $geocodingData = GeocodingData::from([
        'latitude' => 41.9028,
        'longitude' => 12.4964,
        'formatted_address' => 'Rome, Italy',
    ]);
    Assert::assertInstanceOf(GeocodingData::class, $geocodingData);
});

test('TimeZoneData can be instantiated', function () {
    Assert::assertTrue(class_exists(TimeZoneData::class));

    $timeZoneData = TimeZoneData::from([
        'timeZoneId' => 'Europe/Rome',
        'timeZoneName' => 'Central European Time',
        'rawOffset' => 3600,
        'dstOffset' => 3600,
    ]);
    Assert::assertInstanceOf(TimeZoneData::class, $timeZoneData);
});
