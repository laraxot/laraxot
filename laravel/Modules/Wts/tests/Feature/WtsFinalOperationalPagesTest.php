<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsAuditPage;
use Modules\Wts\Filament\Pages\WtsOutputsPage;
use Modules\Wts\Filament\Pages\WtsProcessingSheetPage;
use Modules\User\Models\User;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('renders the real finished material page with origin chain', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-FIN-001',
        'step_type_code' => 'sorting',
    ]);
    $unit = WtsUnit::factory()->create([
        'unit_code' => 'UNIT-FIN-001',
    ]);
    $step->selectedUnits()->sync([$unit->id]);

    $material = WtsDerivedMaterial::factory()->create([
        'processing_step_id' => $step->id,
        'output_code' => 'FIN-2026-003',
        'output_type_code' => 'plastic_ready',
        'container_type_code' => 'big_bag',
        'quantity' => 1,
        'weight' => 40,
    ]);

    actingAs($user);
    get('/wts/finished-material?material='.$material->id)
        ->assertOk()
        ->assertSee('FIN-2026-003')
        ->assertSee('UNIT-FIN-001')
        ->assertSee((string) $unit->inboundLot?->lot_code)
        ->assertSee((string) $unit->inboundLot?->inboundRecord?->form_number);
});

it('renders the real audit page results for a searched code', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-AUDIT-REAL-01',
    ]);
    $unit = WtsUnit::factory()->create([
        'unit_code' => 'UNIT-AUDIT-REAL-01',
    ]);
    $step->selectedUnits()->sync([$unit->id]);
    $material = WtsDerivedMaterial::factory()->create([
        'processing_step_id' => $step->id,
        'output_code' => 'OUT-AUDIT-REAL-01',
    ]);

    actingAs($user);
    get('/wts/audit?search=OUT-AUDIT-REAL-01')
        ->assertOk()
        ->assertSee('OUT-AUDIT-REAL-01')
        ->assertSee('UNIT-AUDIT-REAL-01')
        ->assertSee((string) $unit->inboundLot?->inboundRecord?->form_number);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['search' => 'OUT-AUDIT-REAL-01'])
        ->test(WtsAuditPage::class)
        ->assertSet('search', 'OUT-AUDIT-REAL-01');
});

it('renders the real processing sheet page for a selected step', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-SHEET-REAL-01',
        'step_type_code' => 'unpackaging',
    ]);

    actingAs($user);
    get('/wts/processing-sheet?step='.$step->id)
        ->assertOk()
        ->assertSee('WS-SHEET-REAL-01')
        ->assertSee('unpackaging');

    Livewire::actingAs($user);
    Livewire::withQueryParams(['step' => $step->id])
        ->test(WtsProcessingSheetPage::class)
        ->assertSet('stepId', $step->id);
});

it('reprints a derived material label from outputs page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $material = WtsDerivedMaterial::factory()->create([
        'output_code' => 'OUT-LABEL-01',
    ]);

    Livewire::actingAs($user);
    Livewire::test(WtsOutputsPage::class)
        ->call('reprintOutputLabel', $material->id)
        ->assertHasNoErrors();

    expect($material->fresh()?->labelPrints()->count())->toBeGreaterThan(0);
});
