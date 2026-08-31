<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Illuminate\Support\Collection;
use Livewire\Livewire;
use Modules\User\Models\User;
use Modules\Wts\Filament\Pages\WtsFinishedMaterialPage;
use Modules\Wts\Filament\Pages\WtsIngressesPage;
use Modules\Wts\Filament\Pages\WtsInventoryPage;
use Modules\Wts\Filament\Pages\WtsMaterialDetailPage;
use Modules\Wts\Filament\Pages\WtsProcessingPage;
use Modules\Wts\Filament\Pages\WtsSortingPage;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsIngressOverviewQuery;
use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;
use Modules\Wts\Support\WtsLegacyPayload;
use Modules\Wts\Tests\TestCase;
use Spatie\Permission\Models\Role;

it('covers empty selection and invalid config branches in processing page', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.processing_step_types', 'invalid');
    config()->set('wts.operational_parameters', 'invalid');

    Livewire::actingAs($user);
    Livewire::test(WtsProcessingPage::class)
        ->set('selectedUnitIds', [])
        ->call('processSelection')
        ->assertHasNoErrors()
        ->assertViewHas('stepLabels', [])
        ->assertViewHas('operationalParameters', []);
});

it('covers dirty drum and pallet output branches in processing form trait', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.stock_views', 'invalid');

    $lot = WtsInboundLot::factory()->create();
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsSortingPage::class)
        ->set('selectedUnitIds', [$unit->id])
        ->set('workedWeight', '50') // >= output (fusto sporco 22 + bancale 20) per bilancio valido
        ->set('dirtyDrumsCount', 1)
        ->set('palletsCount', 1)
        ->call('registerProcessing')
        ->assertHasNoErrors()
        ->assertViewHas('stockViews', []);

    $step = WtsProcessingStep::query()->latest('id')->first();

    expect($step?->derivedMaterials()->count())->toBe(2)
        ->and($step?->derivedMaterials()->pluck('output_type_code')->all())->toBe(['dirty_drum', 'pallet']);
});

it('covers document key filtering fallback in ingress overview query', function (): void {
    config()->set('wts.legacy_ingress_document_keys', [123, 'document_type']);
    config()->set('wts.legacy_ingress_document_prefixes', 'invalid');

    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'legacy_payload' => [
            'document_type' => 'INV-2026-01',
        ],
    ]);

    $record = WtsInboundRecord::factory()->create([
        'technical_sheet_snapshot_id' => $snapshot->id,
        'source_company_name' => 'Cliente finale',
    ]);

    $results = app(GetWtsIngressOverviewQuery::class)->execute();

    expect($results->pluck('id')->all())->toContain($record->id);
});

it('covers empty string branch in legacy payload date parsing', function (): void {
    config()->set('wts.legacy_movement_date_keys', ['movement_date']);

    expect(WtsLegacyPayload::movementDate(['movement_date' => '   '], null))->toBeNull();
});

it('covers scalar string executed_at parsing branch in inventory overview query', function (): void {
    $unit = WtsUnit::factory()->make([
        'status_code' => 'available',
        'current_area_code' => 'to_process',
    ]);

    $unit->setRelation('processingSteps', new Collection([
        new class()
        {
            public string $executed_at = '2026-06-03 10:00:00';
        },
    ]));

    $reflection = new ReflectionClass(GetWtsInventoryOverviewQuery::class);
    $method = $reflection->getMethod('applyEffectiveSnapshotState');
    $method->setAccessible(true);

    /** @var \Modules\Wts\Models\WtsUnit $evaluated */
    $evaluated = $method->invoke(app(GetWtsInventoryOverviewQuery::class), $unit, '2026-06-03');

    expect($evaluated->getAttribute('effective_status_code'))->toBe('finished')
        ->and($evaluated->getAttribute('effective_area_code'))->toBe('finished');
});

it('covers invalid config branches in ingresses and inventory pages', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.unit_types', 'invalid');
    config()->set('wts.stock_views', 'invalid');

    Livewire::actingAs($user);
    Livewire::test(WtsIngressesPage::class)
        ->assertViewHas('unitTypeLabels', []);

    Livewire::actingAs($user);
    Livewire::test(WtsInventoryPage::class)
        ->assertViewHas('stockViews', []);
});

it('covers empty fallback branches in finished material and material detail pages', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    config()->set('wts.stock_views', 'invalid');

    Livewire::actingAs($user);
    Livewire::test(WtsFinishedMaterialPage::class)
        ->assertSet('materialId', null)
        ->assertViewHas('selectedMaterial', null);

    Livewire::actingAs($user);
    Livewire::test(WtsMaterialDetailPage::class)
        ->assertViewHas('stockViews', []);
});
