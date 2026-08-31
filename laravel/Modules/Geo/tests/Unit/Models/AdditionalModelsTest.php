<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Modules\Geo\Models\County;
use Modules\Geo\Models\GeoNamesCap;
use Modules\Geo\Models\Locality;
use Modules\Geo\Models\Place;
use Modules\Geo\Models\PlaceType;
use Modules\Geo\Models\State;
use PHPUnit\Framework\Assert;

test('State model can be instantiated', function () {
    $state = new State();

    Assert::assertInstanceOf(State::class, $state);
});

test('County model can be instantiated', function () {
    $county = new County();

    Assert::assertInstanceOf(County::class, $county);
});

test('Locality model can be instantiated', function () {
    $locality = new Locality();

    Assert::assertInstanceOf(Locality::class, $locality);
});

test('Place model can be instantiated', function () {
    $place = new Place();

    Assert::assertInstanceOf(Place::class, $place);
});

test('PlaceType model can be instantiated', function () {
    $placeType = new PlaceType();

    Assert::assertInstanceOf(PlaceType::class, $placeType);
});

test('GeoNamesCap model can be instantiated', function () {
    $geoNamesCap = new GeoNamesCap();

    Assert::assertInstanceOf(GeoNamesCap::class, $geoNamesCap);
});
