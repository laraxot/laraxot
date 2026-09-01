<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Actions\IPGeolocation;

use Modules\Geo\Actions\IPGeolocation\FetchIPLocationAction;
use Modules\Geo\Actions\IPGeolocation\GetLocationFromIPAction;
use Modules\Geo\Datas\Location\IPLocationData;
use Modules\Geo\Tests\Fixtures\FetchIPLocationReturningStub;
use Modules\Geo\Tests\Fixtures\FetchIPLocationThrowingStub;

it('delegates to fetch action and returns result', function (): void {
    app()->instance(FetchIPLocationAction::class, new FetchIPLocationReturningStub(new IPLocationData(
        ip: '8.8.8.8',
        city: 'Ashburn',
        region: null,
        country: 'US',
        countryName: 'United States',
        latitude: null,
        longitude: null,
        timezone: null,
        isp: null,
    )));

    $action = app(GetLocationFromIPAction::class);

    $result = $action->execute('8.8.8.8');

    expect($result)->toBeInstanceOf(IPLocationData::class)
        ->and($result?->ip)->toBe('8.8.8.8')
        ->and($result?->city)->toBe('Ashburn');
});

it('propagates exception when fetch action throws', function (): void {
    app()->instance(FetchIPLocationAction::class, new FetchIPLocationThrowingStub(new \RuntimeException('not found')));

    $action = app(GetLocationFromIPAction::class);

    expect(fn (): ?IPLocationData => $action->execute('192.168.1.1'))
        ->toThrow(\RuntimeException::class, 'not found');
});
