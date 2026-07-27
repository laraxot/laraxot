<?php

declare(strict_types=1);

namespace Modules\Vehicle\Tests\Feature;

use Modules\Vehicle\Models\Vehicle;
use Modules\Vehicle\Tests\TestCase;

uses(TestCase::class);

it('creates and soft-deletes a vehicle', function (): void {
    $vehicle = Vehicle::query()->create([
        'plate_number' => 'AB123CD',
        'year' => 2020,
        'make' => 'Fiat',
        'model' => 'Ducato',
        'odometer_km' => 100000,
        'last_service_on' => null,
        'insurance_expires_on' => null,
        'notes' => null,
        'is_active' => 1,
    ]);

    expect($vehicle->id)->not->toBeNull();

    $vehicle->delete();
    expect($vehicle->refresh()->trashed())->toBeTrue();
});
