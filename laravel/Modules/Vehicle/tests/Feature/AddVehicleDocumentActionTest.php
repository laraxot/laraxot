<?php

declare(strict_types=1);

uses(\Modules\Vehicle\Tests\TestCase::class);

use Illuminate\Support\Facades\Storage;
use Modules\Vehicle\Actions\AddVehicleDocumentAction;
use Modules\Vehicle\Models\Vehicle;

it('adds a vehicle document and stores media metadata', function (): void {
    // Arrange
    Storage::fake('public');
    Storage::disk('public')->put('media/vehicles/test.txt', 'hello');

    $vehicle = Vehicle::query()->create([
        'plate_number' => 'AA123BB',
        'make' => 'Fiat',
        'model' => 'Ducato',
        'year' => 2020,
        'odometer_km' => 120000,
        'last_service_on' => '2026-01-10',
        'insurance_expires_on' => '2026-12-31',
        'notes' => null,
        'is_active' => true,
    ]);

    // Act
    $link = app(AddVehicleDocumentAction::class)->execute(
        vehicle: $vehicle,
        path: 'media/vehicles/test.txt',
        uploadedByUserId: null,
        title: 'Libretto',
    );

    // Assert
    expect($link->vehicle_id)->toBe($vehicle->id);
    expect($link->title)->toBe('Libretto');
    expect($link->media)->not()->toBeNull();
    expect($link->media->mime_type)->not()->toBeNull();
    expect($link->media->size_bytes)->toBe(5);
});

