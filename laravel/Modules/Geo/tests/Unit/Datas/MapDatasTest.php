<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Datas;

use Modules\Geo\Datas\Map\IconData;
use Modules\Geo\Datas\Map\MarkerData;
use Modules\Geo\Datas\Map\PositionData;
use Modules\Geo\Datas\Map\SizeData;
use PHPUnit\Framework\Assert;

test('IconData can be instantiated', function (): void {
    $icon = IconData::from([
        'url' => 'https://example.com/icon.png',
        'size' => ['width' => 32, 'height' => 32],
    ]);
    Assert::assertInstanceOf(IconData::class, $icon);
});

test('MarkerData can be instantiated', function (): void {
    $marker = MarkerData::from([
        'position' => ['lat' => 41.9028, 'lng' => 12.4964],
        'title' => 'Test Marker',
        'icon' => ['url' => 'https://example.com/icon.png'],
    ]);
    Assert::assertInstanceOf(MarkerData::class, $marker);
});

test('PositionData can be instantiated', function (): void {
    $position = PositionData::from([
        'lat' => 41.9028,
        'lng' => 12.4964,
    ]);
    Assert::assertInstanceOf(PositionData::class, $position);
});

test('SizeData can be instantiated', function (): void {
    $size = SizeData::from([
        'width' => 100,
        'height' => 100,
    ]);
    Assert::assertInstanceOf(SizeData::class, $size);
});
