<?php

declare(strict_types=1);

use Modules\Timber\Actions\BuildTimberQrPayloadAction;
use Modules\Timber\Actions\GenerateTimberInventoryPdfAction;
use Modules\Timber\Actions\GenerateTimberLabelPdfAction;
use Modules\Timber\Actions\GenerateTimberTraceabilityPdfAction;
use Modules\Timber\Actions\RenderTimberQrSvgAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Tests\TestCase;

uses(\Modules\Timber\Tests\TestCase::class);

it('builds a qr payload for a lot', function (): void {
    $lot = TimberLot::factory()->createOne(['lot_code' => 'LOT-2026-0099', 'wood_species' => 'abete']);

    $payload = app(BuildTimberQrPayloadAction::class)->execute($lot);

    expect($payload)->toContain('TMB')
        ->toContain('LOTTO=LOT-2026-0099')
        ->toContain('SPECIE=abete');
});

it('includes supplier and DDT in the incoming lot qr payload', function (): void {
    $supplier = TimberSupplier::factory()->createOne(['legal_name' => 'Boschi Veneti Srl']);
    $record = TimberPurchaseRecord::factory()->createOne([
        'supplier_id' => $supplier->getKey(),
        'ddt_number' => 'DDT-2026-00101',
    ]);
    $lot = TimberLot::factory()->createOne([
        'purchase_record_id' => $record->getKey(),
        'lot_code' => 'LOT-2026-0099',
        'wood_species' => 'castagno',
    ]);

    $payload = app(BuildTimberQrPayloadAction::class)->execute($lot);

    expect($payload)->toContain('LOTTO=LOT-2026-0099')
        ->toContain('SPECIE=castagno')
        ->toContain('FORNITORE=Boschi Veneti Srl')
        ->toContain('DDT=DDT-2026-00101');
});

it('builds a qr payload for a derived material', function (): void {
    $material = TimberDerivedMaterial::factory()->createOne(['output_code' => 'MAT-2026-0001']);

    $payload = app(BuildTimberQrPayloadAction::class)->execute($material);

    expect($payload)->toContain('MATERIALE=MAT-2026-0001');
});

it('builds a qr payload for a packaged unit', function (): void {
    $unit = TimberPackagedUnit::factory()->createOne(['package_code' => 'PKG-2026-0001', 'package_type_code' => 'bancale']);

    $payload = app(BuildTimberQrPayloadAction::class)->execute($unit);

    expect($payload)->toContain('CONFEZIONE=PKG-2026-0001')
        ->toContain('TIPO=bancale');
});

it('rejects building a qr payload for an unsupported model', function (): void {
    $supplier = TimberSupplier::factory()->createOne();

    expect(fn (): mixed => app(BuildTimberQrPayloadAction::class)->execute($supplier))
        ->toThrow(\InvalidArgumentException::class);
});

it('renders a qr svg and a data uri', function (): void {
    $action = app(RenderTimberQrSvgAction::class);

    $svg = $action->execute('TMB|LOTTO=TEST');
    $dataUri = $action->dataUri('TMB|LOTTO=TEST');

    expect($svg)->toContain('<svg')
        ->and($dataUri)->toStartWith('data:image/svg+xml;base64,');
});

it('generates a label pdf for a lot', function (): void {
    $lot = TimberLot::factory()->createOne();

    $pdf = app(GenerateTimberLabelPdfAction::class)->execute($lot);

    expect($pdf)
        ->and(strlen($pdf))->toBeGreaterThan(1000)
        ->and($pdf)->toStartWith('%PDF');
});

it('generates an inventory pdf', function (): void {
    TimberLot::factory()->createOne();

    $pdf = app(GenerateTimberInventoryPdfAction::class)->execute(null, null);

    expect($pdf)->toStartWith('%PDF');
});

it('generates a traceability pdf', function (): void {
    $lot = TimberLot::factory()->createOne(['lot_code' => 'LOT-2026-0055']);

    $pdf = app(GenerateTimberTraceabilityPdfAction::class)->execute('LOT-2026-0055');

    expect($pdf)->toStartWith('%PDF');
});
