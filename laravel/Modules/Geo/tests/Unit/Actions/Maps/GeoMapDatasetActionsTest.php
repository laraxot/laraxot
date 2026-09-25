<?php

declare(strict_types=1);

use Modules\Geo\Actions\Maps\GetGeoMapDatasetCategoriesAction;
use Modules\Geo\Actions\Maps\GetGeoMapDatasetStatsAction;
use Modules\Geo\Actions\Maps\LoadGeoMapDatasetAction;
use PHPUnit\Framework\Assert;

function geoMapDatasetPath(): string
{
    return '/var/www/_bases/base_fixcity_fila5/laravel/Modules/Geo/resources/data/geo-map-widget.geojson';
}

test('load geo map dataset normalizes feature collection', function (): void {
    $normalized = app(LoadGeoMapDatasetAction::class)->execute(geoMapDatasetPath());

    Assert::assertSame('FeatureCollection', $normalized['type']);
    Assert::assertIsArray($normalized['features']);
    Assert::assertCount(6, $normalized['features']);
    Assert::assertSame('Feature', $normalized['features'][0]['type']);
});

test('geo map dataset exposes point categories only', function (): void {
    $categories = app(GetGeoMapDatasetCategoriesAction::class)->execute(geoMapDatasetPath());

    Assert::assertIsArray($categories);
    Assert::assertNotEmpty($categories);
});

test('geo map dataset computes stats for points and zones', function (): void {
    $stats = app(GetGeoMapDatasetStatsAction::class)->execute(geoMapDatasetPath());

    Assert::assertSame(6, $stats['total']);
    Assert::assertGreaterThan(0, $stats['points']);
    Assert::assertGreaterThan(0, $stats['zones']);
    Assert::assertGreaterThan(0, $stats['categories']);
});
