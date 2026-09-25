<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models\Policies;

use Modules\Geo\Models\Policies\CountyPolicy;
use Modules\Geo\Models\Policies\GeoNamesCapPolicy;
use Modules\Geo\Models\Policies\LocalityPolicy;
use Modules\Geo\Models\Policies\PlacePolicy;
use Modules\Geo\Models\Policies\PlaceTypePolicy;
use Modules\Geo\Models\Policies\StatePolicy;
use Modules\Geo\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('StatePolicy can be instantiated', function () {
    $policy = new StatePolicy();

    Assert::assertInstanceOf(StatePolicy::class, $policy);
});

test('CountyPolicy can be instantiated', function () {
    $policy = new CountyPolicy();

    Assert::assertInstanceOf(CountyPolicy::class, $policy);
});

test('LocalityPolicy can be instantiated', function () {
    $policy = new LocalityPolicy();

    Assert::assertInstanceOf(LocalityPolicy::class, $policy);
});

test('PlacePolicy can be instantiated', function () {
    $policy = new PlacePolicy();

    Assert::assertInstanceOf(PlacePolicy::class, $policy);
});

test('PlaceTypePolicy can be instantiated', function () {
    $policy = new PlaceTypePolicy();

    Assert::assertInstanceOf(PlaceTypePolicy::class, $policy);
});

test('GeoNamesCapPolicy can be instantiated', function () {
    $policy = new GeoNamesCapPolicy();

    Assert::assertInstanceOf(GeoNamesCapPolicy::class, $policy);
});
