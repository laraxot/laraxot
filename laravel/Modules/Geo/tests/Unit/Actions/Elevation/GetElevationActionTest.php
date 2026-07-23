<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Actions\Elevation;

use Modules\Geo\Actions\Elevation\GetElevationAction;
use Modules\Geo\Actions\GoogleMapsAction;
use Modules\Geo\Datas\LocationData;
use Modules\Geo\Exceptions\ElevationException;
use Modules\Geo\Tests\Fixtures\GoogleMapsServiceElevationStub;
use Modules\Geo\Tests\LightTestCase;

uses(LightTestCase::class);

/**
 * Bind the elevation service stub into the container and resolve the action
 * under test through it. GetElevationAction resolves GoogleMapsAction via
 * app(), so binding the stub is enough (QueueableAction convention: never
 * inject dependencies through `new`).
 */
function makeGetElevationAction(GoogleMapsServiceElevationStub $stub): GetElevationAction
{
    app()->instance(GoogleMapsAction::class, $stub);

    return app(GetElevationAction::class);
}

it('gets elevation for valid location', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub([
        'results' => [
            ['elevation' => 120.5, 'resolution' => 5.0],
        ],
    ]));

    $location = new LocationData(
        latitude: 45.4642,
        longitude: 9.1900,
        address: 'Milano, Italia',
    );

    expect($action->execute($location))->toBe(120.5);
});

it('throws exception for invalid latitude', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect(fn (): float => $action->execute(
        new LocationData(latitude: 100.0, longitude: 9.1900, address: 'Invalid Location'),
    ))->toThrow(\InvalidArgumentException::class, 'Latitudine non valida');
});

it('throws exception for invalid longitude', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect(fn (): float => $action->execute(
        new LocationData(latitude: 45.4642, longitude: 200.0, address: 'Invalid Location'),
    ))->toThrow(\InvalidArgumentException::class, 'Longitudine non valida');
});

it('throws exception for negative latitude', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect(fn (): float => $action->execute(
        new LocationData(latitude: -100.0, longitude: 9.1900, address: 'Invalid Location'),
    ))->toThrow(\InvalidArgumentException::class, 'Latitudine non valida');
});

it('throws exception for negative longitude', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect(fn (): float => $action->execute(
        new LocationData(latitude: 45.4642, longitude: -200.0, address: 'Invalid Location'),
    ))->toThrow(\InvalidArgumentException::class, 'Longitudine non valida');
});

it('throws exception for empty response', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub(['results' => []]));

    expect(fn (): float => $action->execute(
        new LocationData(latitude: 45.4642, longitude: 9.1900, address: 'Milano, Italia'),
    ))->toThrow(ElevationException::class, 'Risposta non valida dal servizio di elevazione');
});

it('throws exception for invalid response structure', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub(['results' => ['invalid']]));

    expect(fn (): float => $action->execute(
        new LocationData(latitude: 45.4642, longitude: 9.1900, address: 'Milano, Italia'),
    ))->toThrow(ElevationException::class, 'Risposta non valida dal servizio di elevazione');
});

it('throws exception when service throws generic exception', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub([], new \Exception('Network error')));

    expect(fn (): float => $action->execute(
        new LocationData(latitude: 45.4642, longitude: 9.1900, address: 'Milano, Italia'),
    ))->toThrow(ElevationException::class, 'Errore nel recupero dell\'elevazione');
});

it('formats elevation correctly', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect($action->formatElevation(1234.5))->toBe('1234.5 m s.l.m.');
});

it('formats elevation with zero value', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect($action->formatElevation(0))->toBe('0.0 m s.l.m.');
});

it('formats negative elevation correctly', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect($action->formatElevation(-430.0))->toBe('-430.0 m s.l.m.');
});

it('handles high elevation correctly', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub());

    expect($action->formatElevation(8848.0))->toBe('8848.0 m s.l.m.');
});

it('handles boundary latitude values', function (): void {
    $action = makeGetElevationAction(new GoogleMapsServiceElevationStub([
        'results' => [
            ['elevation' => 0.0, 'resolution' => 1.0],
        ],
    ]));

    expect($action->execute(new LocationData(latitude: 90.0, longitude: 0.0, address: 'North Pole')))->toBe(0.0);
});
