<?php

declare(strict_types=1);

use Modules\Timber\Actions\RegisterTimberProcessingAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberProcessingStep;
use Modules\Timber\Tests\TestCase;

uses(\Modules\Timber\Tests\TestCase::class);

it('registers a processing step from a lot producing usable and waste outputs', function (): void {
    $lot = TimberLot::factory()->createOne([
        'status_code' => 'available',
        'quantity_available' => 1000,
        'unit_of_measure' => 'kg',
        'wood_species' => 'faggio',
    ]);

    $step = app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: 10.0,
        unitOfMeasure: 'kg',
        notes: 'Lavorazione di test',
        outputDefinitions: [
            ['wood_species' => 'faggio', 'quantity' => 800, 'unit_of_measure' => 'kg', 'is_waste' => false],
            ['wood_species' => 'faggio', 'quantity' => 150, 'unit_of_measure' => 'kg', 'is_waste' => true],
        ],
    );

    expect($step)
        ->and($step->step_type_code)->toBe('taglio')
        ->and((float) $step->input_quantity)->toBe(1000.0)
        ->and((float) $step->output_quantity)->toBe(800.0)
        ->and((float) $step->waste_quantity)->toBe(150.0)
        ->and($step->derivedMaterials)->toHaveCount(2)
        ->and($lot->fresh()?->status_code)->toBe('esaurito')
        ->and((float) $lot->fresh()?->quantity_available)->toBe(0.0);

    $wasteMaterial = $step->derivedMaterials->firstWhere('is_waste', true);
    $goodMaterial = $step->derivedMaterials->firstWhere('is_waste', false);
    expect($wasteMaterial)->not->toBeNull();
    expect($goodMaterial)->not->toBeNull();
    assert($wasteMaterial instanceof TimberDerivedMaterial);
    assert($goodMaterial instanceof TimberDerivedMaterial);

    expect($wasteMaterial->status_code)->toBe('scarto')
        ->and($wasteMaterial->current_area_code)->toBe('area_scarti')
        ->and($goodMaterial->status_code)->toBe('da_lavorare')
        ->and($goodMaterial->current_area_code)->toBe('zona_taglio')
        ->and($goodMaterial->labelPrints)->toHaveCount(1);

    expect($step->inputLots()->pluck('timber_lots.id')->all())->toBe([$lot->id]);
});

it('registers a processing step using a derived material as input', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne([
        'quantity' => 500,
        'wood_species' => 'larice',
        'is_waste' => false,
    ]);

    $step = app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [],
        selectedDerivedMaterialIds: [$material->id],
        stepTypeCode: 'confezionamento',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [
            ['wood_species' => 'larice', 'quantity' => 480, 'is_waste' => false],
        ],
    );

    expect((float) $material->fresh()?->quantity)->toBe(0.0)
        ->and($step->inputDerivedMaterials()->pluck('timber_derived_materials.id')->all())->toBe([$material->id]);
});

it('rejects processing with no lot or material selected', function (): void {
    expect(function (): void {
        app(RegisterTimberProcessingAction::class)->execute(
            selectedLotIds: [],
            selectedDerivedMaterialIds: [],
            stepTypeCode: 'taglio',
            performedByUserId: null,
            recoverableQuantity: null,
            unitOfMeasure: 'kg',
            notes: null,
            outputDefinitions: [['wood_species' => 'abete', 'quantity' => 10]],
        );
    })->toThrow(\InvalidArgumentException::class);
});

it('rejects an invalid step type', function (): void {
    $lot = TimberLot::factory()->createOne();

    expect(fn (): mixed => app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'not-a-step',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'abete', 'quantity' => 10]],
    ))->toThrow(\InvalidArgumentException::class);
});

it('rejects processing a lot that is not available', function (): void {
    $lot = TimberLot::factory()->createOne(['status_code' => 'esaurito']);

    expect(fn (): mixed => app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'abete', 'quantity' => 10]],
    ))->toThrow(\InvalidArgumentException::class);
});

it('rejects processing a derived material without available quantity', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['quantity' => 0]);

    expect(fn (): mixed => app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [],
        selectedDerivedMaterialIds: [$material->id],
        stepTypeCode: 'selezione',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'abete', 'quantity' => 10]],
    ))->toThrow(\InvalidArgumentException::class);
});

