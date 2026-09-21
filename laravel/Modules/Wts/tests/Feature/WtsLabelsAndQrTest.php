<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsLotDetailPage;
use Modules\User\Models\User;
use Modules\Wts\Actions\BuildWtsQrPayloadAction;
use Modules\Wts\Actions\GenerateWtsUnitLabelPdfAction;
use Modules\Wts\Actions\GenerateWtsUnitSeriesAction;
use Modules\Wts\Mail\WtsNonConformityReportedMail;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsNonConformityReport;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\get;

it('builds a QR that links to the processing page for the lot', function (): void {
    config()->set('app.url', 'https://commesse.cavalliniservice.com');

    $lot = WtsInboundLot::factory()->create([
        'lot_code' => 'LOT-2026-0100',
    ]);

    $inboundRecord = $lot->inboundRecord;
    assert($inboundRecord instanceof WtsInboundRecord);

    $unit = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: $inboundRecord->technicalSheetSnapshot,
        printReasonCode: 'initial_print',
    )->first();
    assert($unit instanceof WtsUnit);

    $payload = app(BuildWtsQrPayloadAction::class)->execute($unit);

    expect($payload)->toBe('https://commesse.cavalliniservice.com/wts/processing?lot='.$lot->id);
});

it('generates a PDF label for a WTS unit with a real QR SVG', function (): void {
    $lot = WtsInboundLot::factory()->create([
        'lot_code' => 'LOT-2026-0101',
    ]);

    $inboundRecord = $lot->inboundRecord;
    assert($inboundRecord instanceof WtsInboundRecord);

    $unit = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'pallet',
        unitsCount: 1,
        technicalSheetSnapshot: $inboundRecord->technicalSheetSnapshot,
        printReasonCode: 'initial_print',
    )->first();
    assert($unit instanceof WtsUnit);

    $pdf = app(GenerateWtsUnitLabelPdfAction::class)->execute($unit);

    expect(strlen($pdf))->toBeGreaterThan(1000)
        ->and($pdf)->toStartWith('%PDF');
});

it('records a non conformity from the lot detail page', function (): void {
    Role::findOrCreate('wts_admin');
    Mail::fake();

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create([
        'lot_code' => 'LOT-2026-0102',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsLotDetailPage::class)
        ->set('nonConformityNote', 'Etichetta non leggibile sul collo')
        ->call('reportNonConformity')
        ->assertHasNoErrors();

    expect(WtsNonConformityReport::query()->where('wts_inbound_lot_id', $lot->id)->exists())->toBeTrue()
        ->and(WtsNonConformityReport::query()->first()?->note)->toBe('Etichetta non leggibile sul collo');

    Mail::assertSent(WtsNonConformityReportedMail::class);
});

it('redirects a scanned WTS QR code to the material detail page', function (): void {
    $unit = WtsUnit::factory()->create([
        'qr_code' => 'WTS-QR-LOT-2026-QR-01',
    ]);

    get('/wts/qr/'.$unit->qr_code)
        ->assertRedirect('/wts/material-detail?unit='.$unit->id);
});

it('generates a ZPL label for a WTS unit with the verified weight and QR', function (): void {
    config()->set('wts.label_pdf.zpl_force_size', true);
    config()->set('wts.label_pdf.zpl_label_width_dots', 0);
    config()->set('wts.label_pdf.zpl_label_length_dots', 0);

    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'sheet_code' => 'ST2025-04083',
        'producer_name' => 'GALVALUX SRL',
        'waste_code' => '110107',
        'production_description' => 'basi di decappaggio',
        'legacy_payload' => ['producer_address' => 'VIA MAESTRA 54/56, 32044 PIEVE DI CADORE (BL)'],
    ]);
    $record = WtsInboundRecord::factory()->create([
        'technical_sheet_snapshot_id' => $snapshot->id,
        'form_number' => 'XCJQH 000141 BV',
        'entry_weight' => 28840,
        'ecos_units_count' => 1,
    ]);
    $lot = WtsInboundLot::factory()->create(['inbound_record_id' => $record->id, 'lot_code' => 'LOT-2026-0012']);
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $snapshot->id,
        'unit_code' => 'LOT-2026-0012-BIG-BAG-01',
    ]);

    $zpl = app(Modules\Wts\Actions\GenerateWtsUnitLabelZplAction::class)->execute($unit);

    expect($zpl)
        ->toStartWith('^XA')
        ->toContain('^XZ')
        ->toContain('^PW400')
        ->toContain('^LL1119')
        ->toContain('^BQN')
        ->toContain('^MMC')
        ->toContain('^GFA')
        ->toContain('CER 110107')
        ->toContain('GALVALUX SRL')
        ->toContain('PIEVE DI CADORE')
        ->toContain('Peso: 28.840 kg');
});

it('rejects ZPL label generation for a unit without a technical sheet', function (): void {
    $record = WtsInboundRecord::factory()->create(['technical_sheet_snapshot_id' => null]);
    $lot = WtsInboundLot::factory()->create(['inbound_record_id' => $record->id]);
    $unit = WtsUnit::factory()->create(['inbound_lot_id' => $lot->id, 'technical_sheet_snapshot_id' => null]);

    expect(fn () => app(Modules\Wts\Actions\GenerateWtsUnitLabelZplAction::class)->execute($unit))
        ->toThrow(RuntimeException::class, 'etichetta non generabile');
});
