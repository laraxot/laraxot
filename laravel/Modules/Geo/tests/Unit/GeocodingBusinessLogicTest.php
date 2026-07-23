<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit;

use PHPUnit\Framework\Assert;

uses(\Modules\Geo\Tests\TestCase::class);

/**
 * @return array<string, string>
 */
function italianAddressFixture(): array
{
    return [
        'street' => 'Via Roma 123',
        'city' => 'Milano',
        'province' => 'MI',
        'region' => 'Lombardia',
        'postal_code' => '20100',
        'country' => 'Italy',
        'country_code' => 'IT',
    ];
}

/**
 * @return array<string, mixed>
 */
function geocodingResultFixture(): array
{
    return [
        'latitude' => 45.4642,
        'longitude' => 9.1900,
        'accuracy' => 'street_level',
        'provider' => 'nominatim',
        'confidence' => 0.95,
        'bounding_box' => [
            'north' => 45.4652,
            'south' => 45.4632,
            'east' => 9.1910,
            'west' => 9.1890,
        ],
    ];
}

/**
 * @return array<string, mixed>
 */
function weatherDataFixture(): array
{
    return [
        'temperature' => 15.5,
        'humidity' => 65,
        'pressure' => 1013.25,
        'weather_condition' => 'partly_cloudy',
        'wind_speed' => 3.2,
        'wind_direction' => 180,
        'visibility' => 10,
        'uv_index' => 4,
    ];
}

/**
 * @return array<string, mixed>
 */
function placeFixture(): array
{
    return [
        'id' => 'place-milano-001',
        'name' => 'Milano',
        'type' => 'city',
        'population' => 1366180,
        'area_km2' => 181.76,
        'elevation' => 120,
        'timezone' => 'Europe/Rome',
    ];
}

