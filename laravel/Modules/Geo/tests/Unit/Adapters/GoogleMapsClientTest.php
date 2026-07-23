<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Adapters;

use Modules\Geo\Adapters\GoogleMapsClient;
use PHPUnit\Framework\Assert;

it('instantiates the Google Maps client', function (): void {
    Assert::assertInstanceOf(GoogleMapsClient::class, new GoogleMapsClient());
});

it('exposes the Google Maps operations', function (): void {
    $reflection = new \ReflectionClass(GoogleMapsClient::class);
    Assert::assertTrue($reflection->hasMethod('reverseGeocode'));
    Assert::assertTrue($reflection->hasMethod('getDistanceMatrix'));
    Assert::assertTrue($reflection->hasMethod('getElevation'));
});