it('rejects an empty list of output definitions', function (): void {
    $lot = TimberLot::factory()->createOne();

    expect(fn (): mixed => app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [],
    ))->toThrow(\InvalidArgumentException::class);
});

it('forces the output species to the input species and builds the traceable code', function (): void {
    $lot = TimberLot::factory()->createOne([
        'status_code' => 'available',
        'quantity_available' => 1000,
        'wood_species' => 'castagno',
        'lot_code' => 'LOT-2026-0007',
    ]);

    $step = app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        // Specie diversa/vuota negli output: deve essere ignorata (auto = input).
        outputDefinitions: [['wood_species' => 'abete', 'quantity' => 800, 'is_waste' => false]],
    );

    $material = $step->derivedMaterials->firstWhere('is_waste', false);
    expect($material)->not->toBeNull();
    assert($material instanceof TimberDerivedMaterial);

    expect($material->wood_species)->toBe('castagno')
        ->and($material->root_lot_id)->toBe($lot->id)
        ->and($material->traceable_code)->toBe('LOT-2026-0007·TAG-'.now()->format('md'))
        ->and($material->stage_history)->toHaveCount(1);
});

it('extends the traceable code when processing a semi-finished material further', function (): void {
    $lot = TimberLot::factory()->createOne(['wood_species' => 'faggio', 'lot_code' => 'LOT-2026-0010', 'status_code' => 'available', 'quantity_available' => 500]);

    $cut = app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'faggio', 'quantity' => 400, 'is_waste' => false]],
    );
    $semi = $cut->derivedMaterials->firstWhere('is_waste', false);
    expect($semi)->not->toBeNull();
    assert($semi instanceof TimberDerivedMaterial);

    $pack = app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [],
        selectedDerivedMaterialIds: [$semi->id],
        stepTypeCode: 'confezionamento',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'faggio', 'quantity' => 380, 'is_waste' => false]],
    );
    $packed = $pack->derivedMaterials->firstWhere('is_waste', false);
    expect($packed)->not->toBeNull();
    assert($packed instanceof TimberDerivedMaterial);

    expect($packed->traceable_code)->toBe('LOT-2026-0010·TAG-'.now()->format('md').'·CNF-'.now()->format('md'))
        ->and($packed->root_lot_id)->toBe($lot->id)
        ->and($packed->stage_history)->toHaveCount(2);
});

it('rejects an output definition with a non positive quantity', function (): void {
    $lot = TimberLot::factory()->createOne();

    expect(fn (): mixed => app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: null,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'abete', 'quantity' => 0]],
    ))->toThrow(\InvalidArgumentException::class);
});

it('rejects a negative recoverable quantity', function (): void {
    $lot = TimberLot::factory()->createOne();

    expect(fn (): mixed => app(RegisterTimberProcessingAction::class)->execute(
        selectedLotIds: [$lot->id],
        selectedDerivedMaterialIds: [],
        stepTypeCode: 'taglio',
        performedByUserId: null,
        recoverableQuantity: -5.0,
        unitOfMeasure: 'kg',
        notes: null,
        outputDefinitions: [['wood_species' => 'abete', 'quantity' => 10]],
    ))->toThrow(\InvalidArgumentException::class);
});

it('rejects unknown lot ids', function (): void {
    expect(function (): void {
        app(RegisterTimberProcessingAction::class)->execute(
            selectedLotIds: [999999],
            selectedDerivedMaterialIds: [],
            stepTypeCode: 'taglio',
            performedByUserId: null,
            recoverableQuantity: null,
            unitOfMeasure: 'kg',
            notes: null,
            outputDefinitions: [['wood_species' => 'abete', 'quantity' => 10]],
        );
    })->toThrow(\InvalidArgumentException::class);
});

it('rejects unknown derived material ids', function (): void {
    expect(function (): void {
        app(RegisterTimberProcessingAction::class)->execute(
            selectedLotIds: [],
            selectedDerivedMaterialIds: [999999],
            stepTypeCode: 'taglio',
            performedByUserId: null,
            recoverableQuantity: null,
            unitOfMeasure: 'kg',
            notes: null,
            outputDefinitions: [['wood_species' => 'abete', 'quantity' => 10]],
        );
    })->toThrow(\InvalidArgumentException::class);
});
