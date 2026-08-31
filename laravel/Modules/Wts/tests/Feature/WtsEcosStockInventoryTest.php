<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Ecos\EcosOdbcConnection;
use Modules\Wts\Actions\FetchEcosStockSnapshotAction;
use Modules\Wts\Datas\WtsInventoryRow;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;

it('maps ECOS stock rows from the bspart/bscol snapshot query', function (): void {
    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')
        ->once()
        ->andReturn([
            [
                'batch_code' => 'PART-001',
                'unit_sub_code' => '12',
                'sheet_code' => 'ST-944',
                'waste_code' => '12 01 03',
                'weight_kg' => 1238.5,
                'volume' => 0,
                'colli_count' => 6,
                'zone_code' => 'WTS1',
                'zone_label' => 'WTS1 - Da lavorare',
                'production_description' => 'Sfridi da linea stampa',
                'packaging_description' => 'Big bag misti',
                'note_1' => 'Linea 3',
                'note_2' => 'Ciclo cartotecnica',
                'form_reference' => 'ING/2026/118871',
            ],
        ]);

    app()->instance(EcosOdbcConnection::class, $connection);

    $result = app(FetchEcosStockSnapshotAction::class)->execute('2026-06-10');

    expect($result['error'])->toBeNull()
        ->and($result['rows'])->toHaveCount(1);

    $row = $result['rows'][0];
    expect($row)->toBeInstanceOf(WtsInventoryRow::class)
        ->and($row->batchCode)->toBe('PART-001')
        ->and($row->sheetCode)->toBe('ST-944')
        ->and($row->wasteCode)->toBe('12 01 03')
        ->and($row->weightKg)->toBe(1238.5)
        ->and($row->note1)->toBe('Linea 3')
        ->and($row->source)->toBe('ecos');
});

it('builds the UI-12 inventory overview from ECOS when sync stock is enabled', function (): void {
    config()->set('wts.ecos.sync_stock', true);

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')
        ->once()
        ->andReturn([
            [
                'batch_code' => 'PART-001',
                'unit_sub_code' => '12',
                'sheet_code' => 'ST-944',
                'waste_code' => '12 01 03',
                'weight_kg' => 500,
                'volume' => 0,
                'colli_count' => 2,
                'zone_code' => 'WTS1',
                'zone_label' => 'WTS1',
                'production_description' => 'Sfridi',
                'packaging_description' => null,
                'note_1' => null,
                'note_2' => null,
                'form_reference' => 'ING/2026/118871',
            ],
            [
                'batch_code' => 'PART-002',
                'unit_sub_code' => '1',
                'sheet_code' => 'ST-945',
                'waste_code' => '15 01 10*',
                'weight_kg' => 75,
                'volume' => 0,
                'colli_count' => 1,
                'zone_code' => 'PRESS',
                'zone_label' => 'Reparto pressa',
                'production_description' => 'Imballaggi verniciati',
                'packaging_description' => null,
                'note_1' => null,
                'note_2' => null,
                'form_reference' => 'ING/2026/118872',
            ],
        ]);

    app()->instance(EcosOdbcConnection::class, $connection);

    $overview = app(GetWtsInventoryOverviewQuery::class)->execute('12 01 03', 'WTS1', '2026-06-10');

    expect($overview['usesEcosStock'])->toBeTrue()
        ->and($overview['rows'])->toHaveCount(1)
        ->and($overview['rows']->first()?->batchCode)->toBe('PART-001')
        ->and($overview['stats']['total'])->toBe(1)
        ->and($overview['dataSource']['label'])->toBe('DATI REALI ECOS');
});

it('keeps local unit inventory when ECOS stock sync is disabled', function (): void {
    config()->set('wts.ecos.sync_stock', false);

    $lot = WtsInboundLot::factory()->create();
    WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'status_code' => 'available',
        'current_area_code' => 'to_process',
        'unit_code' => 'LOT-LOCAL-01',
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)->execute();

    expect($overview['usesEcosStock'])->toBeFalse()
        ->and($overview['rows'])->toHaveCount(1)
        ->and($overview['rows']->first()?->unitCode)->toBe('LOT-LOCAL-01')
        ->and($overview['dataSource']['label'])->toBe('DATI APPLICATIVI LOCALI');
});

it('marks local units as consumed at the inventory reference date', function (): void {
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

it('defaults the ECOS stock reference moment to end of today when no date is given', function (): void {
    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')
        ->once()
        ->andReturn([]);

    app()->instance(EcosOdbcConnection::class, $connection);

    $result = app(FetchEcosStockSnapshotAction::class)->execute();

    expect($result['error'])->toBeNull()
        ->and($result['rows'])->toBeEmpty();
});

it('skips ECOS stock rows without a batch code', function (): void {
    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')
        ->once()
        ->andReturn([
            [
                'batch_code' => '',
                'unit_sub_code' => '1',
                'sheet_code' => 'ST-1',
                'waste_code' => '12 01 03',
                'weight_kg' => 10,
                'volume' => 0,
                'colli_count' => 1,
                'zone_code' => 'WTS1',
                'zone_label' => 'WTS1',
                'production_description' => null,
                'packaging_description' => null,
                'note_1' => null,
                'note_2' => null,
                'form_reference' => null,
            ],
        ]);

    app()->instance(EcosOdbcConnection::class, $connection);

    $result = app(FetchEcosStockSnapshotAction::class)->execute('2026-06-10');

    expect($result['error'])->toBeNull()
        ->and($result['rows'])->toBeEmpty();
});

it('does not filter ECOS rows by waste code or zone when no filters are provided', function (): void {
    config()->set('wts.ecos.sync_stock', true);

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')
        ->once()
        ->andReturn([
            [
                'batch_code' => 'PART-010',
                'unit_sub_code' => '1',
                'sheet_code' => 'ST-010',
                'waste_code' => '12 01 03',
                'weight_kg' => 10,
                'volume' => 0,
                'colli_count' => 1,
                'zone_code' => 'WTS1',
                'zone_label' => 'WTS1',
                'production_description' => null,
                'packaging_description' => null,
                'note_1' => null,
                'note_2' => null,
                'form_reference' => null,
            ],
        ]);

    app()->instance(EcosOdbcConnection::class, $connection);

    $overview = app(GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-10');

    expect($overview['rows'])->toHaveCount(1)
        ->and($overview['rows']->first()?->batchCode)->toBe('PART-010');
});

it('reports the ECOS data source as real but empty when the query returns no open batches', function (): void {
    config()->set('wts.ecos.sync_stock', true);

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')
        ->once()
        ->andReturn([]);

    app()->instance(EcosOdbcConnection::class, $connection);

    $overview = app(GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-10');

    expect($overview['rows'])->toBeEmpty()
        ->and($overview['dataSource']['label'])->toBe('DATI REALI ECOS')
        ->and($overview['dataSource']['description'])->toContain('non ha restituito partite aperte');
});

it('reports ECOS stock data source error state when the ODBC query fails', function (): void {
    config()->set('wts.ecos.sync_stock', true);

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')
        ->once()
        ->andThrow(new RuntimeException('Driver assente'));

    app()->instance(EcosOdbcConnection::class, $connection);

    $overview = app(GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-10');

    expect($overview['rows'])->toBeEmpty()
        ->and($overview['dataSource']['label'])->toBe('DATI DEMO - ECOS NON RAGGIUNGIBILE')
        ->and($overview['dataSource']['description'])->toContain('Driver assente');
});