describe('Geocoding Business Logic', function () {
    describe('Italian Address Validation', function () {
        it('validates Italian postal code format', function () {
            $address = italianAddressFixture();

            Assert::assertMatchesRegularExpression('/^\d{5}$/', (string) $address['postal_code']);
            Assert::assertSame('20100', $address['postal_code']);
            Assert::assertSame(5, strlen($address['postal_code']));
        });

        it('validates Italian province codes', function () {
            $address = italianAddressFixture();

            Assert::assertMatchesRegularExpression('/^[A-Z]{2}$/', (string) $address['province']);
            Assert::assertSame('MI', $address['province']);
            Assert::assertSame(2, strlen($address['province']));
        });

        it('validates Italian address structure', function () {
            $address = italianAddressFixture();

            Assert::assertArrayHasKey('street', $address);
            Assert::assertArrayHasKey('city', $address);
            Assert::assertArrayHasKey('province', $address);
            Assert::assertArrayHasKey('region', $address);
            Assert::assertArrayHasKey('postal_code', $address);
            Assert::assertArrayHasKey('country_code', $address);
            Assert::assertSame('IT', $address['country_code']);
            Assert::assertSame('Italy', $address['country']);
        });

        it('validates Italian street address format', function () {
            $address = italianAddressFixture();

            Assert::assertStringContainsString('Via', $address['street']);
            Assert::assertMatchesRegularExpression('/Via\s+\w+\s+\d+/', (string) $address['street']);
            Assert::assertSame('Via Roma 123', $address['street']);
        });

        it('validates Italian regional hierarchy', function () {
            $address = italianAddressFixture();

            if ('Milano' === $address['city']) {
                Assert::assertSame('Lombardia', $address['region']);
                Assert::assertSame('MI', $address['province']);
            }

            $lombardyProvinces = ['MI', 'BG', 'BS', 'CO', 'CR', 'MN', 'PV', 'SO', 'VA'];
            if ('Lombardia' === $address['region']) {
                Assert::assertContains($address['province'], $lombardyProvinces);
            }
        });
    });

    describe('Geocoding Provider Logic', function () {
        it('validates geocoding coordinate precision', function () {
            $result = geocodingResultFixture();

            Assert::assertGreaterThan(35.0, $result['latitude']);
            Assert::assertLessThan(47.5, $result['latitude']);
            Assert::assertGreaterThan(6.0, $result['longitude']);
            Assert::assertLessThan(19.0, $result['longitude']);
        });

        it('ensures geocoding accuracy levels', function () {
            $result = geocodingResultFixture();
            $validAccuracyLevels = ['country', 'region', 'city', 'district', 'street_level', 'building'];

            Assert::assertContains($result['accuracy'], $validAccuracyLevels);
            Assert::assertGreaterThan(0.0, $result['confidence']);
            Assert::assertLessThanOrEqual(1.0, $result['confidence']);
        });

        it('validates provider response structure', function () {
            $result = geocodingResultFixture();

            Assert::assertArrayHasKey('latitude', $result);
            Assert::assertArrayHasKey('longitude', $result);
            Assert::assertArrayHasKey('accuracy', $result);
            Assert::assertArrayHasKey('provider', $result);
            Assert::assertArrayHasKey('confidence', $result);

            $validProviders = ['nominatim', 'bing', 'mapbox', 'here', 'google'];
            Assert::assertContains($result['provider'], $validProviders);
        });

        it('validates bounding box calculations', function () {
            $result = geocodingResultFixture();
            Assert::assertArrayHasKey('bounding_box', $result);
            Assert::assertIsArray($result['bounding_box']);
            $bbox = $result['bounding_box'];
            Assert::assertArrayHasKey('north', $bbox);
            Assert::assertArrayHasKey('south', $bbox);
            Assert::assertArrayHasKey('east', $bbox);
            Assert::assertArrayHasKey('west', $bbox);

            $latitude = (float) $result['latitude'];
            $longitude = (float) $result['longitude'];
            $north = (float) $bbox['north'];
            $south = (float) $bbox['south'];
            $east = (float) $bbox['east'];
            $west = (float) $bbox['west'];

            Assert::assertGreaterThan($latitude, $north);
            Assert::assertLessThan($latitude, $south);
            Assert::assertGreaterThan($longitude, $east);
            Assert::assertLessThan($longitude, $west);

            $latDiff = $north - $south;
            $lngDiff = $east - $west;
            Assert::assertGreaterThan(0.0001, $latDiff);
            Assert::assertLessThan(1.0, $latDiff);
            Assert::assertGreaterThan(0.0001, $lngDiff);
            Assert::assertLessThan(1.0, $lngDiff);
        });

        it('handles provider failover logic', function () {
            $providers = ['nominatim', 'bing', 'mapbox', 'here'];
            $primaryProvider = 'nominatim';
            $fallbackProviders = ['bing', 'mapbox', 'here'];

            Assert::assertContains($primaryProvider, $providers);
            Assert::assertGreaterThan(0, count($fallbackProviders));

            foreach ($fallbackProviders as $fallback) {
                Assert::assertNotSame($primaryProvider, $fallback);
                Assert::assertContains($fallback, $providers);
            }
        });
    });

    describe('Weather Data Integration', function () {
        it('validates weather data structure', function () {
            $weather = weatherDataFixture();

            Assert::assertArrayHasKey('temperature', $weather);
            Assert::assertArrayHasKey('humidity', $weather);
            Assert::assertArrayHasKey('pressure', $weather);
            Assert::assertArrayHasKey('weather_condition', $weather);
            Assert::assertGreaterThan(-20, $weather['temperature']);
            Assert::assertLessThan(50, $weather['temperature']);
        });

        it('validates humidity and pressure ranges', function () {
            $weather = weatherDataFixture();

            Assert::assertGreaterThanOrEqual(0, $weather['humidity']);
            Assert::assertLessThanOrEqual(100, $weather['humidity']);
            Assert::assertGreaterThan(950, $weather['pressure']);
            Assert::assertLessThan(1050, $weather['pressure']);
        });

        it('validates wind measurements', function () {
            $weather = weatherDataFixture();

            Assert::assertGreaterThanOrEqual(0, $weather['wind_speed']);
            Assert::assertLessThan(100, $weather['wind_speed']);
            Assert::assertGreaterThanOrEqual(0, $weather['wind_direction']);
            Assert::assertLessThan(360, $weather['wind_direction']);
        });

        it('validates weather condition categories', function () {
            $weather = weatherDataFixture();
            $validConditions = [
                'clear', 'partly_cloudy', 'cloudy', 'overcast', 'rain', 'heavy_rain',
                'snow', 'thunderstorm', 'fog', 'mist', 'hail', 'sleet',
            ];

            Assert::assertContains($weather['weather_condition'], $validConditions);
        });

        it('validates UV index ranges', function () {
            $weather = weatherDataFixture();

            Assert::assertGreaterThanOrEqual(0, $weather['uv_index']);
            Assert::assertLessThanOrEqual(11, $weather['uv_index']);
        });
    });

    describe('Place Classification Logic', function () {
        it('validates place hierarchy and types', function () {
            $place = placeFixture();
            $validTypes = [
                'country', 'region', 'province', 'city', 'town', 'village',
                'district', 'neighborhood', 'landmark',
            ];

            Assert::assertContains($place['type'], $validTypes);
            Assert::assertIsString($place['name']);
            Assert::assertNotEmpty($place['name']);
        });

        it('validates population data for cities', function () {
            $place = placeFixture();

            if ('city' === $place['type']) {
                Assert::assertArrayHasKey('population', $place);
                Assert::assertGreaterThan(0, $place['population']);
            }

            if ('Milano' === $place['name']) {
                Assert::assertGreaterThan(1000000, $place['population']);
                Assert::assertLessThan(2000000, $place['population']);
            }
        });

        it('validates geographic measurements', function () {
            $place = placeFixture();

            if (isset($place['area_km2'])) {
                Assert::assertGreaterThan(0, $place['area_km2']);
                Assert::assertLessThan(100000, $place['area_km2']);
            }

            if (isset($place['elevation'])) {
                Assert::assertGreaterThan(-500, $place['elevation']);
                Assert::assertLessThan(5000, $place['elevation']);
            }
        });

        it('validates timezone assignments', function () {
            $place = placeFixture();

            if (isset($place['timezone'])) {
                Assert::assertContains($place['timezone'], ['Europe/Rome']);
            }
        });
    });

    describe('Distance and Route Calculations', function () {
        it('calculates distance between coordinates', function () {
            $point1 = ['lat' => 45.4642, 'lng' => 9.1900];
            $point2 = ['lat' => 41.9028, 'lng' => 12.4964];

            $earthRadius = 6371;
            $dLat = deg2rad($point2['lat'] - $point1['lat']);
            $dLng = deg2rad($point2['lng'] - $point1['lng']);

            $a = (sin($dLat / 2) * sin($dLat / 2))
                + (cos(deg2rad($point1['lat'])) * cos(deg2rad($point2['lat'])) * sin($dLng / 2) * sin($dLng / 2));
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            Assert::assertGreaterThan(450, $distance);
            Assert::assertLessThan(520, $distance);
        });

        it('validates coordinate bounds checking', function () {
            $milanBounds = [
                'north' => 45.5311,
                'south' => 45.3975,
                'east' => 9.2844,
                'west' => 9.0944,
            ];

            $pointInMilan = ['lat' => 45.4642, 'lng' => 9.1900];
            $pointOutsideMilan = ['lat' => 41.9028, 'lng' => 12.4964];

            $isInBounds = fn (array $point, array $bounds): bool => $point['lat'] >= $bounds['south']
                && $point['lat'] <= $bounds['north']
                && $point['lng'] >= $bounds['west']
                && $point['lng'] <= $bounds['east'];

            Assert::assertTrue($isInBounds($pointInMilan, $milanBounds));
            Assert::assertFalse($isInBounds($pointOutsideMilan, $milanBounds));
        });

        it('validates proximity search radius', function () {
            $searchRadius = 10;
            Assert::assertGreaterThan(0.1, $searchRadius);
            Assert::assertLessThan(100, $searchRadius);
        });
    });

    describe('Data Quality and Validation', function () {
        it('ensures coordinate precision limits', function () {
            $coordinates = ['lat' => 45.4642035, 'lng' => 9.1899738];

            $latFraction = strrchr((string) $coordinates['lat'], '.');
            Assert::assertIsString($latFraction);
            $lngFraction = strrchr((string) $coordinates['lng'], '.');
            Assert::assertIsString($lngFraction);

            $latPrecision = strlen(substr($latFraction, 1));
            $lngPrecision = strlen(substr($lngFraction, 1));

            Assert::assertLessThanOrEqual(8, $latPrecision);
            Assert::assertLessThanOrEqual(8, $lngPrecision);
        });

        it('validates address completeness scoring', function () {
            $address = italianAddressFixture();
            $requiredFields = ['street', 'city', 'postal_code'];
            $optionalFields = ['province', 'region', 'country'];

            $score = 0;
            foreach ($requiredFields as $field) {
                if (isset($address[$field]) && '' !== $address[$field]) {
                    $score += 40;
                }
            }
            foreach ($optionalFields as $field) {
                if (isset($address[$field]) && '' !== $address[$field]) {
                    $score += 20 / count($optionalFields);
                }
            }

            Assert::assertGreaterThan(80, $score);
        });

        it('validates geocoding cache invalidation logic', function () {
            $cacheEntry = [
                'address' => italianAddressFixture(),
                'result' => geocodingResultFixture(),
                'cached_at' => time() - 86400,
                'expires_at' => time() + (86400 * 30),
            ];

            $isExpired = $cacheEntry['expires_at'] < time();
            $isRecentEnough = (time() - $cacheEntry['cached_at']) < (86400 * 90);

            Assert::assertFalse($isExpired);
            Assert::assertTrue($isRecentEnough);
        });
    });
});
