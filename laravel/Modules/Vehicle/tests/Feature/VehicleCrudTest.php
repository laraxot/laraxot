<?php

declare(strict_types=1);

uses(\Modules\Vehicle\Tests\TestCase::class);

use Modules\Vehicle\Models\Vehicle;

it('creates and soft-deletes a vehicle', function (): void {
    /** @var Vehicle $vehicle */
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

