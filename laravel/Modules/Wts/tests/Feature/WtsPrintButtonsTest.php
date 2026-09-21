<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsFinishedMaterialPage;
use Modules\Wts\Filament\Pages\WtsInventoryPage;
use Modules\Wts\Filament\Pages\WtsProcessingSheetPage;
use Modules\Wts\Filament\Pages\WtsReportsPage;
use Modules\User\Models\User;
use Modules\Wts\Actions\BuildWtsOutputQrPayloadAction;
use Modules\Wts\Actions\GenerateWtsOutputLabelPdfAction;
use Modules\Wts\Actions\GenerateWtsUnitLabelsBatchPdfAction;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('builds a structured QR payload for a derived material output', function (): void {
    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-OUT-QR-01',
    ]);
    $material = WtsDerivedMaterial::factory()->create([
        'processing_step_id' => $step->id,
        'output_code' => 'OUT-QR-01',
    ]);

    $payload = app(BuildWtsOutputQrPayloadAction::class)->execute($material);

    expect($payload)
        ->toContain('OUTPUT=OUT-QR-01')
        ->toContain('SHEET=WS-OUT-QR-01')
        ->toContain('CER='.$material->technicalSheetSnapshot?->waste_code);
});

it('falls back to N/D when a derived material has no processing step or technical sheet', function (): void {
    $material = new WtsDerivedMaterial([
        'output_code' => 'OUT-QR-ORPHAN',
    ]);
    $material->setRelation('processingStep', null);
    $material->setRelation('technicalSheetSnapshot', null);
    $material->exists = true;

    $payload = app(BuildWtsOutputQrPayloadAction::class)->execute($material);

    expect($payload)
        ->toContain('SHEET=N/D')
        ->toContain('CER=N/D');
});

it('generates a PDF label for a derived material output', function (): void {
    $material = WtsDerivedMaterial::factory()->create([
        'output_code' => 'OUT-PDF-01',
    ]);

    $pdf = app(GenerateWtsOutputLabelPdfAction::class)->execute($material);

    expect($pdf)->toStartWith('%PDF')
        ->and(strlen($pdf))->toBeGreaterThan(500);
});

it('generates a batch PDF with one label per unit', function (): void {
    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = WtsUnit::factory()->count(2)->create();

    $pdf = app(GenerateWtsUnitLabelsBatchPdfAction::class)->execute(
        new Collection($units->all()),
    );

    expect($pdf)->toStartWith('%PDF')
        ->and(strlen($pdf))->toBeGreaterThan(500);
});

it('generates a valid empty batch PDF when there are no units', function (): void {
    $pdf = app(GenerateWtsUnitLabelsBatchPdfAction::class)->execute(new Collection);

    expect($pdf)->toStartWith('%PDF');
});

it('downloads the processing sheet PDF from the processing sheet page', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-DOWNLOAD-01',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['step' => $step->id])
        ->test(WtsProcessingSheetPage::class)
        ->call('downloadSheetPdf')
        ->assertHasNoErrors();
});

it('returns nothing when downloading a processing sheet PDF without a selected step', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    Livewire::test(WtsProcessingSheetPage::class)
        ->call('downloadSheetPdf')
        ->assertHasNoErrors();
});

it('downloads a step sheet PDF from the reports page', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-REPORT-01',
    ]);

    Livewire::actingAs($user);
    Livewire::test(WtsReportsPage::class)
        ->call('downloadStepPdf', $step->id)
        ->assertHasNoErrors();
});

it('returns nothing when downloading a missing step sheet PDF from the reports page', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    Livewire::test(WtsReportsPage::class)
        ->call('downloadStepPdf', 999999)
        ->assertHasNoErrors();
});

it('downloads the full processing report PDF from the reports page', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    WtsProcessingStep::factory()->create();

    Livewire::actingAs($user);
    Livewire::test(WtsReportsPage::class)
        ->call('downloadFullReportPdf')
        ->assertHasNoErrors();
});

it('reprints the output label PDF from the finished material page', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $material = WtsDerivedMaterial::factory()->create([
        'output_code' => 'OUT-FIN-PRINT-01',
    ]);

    actingAs($user);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['material' => $material->id])
        ->test(WtsFinishedMaterialPage::class)
        ->call('reprintOutputLabel')
        ->assertHasNoErrors();

    expect($material->fresh()?->labelPrints()->count())->toBeGreaterThan(0);
});

it('returns nothing when reprinting an output label without a selected material', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    Livewire::test(WtsFinishedMaterialPage::class)
        ->call('reprintOutputLabel')
        ->assertHasNoErrors();
});

it('downloads the WTS inventory PDF with the default reference date', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    WtsUnit::factory()->create();

    Livewire::actingAs($user);
    Livewire::test(WtsInventoryPage::class)
        ->call('downloadInventoryPdf')
        ->assertHasNoErrors();
});

it('downloads the WTS inventory PDF using an explicit reference date', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    Livewire::test(WtsInventoryPage::class)
        ->set('referenceDate', '2026-01-15')
        ->call('downloadInventoryPdf')
        ->assertHasNoErrors();
});

it('falls back to today when the WTS inventory reference date is cleared', function (): void {
    Role::findOrCreate('wts_admin');
    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    Livewire::actingAs($user);
    Livewire::test(WtsInventoryPage::class)
        ->set('referenceDate', '')
        ->call('downloadInventoryPdf')
        ->assertHasNoErrors();
});
