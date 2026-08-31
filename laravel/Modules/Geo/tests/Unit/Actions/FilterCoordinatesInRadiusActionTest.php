<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Actions;

use Modules\Geo\Actions\FilterCoordinatesInRadiusAction;
use Modules\Geo\Actions\GoogleMaps\CalculateDistanceMatrixAction;
use Modules\Geo\Tests\Fixtures\CalculateDistanceMatrixQueueStub;

/**
 * Bind the sequenced distance-matrix stub into the container and resolve the
 * FilterCoordinatesInRadiusAction through it. The action internally resolves
 * CalculateDistanceAction (which resolves CalculateDistanceMatrixAction) via
 * app(), so binding the leaf dependency is enough to drive the whole chain.
 */
function makeFilterAction(CalculateDistanceMatrixQueueStub $stub): FilterCoordinatesInRadiusAction
{
    app()->instance(CalculateDistanceMatrixAction::class, $stub);

    return app(FilterCoordinatesInRadiusAction::class);
}

it('filters coordinates within radius', function (): void {
    $action = makeFilterAction(new CalculateDistanceMatrixQueueStub([
        [[['distance' => ['value' => 1000], 'duration' => ['value' => 100], 'status' => 'OK']]],
        [[['distance' => ['value' => 7000], 'duration' => ['value' => 500], 'status' => 'OK']]],
        [[['distance' => ['value' => 200], 'duration' => ['value' => 30], 'status' => 'OK']]],
    ]));

    $result = $action->execute(45.4642, 9.1900, [
        ['latitude' => '45.4700', 'longitude' => '9.2000'],
        ['latitude' => '45.5000', 'longitude' => '9.2500'],
        ['latitude' => '45.4650', 'longitude' => '9.1910'],
    ], 5000);

    expect($result)->toHaveCount(2);
});

it('returns empty array when no coordinates within radius', function (): void {
    $action = makeFilterAction(new CalculateDistanceMatrixQueueStub([
        [[['distance' => ['value' => 10000], 'duration' => ['value' => 600], 'status' => 'OK']]],
        [[['distance' => ['value' => 12000], 'duration' => ['value' => 800], 'status' => 'OK']]],
    ]));

    $result = $action->execute(45.4642, 9.1900, [
        ['latitude' => '45.5000', 'longitude' => '9.2500'],
        ['latitude' => '45.5100', 'longitude' => '9.2600'],
    ], 1000);

    expect($result)->toHaveCount(0);
});

it('returns all coordinates when all within radius', function (): void {
    $action = makeFilterAction(new CalculateDistanceMatrixQueueStub([
        [[['distance' => ['value' => 1000], 'duration' => ['value' => 100], 'status' => 'OK']]],
        [[['distance' => ['value' => 8000], 'duration' => ['value' => 400], 'status' => 'OK']]],
        [[['distance' => ['value' => 3000], 'duration' => ['value' => 200], 'status' => 'OK']]],
    ]));

    $result = $action->execute(45.4642, 9.1900, [
        ['latitude' => '45.4700', 'longitude' => '9.2000'],
        ['latitude' => '45.5000', 'longitude' => '9.2500'],
        ['latitude' => '45.4800', 'longitude' => '9.2100'],
    ], 50000);

    expect($result)->toHaveCount(3);
});

it('handles empty coordinates array', function (): void {
    $action = makeFilterAction(new CalculateDistanceMatrixQueueStub());

    $result = $action->execute(45.4642, 9.1900, [], 5000);

    expect($result)->toHaveCount(0);
});

it('filters exactly at boundary', function (): void {
    $action = makeFilterAction(new CalculateDistanceMatrixQueueStub([
        [[['distance' => ['value' => 5000], 'duration' => ['value' => 300], 'status' => 'OK']]],
    ]));

    $result = $action->execute(45.4642, 9.1900, [
        ['latitude' => '45.4700', 'longitude' => '9.2000'],
    ], 5000);

    expect($result)->toHaveCount(1);
});
