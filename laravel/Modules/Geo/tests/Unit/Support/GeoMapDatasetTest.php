<?php

declare(strict_types=1);

use Modules\Geo\Actions\Maps\GetGeoMapDatasetCategoriesAction;
use Modules\Geo\Actions\Maps\GetGeoMapDatasetStatsAction;
use Modules\Geo\Actions\Maps\LoadGeoMapDatasetAction;
use PHPUnit\Framework\Assert;

function geoMapDatasetPath(): string
{
    return dirname(__DIR__, 3).'/resources/data/geo-map-widget.geojson';
}

test('geo map dataset normalizes feature collection', function (): void {
    $path = geoMapDatasetPath();
    $normalized = app(LoadGeoMapDatasetAction::class)->execute($path);

    Assert::assertSame('FeatureCollection', $normalized['type']);
    Assert::assertIsArray($normalized['features']);
    Assert::assertCount(6, $normalized['features']);
    Assert::assertSame('Feature', $normalized['features'][0]['type']);
});

test('geo map dataset exposes point categories only', function (): void {
    $path = geoMapDatasetPath();
    $categories = app(GetGeoMapDatasetCategoriesAction::class)->execute($path);

    Assert::assertNotEmpty($categories);
    // `assertContainsOnly()` e' stata rimossa in PHPUnit 13: le varianti per tipo la sostituiscono.
    Assert::assertContainsOnlyString($categories);
});

test('geo map dataset computes stats for points and zones', function (): void {
    $path = geoMapDatasetPath();
    $stats = app(GetGeoMapDatasetStatsAction::class)->execute($path);

    Assert::assertSame(6, $stats['total']);
    Assert::assertGreaterThan(0, $stats['points']);
    Assert::assertGreaterThan(0, $stats['zones']);
});
