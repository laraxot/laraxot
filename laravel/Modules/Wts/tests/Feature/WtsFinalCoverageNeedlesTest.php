<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsProcessingPage;
use Modules\User\Models\User;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsIngressOverviewQuery;
use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;
use Modules\Wts\Support\WtsLegacyPayload;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('covers remaining direct helper branches in WtsLegacyPayload', function (): void {
    expect(WtsLegacyPayload::firstString(['foo' => '   '], ['foo']))->toBeNull()
        ->and(WtsLegacyPayload::movementDate([], CarbonImmutable::parse('2026-06-10'))?->toDateString())->toBe('2026-06-10')
        ->and(WtsLegacyPayload::matchesDate([], CarbonImmutable::parse('2026-06-10'), ''))->toBeTrue();
});

it('covers attempted no rows and config fallback branches in ingress overview query', function (): void {
    config()->set('wts.legacy_ingress_document_keys', 'invalid');
    config()->set('wts.legacy_ingress_document_prefixes', 'invalid');

    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'legacy_payload' => [
            'imp_cod_pr' => 123,
            'imp_uni_pr' => '11',
            'document_type' => 'INV-2026-01',
        ],
    ]);

    $record = WtsInboundRecord::factory()->create([
        'source_company_name' => 'Cliente test',
        'technical_sheet_snapshot_id' => $snapshot->id,
    ]);

    $query = app(GetWtsIngressOverviewQuery::class);
    $results = $query->execute();

    expect($results->pluck('id')->all())->toContain($record->id);

    $reflection = new \ReflectionClass($query);
    $property = $reflection->getProperty('lastEcosSync');
    $property->setAccessible(true);
    $property->setValue($query, ['attempted' => true, 'imported' => 0, 'error' => null]);

    $status = $query->dataSourceStatus(new EloquentCollection);

    expect($status['label'])->toBe('DATI DEMO - NESSUN INGRESSO ECOS');
});

it('warns about stale ECOS data when the last sync failed but rows exist', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'legacy_system' => 'ecos',
    ]);
    $record = WtsInboundRecord::factory()->create([
        'technical_sheet_snapshot_id' => $snapshot->id,
    ]);
    $record->setRelation('technicalSheetSnapshot', $snapshot);

    $query = app(GetWtsIngressOverviewQuery::class);

    $reflection = new \ReflectionClass($query);
    $property = $reflection->getProperty('lastEcosSync');
    $property->setAccessible(true);
    $property->setValue($query, ['attempted' => true, 'imported' => 0, 'error' => 'Connessione ECOS caduta.']);

    $status = $query->dataSourceStatus(new EloquentCollection([$record]));

    expect($status['label'])->toBe('DATI ECOS NON AGGIORNATI')
        ->and($status['description'])->toContain('Connessione ECOS caduta.');
});

it('covers remaining processing page utility branches and config normalization', function (): void {
    Role::findOrCreate('wts_admin');

    $user = User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $lot = WtsInboundLot::factory()->create();
    $unitA = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);
    $unitB = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
    ]);

    config()->set('wts.processing_step_types', [
        'sorting' => 'Selezione',
        99 => 'Da ignorare',
        'pressing' => ['invalid'],
    ]);
    config()->set('wts.operational_parameters', [
        'standard_drum_weight_kg' => [
            'label' => 'Peso',
            'value' => 18,
            'unit' => 'kg',
            99 => 'skip',
        ],
        77 => 'invalid',
        'broken' => 'invalid',
    ]);

    Livewire::actingAs($user);
    /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
    $livewire = Livewire::withQueryParams(['lot' => $lot->id])
        ->test(WtsProcessingPage::class);
    $livewire->call('deselectUnit', $unitA->id)
        ->set('lotId', $lot->id);
    $livewire->assertViewHas('stepLabels', [
        'sorting' => 'Selezione',
        'pressing' => '',
        ]);
    $livewire->assertViewHas('operationalParameters', [
        'standard_drum_weight_kg' => [
        'label' => 'Peso',
        'value' => 18,
        'unit' => 'kg',
        ],
        ]);
    $livewire->assertHasNoErrors();

    expect([$unitA->id, $unitB->id])->toContain($unitB->id);
});

it('covers inventory snapshot branch with executed_at timestamps on processing steps', function (): void {
    config()->set('wts.ecos.sync_stock', false);

    $lot = WtsInboundLot::factory()->create();

    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
        'current_area_code' => 'to_process',
        'created_at' => '2026-06-01 08:00:00',
        'updated_at' => '2026-06-01 08:00:00',
    ]);

    $step = WtsProcessingStep::factory()->create([
        'executed_at' => '2026-06-03 10:00:00',
    ]);
    $step->selectedUnits()->sync([$unit->id]);

    $before = app(GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-02');
    $after = app(GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-03');

    expect($before['rows']->first()?->statusCode)->toBe('available')
        ->and($before['rows']->first()?->zoneCode)->toBe('to_process')
        ->and($after['rows']->first()?->statusCode)->toBe('finished')
        ->and($after['rows']->first()?->zoneCode)->toBe('finished');
});
