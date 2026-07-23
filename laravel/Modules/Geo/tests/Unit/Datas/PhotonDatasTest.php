<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Datas;

use Modules\Geo\Datas\Photon\PhotonAddressData;
use Modules\Geo\Datas\Photon\PhotonFeatureData;
use Modules\Geo\Datas\Photon\PhotonPropertiesData;
use Modules\Geo\Datas\Photon\PhotonResponseData;
use Modules\Geo\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('PhotonAddressData can be instantiated', function (): void {
    $address = PhotonAddressData::from([
        'city' => 'Rome',
        'country' => 'Italy',
    ]);
    Assert::assertInstanceOf(PhotonAddressData::class, $address);
});

test('PhotonFeatureData can be instantiated', function (): void {
    $feature = PhotonFeatureData::from([
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [12.4964, 41.9028],
        ],
        'properties' => [
            'name' => 'Rome',
            'city' => 'Rome',
            'country' => 'Italy',
        ],
    ]);
    Assert::assertInstanceOf(PhotonFeatureData::class, $feature);
});

test('PhotonPropertiesData can be instantiated', function (): void {
    $properties = PhotonPropertiesData::from([
        'name' => 'Rome',
        'city' => 'Rome',
        'country' => 'Italy',
    ]);
    Assert::assertInstanceOf(PhotonPropertiesData::class, $properties);
});

test('PhotonResponseData can be instantiated', function (): void {
    $response = PhotonResponseData::from([
        'features' => [
            [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [12.4964, 41.9028],
                ],
                'properties' => [
                    'name' => 'Rome',
                    'city' => 'Rome',
                    'country' => 'Italy',
                ],
            ],
        ],
    ]);
    Assert::assertInstanceOf(PhotonResponseData::class, $response);
});
