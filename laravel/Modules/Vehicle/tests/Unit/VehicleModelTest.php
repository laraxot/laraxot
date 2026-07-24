<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Vehicle\Database\Factories\VehicleFactory;
use Modules\Vehicle\Models\Vehicle;
use Modules\Vehicle\Models\VehicleDocument;
use Modules\Vehicle\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

// No database writes here: this module's test infrastructure does not run
// migrations automatically, so factories are used unpersisted (makeOne()).

it('builds a vehicle via its factory', function (): void {
    $vehicle = VehicleFactory::new()->makeOne();

    Assert::assertInstanceOf(Vehicle::class, $vehicle);
    Assert::assertNotEmpty($vehicle->plate_number);
    Assert::assertSame('vehicles', $vehicle->getTable());
});

it('uses soft deletes', function (): void {
    $vehicle = new Vehicle();

    Assert::assertContains(SoftDeletes::class, class_uses_recursive($vehicle));
});

it('exposes a hasMany documents relation', function (): void {
    $vehicle = new Vehicle();

    Assert::assertInstanceOf(HasMany::class, $vehicle->documents());
});

it('links a vehicle document to a vehicle via belongsTo', function (): void {
    $document = new VehicleDocument();

    Assert::assertInstanceOf(BelongsTo::class, $document->vehicle());
    Assert::assertSame('vehicle_documents', $document->getTable());
});
