<?php

declare(strict_types=1);

use Modules\Timber\Database\Seeders\TimberDemoSeeder;
use Modules\Timber\Models\TimberAgent;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberDocument;
use Modules\Timber\Models\TimberDocumentLine;
use Modules\Timber\Models\TimberDryingCycle;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberStockMovement;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Models\TimberTransporter;
use Modules\Timber\Queries\GetTimberStockMovementsQuery;
use Modules\Timber\Tests\TestCase;

uses(\Modules\Timber\Tests\TestCase::class);

it('populates suppliers, transporters, agents, purchases and lots in varied states', function (): void {
    app(TimberDemoSeeder::class)->run();

    expect(TimberSupplier::query()->count())->toBeGreaterThanOrEqual(3)
        ->and(TimberTransporter::query()->count())->toBeGreaterThanOrEqual(1)
        ->and(TimberAgent::query()->count())->toBeGreaterThanOrEqual(2)
        ->and(TimberPurchaseRecord::query()->count())->toBeGreaterThanOrEqual(5)
        ->and(TimberLot::query()->count())->toBeGreaterThanOrEqual(5)
        ->and(TimberDryingCycle::query()->where('status_code', 'in_progress')->count())->toBeGreaterThanOrEqual(1)
        ->and(TimberDryingCycle::query()->where('status_code', 'completed')->count())->toBeGreaterThanOrEqual(2)
        ->and(TimberPackagedUnit::query()->count())->toBeGreaterThanOrEqual(1);
});

it('populates the Easyfatt-style commercial tables with coherent relations', function (): void {
    app(TimberDemoSeeder::class)->run();

    expect(TimberCustomer::query()->count())->toBeGreaterThanOrEqual(3)
        ->and(TimberCustomer::query()->where('is_supplier', true)->exists())->toBeTrue()
        ->and(TimberProduct::query()->count())->toBeGreaterThanOrEqual(3)
        ->and(TimberDocument::query()->where('document_type_code', 'arrivo_merce')->exists())->toBeTrue()
        ->and(TimberDocument::query()->where('document_type_code', 'ddt')->exists())->toBeTrue()
        ->and(TimberDocumentLine::query()->count())->toBeGreaterThanOrEqual(3)
        ->and(TimberStockMovement::query()->where('direction_code', 'load')->exists())->toBeTrue()
        ->and(TimberStockMovement::query()->where('direction_code', 'unload')->exists())->toBeTrue();

    // Every customer with an agent points to a real agent, and every product to a real supplier.
    TimberCustomer::query()->whereNotNull('agent_id')->get()->each(function (TimberCustomer $customer): void {
        expect($customer->agent)->not->toBeNull();
    });
    TimberProduct::query()->whereNotNull('supplier_id')->get()->each(function (TimberProduct $product): void {
        expect($product->supplier)->not->toBeNull();
    });

    // A posted inbound document leaves positive stock, and the DDT reduced it coherently.
    $stockQuery = app(GetTimberStockMovementsQuery::class);
    $product = TimberProduct::query()->whereHas('supplier')->firstOrFail();
    expect($stockQuery->stockForProduct($product->id))->toBeGreaterThan(0.0);
});

it('is safe to run more than once without duplicating registries', function (): void {
    app(TimberDemoSeeder::class)->run();
    $supplierCountAfterFirstRun = TimberSupplier::query()->count();
    $customerCountAfterFirstRun = TimberCustomer::query()->count();
    $documentCountAfterFirstRun = TimberDocument::query()->count();
    $movementCountAfterFirstRun = TimberStockMovement::query()->count();

    app(TimberDemoSeeder::class)->run();

    expect(TimberSupplier::query()->count())->toBe($supplierCountAfterFirstRun)
        ->and(TimberCustomer::query()->count())->toBe($customerCountAfterFirstRun)
        ->and(TimberDocument::query()->count())->toBe($documentCountAfterFirstRun)
        ->and(TimberStockMovement::query()->count())->toBe($movementCountAfterFirstRun);
});
