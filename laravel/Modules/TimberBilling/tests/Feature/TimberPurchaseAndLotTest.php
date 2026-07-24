<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Actions\RegisterTimberPurchaseAction;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberPurchaseRecord;
use Modules\TimberBilling\Models\TimberSupplier;
use Modules\TimberBilling\Models\TimberTransporter;

function registerTimberPurchase(array $overrides = []): TimberPurchaseRecord
{
    $supplier = $overrides['supplier'] ?? TimberSupplier::factory()->create();
    $transporter = $overrides['transporter'] ?? null;

    return app(RegisterTimberPurchaseAction::class)->execute(
        supplier: $supplier,
        transporter: $transporter,
        originForest: $overrides['originForest'] ?? 'Bosco del Cansiglio',
        ddtNumber: $overrides['ddtNumber'] ?? 'DDT-TEST-0001',
        ddtDate: $overrides['ddtDate'] ?? '2026-01-10',
        woodSpecies: $overrides['woodSpecies'] ?? 'abete',
        quantity: $overrides['quantity'] ?? 1000.0,
        unitOfMeasure: $overrides['unitOfMeasure'] ?? 'kg',
        grossWeight: $overrides['grossWeight'] ?? 1000.0,
        netWeight: $overrides['netWeight'] ?? 950.0,
        unitPurchasePrice: $overrides['unitPurchasePrice'] ?? 0.15,
        certifications: $overrides['certifications'] ?? 'FSC',
        photoPath: $overrides['photoPath'] ?? null,
        notes: $overrides['notes'] ?? null,
        userId: $overrides['userId'] ?? null,
        purchasePriceUnit: $overrides['purchasePriceUnit'] ?? null,
    );
}

it('registers a purchase and automatically creates the operational lot', function (): void {
    $supplier = TimberSupplier::factory()->create(['legal_name' => 'Boschi Test Srl']);
    $transporter = TimberTransporter::factory()->create();

    $record = registerTimberPurchase(['supplier' => $supplier, 'transporter' => $transporter]);

    expect($record)->toBeInstanceOf(TimberPurchaseRecord::class)
        ->and($record->supplier_id)->toBe($supplier->id)
        ->and($record->transporter_id)->toBe($transporter->id)
        ->and($record->status_code)->toBe('registered')
        ->and($record->lot)->toBeInstanceOf(TimberLot::class)
        ->and($record->lot->lot_code)->toStartWith('LOT-')
        ->and($record->lot->status_code)->toBe('available')
        ->and($record->lot->current_area_code)->toBe('piazzale')
        ->and((float) $record->lot->quantity_available)->toBe(1000.0);
});

it('registers a purchase with a species added from the database', function (): void {
    Modules\TimberBilling\Models\TimberWoodSpecies::query()->create(['code' => 'rovere', 'name' => 'Rovere']);

    $record = registerTimberPurchase(['woodSpecies' => 'rovere']);

    expect($record->wood_species)->toBe('rovere');
});

it('stores the purchase price unit (per kg or per quintale)', function (): void {
    $record = registerTimberPurchase(['purchasePriceUnit' => 'kg']);

    expect($record->purchase_price_unit)->toBe('kg');
});

it('defaults the purchase price unit to quintale when missing or invalid', function (): void {
    expect(registerTimberPurchase()->purchase_price_unit)->toBe('quintale')
        ->and(registerTimberPurchase(['purchasePriceUnit' => 'bananas'])->purchase_price_unit)->toBe('quintale');
});

it('generates sequential lot codes across multiple purchases', function (): void {
    $first = registerTimberPurchase(['ddtNumber' => 'DDT-A']);
    $second = registerTimberPurchase(['ddtNumber' => 'DDT-B']);

    expect($first->lot->lot_code)->not->toBe($second->lot->lot_code);
});

it('rejects an unknown wood species', function (): void {
    registerTimberPurchase(['woodSpecies' => 'not-a-species']);
})->throws(InvalidArgumentException::class);

it('rejects an unknown unit of measure', function (): void {
    registerTimberPurchase(['unitOfMeasure' => 'not-a-unit']);
})->throws(InvalidArgumentException::class);

it('rejects a non positive quantity', function (): void {
    registerTimberPurchase(['quantity' => 0.0]);
})->throws(InvalidArgumentException::class);

it('rejects a negative gross weight', function (): void {
    registerTimberPurchase(['grossWeight' => -1.0]);
})->throws(InvalidArgumentException::class);

it('rejects a negative net weight', function (): void {
    registerTimberPurchase(['netWeight' => -1.0]);
})->throws(InvalidArgumentException::class);

it('rejects a negative unit purchase price', function (): void {
    registerTimberPurchase(['unitPurchasePrice' => -1.0]);
})->throws(InvalidArgumentException::class);
