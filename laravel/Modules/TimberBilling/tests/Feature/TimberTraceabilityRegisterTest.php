<?php

declare(strict_types=1);

use Modules\TimberBilling\Actions\PackageTimberMaterialAction;
use Modules\TimberBilling\Actions\RegisterTimberProcessingAction;
use Modules\TimberBilling\Actions\RegisterTimberPurchaseAction;
use Modules\TimberBilling\Data\TimberTraceabilityRow;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberSupplier;
use Modules\TimberBilling\Queries\GetTimberTraceabilityRegisterQuery;
use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

it('projects a full chain into a traceability register row like the Excel sheet', function (): void {
    $supplier = TimberSupplier::factory()->createOne(['legal_name' => 'Boschi Veneti Srl', 'vat_number' => 'IT01234567890']);

    $record = app(RegisterTimberPurchaseAction::class)->execute(
        supplier: $supplier,
        transporter: null,
        originForest: null,
        ddtNumber: 'DDT-2026-00101',
        ddtDate: '2026-07-01',
        woodSpecies: 'castagno',
        quantity: 100.0,
        unitOfMeasure: 'quintali',
        grossWeight: null,
        netWeight: null,
        unitPurchasePrice: null,
        certifications: null,
        photoPath: null,
        notes: null,
        userId: null,
    );
    $lot = $record->lot;
    expect($lot)->not->toBeNull();
    assert($lot instanceof TimberLot);

    $step = app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'quintali',
        notes: null,
        outputDefinitions: [['wood_species' => 'castagno', 'quantity' => 80, 'is_waste' => false]],
    );
    $semi = $step->derivedMaterials->firstWhere('is_waste', false);
    expect($semi)->not->toBeNull();
    assert($semi instanceof TimberDerivedMaterial);

    $product = TimberProduct::factory()->createOne(['code' => '80590-A', 'unit_of_measure' => 'quintali']);
    app(PackageTimberMaterialAction::class)->execute($semi, [
        ['product_id' => $product->id, 'package_type_code' => 'bancale', 'packages_count' => 3, 'quantity_per_package' => 20.0],
    ]);

    $rows = app(GetTimberTraceabilityRegisterQuery::class)->execute();
    $row = collect($rows)->firstWhere('articleCode', '80590-A');
    expect($row)->not->toBeNull();
    assert($row instanceof TimberTraceabilityRow);

    expect($row->supplierName)->toBe('Boschi Veneti Srl')
        ->and($row->supplierCode)->toBe('IT01234567890')
        ->and($row->ingressYear)->toBe('2026')
        ->and($row->ddtNumber)->toBe('DDT-2026-00101')
        ->and($row->quantity)->toBe(100.0)
        ->and($row->unit)->toBe('quintali')
        ->and($row->bancali)->toBe(3)
        ->and($row->workStartDate)->toBe(now()->format('Y-m-d'))
        ->and($row->packagingDate)->toBe(now()->format('Y-m-d'));
});

it('includes lots not yet packaged as rows with empty confezionamento', function (): void {
    $supplier = TimberSupplier::factory()->createOne(['legal_name' => 'Legnami Alpini']);
    app(RegisterTimberPurchaseAction::class)->execute(
        supplier: $supplier,
        transporter: null,
        originForest: null,
        ddtNumber: 'DDT-2026-00200',
        ddtDate: '2026-07-05',
        woodSpecies: 'faggio',
        quantity: 50.0,
        unitOfMeasure: 'quintali',
        grossWeight: null,
        netWeight: null,
        unitPurchasePrice: null,
        certifications: null,
        photoPath: null,
        notes: null,
        userId: null,
    );

    $rows = app(GetTimberTraceabilityRegisterQuery::class)->execute();
    $row = collect($rows)->firstWhere('ddtNumber', 'DDT-2026-00200');
    expect($row)->not->toBeNull();
    assert($row instanceof TimberTraceabilityRow);

    expect($row->articleCode)->toBeNull()
        ->and($row->bancali)->toBe(0)
        ->and($row->workStartDate)->toBeNull();
});
