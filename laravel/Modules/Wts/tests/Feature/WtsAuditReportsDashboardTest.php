<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\User\Models\User;
use Modules\Wts\Actions\GenerateWtsInventoryPdfAction;
use Modules\Wts\Actions\GenerateWtsProcessingReportPdfAction;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsDashboardStatsQuery;
use Modules\Wts\Queries\SearchWtsAuditQuery;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('searches the WTS audit chain by formulario lot unit or output', function (): void {
    $unit = WtsUnit::factory()->create([
        'unit_code' => 'UNIT-AUDIT-001',
    ]);
    $unit->inboundLot?->inboundRecord?->forceFill([
        'form_number' => 'FIR-AUDIT-001',
    ])->save();

    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-AUDIT-001',
    ]);
    $step->selectedUnits()->sync([$unit->id]);

    $material = WtsDerivedMaterial::factory()->create([
        'processing_step_id' => $step->id,
        'output_code' => 'OUT-AUDIT-001',
    ]);

    $result = app(SearchWtsAuditQuery::class)->execute('OUT-AUDIT-001');

    expect($result['record']?->form_number)->toBe('FIR-AUDIT-001')
        ->and($result['unit']?->unit_code)->toBe('UNIT-AUDIT-001')
        ->and($result['processingSteps']->first()?->is($step))->toBeTrue()
        ->and($result['derivedMaterial']?->is($material))->toBeTrue();
});

it('generates processing report and inventory PDFs', function (): void {
    WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-REPORT-001',
    ]);
    WtsUnit::factory()->count(2)->create();

    $processingPdf = app(GenerateWtsProcessingReportPdfAction::class)->execute();
    $inventoryPdf = app(GenerateWtsInventoryPdfAction::class)->execute(null, null, now()->toDateString());

    expect($processingPdf)->toStartWith('%PDF')
        ->and(strlen($processingPdf))->toBeGreaterThan(1000)
        ->and($inventoryPdf)->toStartWith('%PDF')
        ->and(strlen($inventoryPdf))->toBeGreaterThan(1000);
});

it('shows real dashboard counters on the WTS dashboard', function (): void {
    Role::findOrCreate('wts_admin');
    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    WtsInboundRecord::factory()->create([
        'received_at' => now(),
    ]);
    WtsUnit::factory()->create([
        'status_code' => 'available',
        'current_area_code' => 'to_process',
    ]);
    WtsProcessingStep::factory()->create([
        'executed_at' => now(),
    ]);

    $stats = app(GetWtsDashboardStatsQuery::class)->execute();

    expect($stats['ingresses_today'])->toBeGreaterThanOrEqual(1)
        ->and($stats['units_to_process'])->toBeGreaterThanOrEqual(1)
        ->and($stats['processings_today'])->toBeGreaterThanOrEqual(1);

    actingAs($user)
        ->get('/wts')
        ->assertOk()
        ->assertSee('Ingressi oggi')
        ->assertSee('Colli da lavorare')
        ->assertSee('Lavorazioni oggi');
});
