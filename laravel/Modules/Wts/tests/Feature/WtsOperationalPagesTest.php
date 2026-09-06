<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\User\Models\User;
use Modules\Wts\Actions\GenerateWtsUnitSeriesAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Queries\GetWtsIngressOverviewQuery;
use Modules\Wts\Queries\GetWtsWorkQueueOverviewQuery;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('shows legacy technical sheet details in the WTS ingress page and links directly to label generation', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'hp_codes' => 'HP4, HP14',
        'note_1' => 'Linea trattamento 3',
        'note_2' => 'Ciclo produzione cartotecnica',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'incoming',
    ]);

    actingAs($user);
    get('/wts/ingresses')
        ->assertOk()
        ->assertSee('SR2024-04817')
        ->assertSee('Grafica Veneta Spa')
        ->assertSee('HP4, HP14')
        ->assertSee('FIR-2026-001')
        ->assertSee('PART-778')
        ->assertSee('/wts/lot-detail?lot='.$lot->id, false)
        ->assertDontSee('Apri dettaglio ERP');
});

it('shows take charge button when an ingress has no lot yet', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'received_at' => now(),
    ]);

    actingAs($user);
    get('/wts/ingresses')
        ->assertOk()
        ->assertSee('Prendi in carico')
        ->assertSee('takeChargeAndOpenLot('.$record->id.')', false)
        ->assertDontSee('/wts/new-ingress?record='.$record->id, false)
        ->assertDontSee('Genera colli e QCODE');
});

it('shows work queue button when an ingress lot already has units', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $record = WtsInboundRecord::factory()->create([
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::factory()->create([
        'inbound_record_id' => $record->id,
    ]);

    app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: $record->technicalSheetSnapshot,
        printReasonCode: 'initial_print',
    );

    actingAs($user);
    get('/wts/ingresses')
        ->assertOk()
        ->assertSee('Giacenza')
        ->assertSee('/wts/work-queue', false)
        ->assertSee('/wts/lot-detail?lot='.$lot->id, false);
});

it('can search the ingress overview by technical sheet description', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'production_description' => 'Sfridi da linea stampa',
        'packaging_description' => 'Pallet e IBC contaminati',
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $resultsByProduction = app(GetWtsIngressOverviewQuery::class)->execute('linea stampa');
    $resultsByPackaging = app(GetWtsIngressOverviewQuery::class)->execute('IBC contaminati');

    expect($resultsByProduction)->toHaveCount(1)
        ->and($resultsByPackaging)->toHaveCount(1)
        ->and($resultsByPackaging->first()?->technicalSheetSnapshot?->sheet_code)->toBe('SR2024-04817');
});

it('can filter the ingress overview by received date', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1002',
        'form_number' => 'FIR-2026-002',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'CD456EF',
        'gross_quantity' => 980,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now()->subDay(),
    ]);

    $results = app(GetWtsIngressOverviewQuery::class)->execute(null, now()->toDateString());

    expect($results)->toHaveCount(1)
        ->and($results->first()?->form_number)->toBe('FIR-2026-001');
});

it('prefers the legacy movement date over received_at when filtering ingresses', function (): void {
    // La gerarchia delle date (mte_dat_part -> mte_dat_r -> received_at) si legge
    // dal payload del VIAGGIO. Quello della scheda tecnica qui punta a un altro
    // giorno apposta: e' condiviso da tutti i conferimenti con lo stesso ST e
    // non deve spostare la riga di giornata.
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'legacy_payload' => [
            'mte_dat_part' => '2026-06-10 08:30:00',
        ],
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => '2026-06-04 09:00:00',
        'legacy_payload' => [
            'mte_dat_part' => '2026-06-03 08:30:00',
        ],
    ]);

    $results = app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-06-03');

    expect($results)->toHaveCount(1)
        ->and($results->first()?->form_number)->toBe('FIR-2026-001')
        ->and(app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-06-10'))->toHaveCount(0);
});

it('excludes internal WTS producers from the ingress overview', function (): void {
    $externalSnapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    $internalSnapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04818',
        'producer_name' => 'Waste Treatment Solution Srl',
        'waste_code' => '19 12 05',
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $externalSnapshot->id,
        'received_at' => now(),
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1002',
        'form_number' => 'FIR-2026-002',
        'source_company_name' => 'Waste Treatment Solution Srl',
        'vehicle_reference' => 'CD456EF',
        'gross_quantity' => 980,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $internalSnapshot->id,
        'received_at' => now(),
    ]);

    $results = app(GetWtsIngressOverviewQuery::class)->execute();

    expect($results)->toHaveCount(1)
        ->and($results->first()?->form_number)->toBe('FIR-2026-001');
});

it('filters the ingress overview by allowed legacy document prefixes', function (): void {
    $allowedSnapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'legacy_payload' => [
            'document_type' => 'ING-001',
        ],
    ]);

    $blockedSnapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04818',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'legacy_payload' => [
            'document_type' => 'OUT-001',
        ],
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $allowedSnapshot->id,
        'received_at' => now(),
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1002',
        'form_number' => 'FIR-2026-002',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'CD456EF',
        'gross_quantity' => 980,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $blockedSnapshot->id,
        'received_at' => now(),
    ]);

    $results = app(GetWtsIngressOverviewQuery::class)->execute();

    expect($results)->toHaveCount(1)
        ->and($results->first()?->form_number)->toBe('FIR-2026-001');
});

it('excludes legacy WTS 99 source movements from the ingress overview', function (): void {
    $externalSnapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'legacy_payload' => [
            'document_type' => 'ING-001',
            'imp_cod_pr' => 'EXT',
            'imp_uni_pr' => 1,
        ],
    ]);

    $internalSnapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04818',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'legacy_payload' => [
            'document_type' => 'ING-002',
            'imp_cod_pr' => 'WTS',
            'imp_uni_pr' => 99,
        ],
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $externalSnapshot->id,
        'received_at' => now(),
    ]);

    WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1002',
        'form_number' => 'FIR-2026-002',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'CD456EF',
        'gross_quantity' => 980,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $internalSnapshot->id,
        'received_at' => now(),
    ]);

    $results = app(GetWtsIngressOverviewQuery::class)->execute();

    expect($results)->toHaveCount(1)
        ->and($results->first()?->form_number)->toBe('FIR-2026-001');
});

it('shows generated units in the operational stock page and processing entry point', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 2,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    actingAs($user);
    get('/wts/work-queue')
        ->assertOk()
        ->assertSee('LOT-2026-0001-BIG-BAG-01')
        ->assertSee('SR2024-04817')
        ->assertSee('/wts/processing?lot='.$lot->id, false)
        ->assertSee('Giacenza operativa WTS');
});

it('can search the work queue by sheet code and waste code', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'production_description' => 'Sfridi da linea stampa',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    $resultsBySheet = app(GetWtsWorkQueueOverviewQuery::class)->execute('to_process', 'SR2024-04817');
    $resultsByWaste = app(GetWtsWorkQueueOverviewQuery::class)->execute('to_process', '15 01 02');

    expect($resultsBySheet)->toHaveCount(1)
        ->and($resultsByWaste)->toHaveCount(1)
        ->and($resultsByWaste->first()?->technicalSheetSnapshot?->production_description)->toBe('Sfridi da linea stampa');
});

it('keeps technical identifiers as a secondary search in the work queue', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'production_description' => 'Sfridi da linea stampa',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 1,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    $resultsByOperationalSearch = app(GetWtsWorkQueueOverviewQuery::class)->execute('to_process', 'LOT-2026-0001');
    $resultsByTechnicalSearch = app(GetWtsWorkQueueOverviewQuery::class)->execute('to_process', null, 'LOT-2026-0001');

    expect($resultsByOperationalSearch)->toHaveCount(0)
        ->and($resultsByTechnicalSearch)->toHaveCount(1)
        ->and($resultsByTechnicalSearch->first()?->unit_code)->toBe(($units[0] ?? throw new \RuntimeException('missing unit 0'))->unit_code);
});

it('excludes already finished units from the operational work queue', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 2,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    ($units[1] ?? throw new \RuntimeException('missing unit 1'))->forceFill([
        'status_code' => 'finished',
        'current_area_code' => 'to_process',
    ])->save();

    $results = app(GetWtsWorkQueueOverviewQuery::class)->execute('to_process');

    expect($results)->toHaveCount(1)
        ->and($results->first()?->unit_code)->toBe(($units[0] ?? throw new \RuntimeException('missing unit 0'))->unit_code);
});

it('shows the WTS printable inventory with technical notes and consumed state', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'production_description' => 'Sfridi da linea stampa',
        'note_1' => 'Linea trattamento 3',
        'note_2' => 'Ciclo produzione cartotecnica',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'PROGRAMMAZIONE-2026-05-15',
        'form_number' => 'FIR-2026-001',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 2,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    ($units[1] ?? throw new \RuntimeException('missing unit 1'))->forceFill([
        'status_code' => 'finished',
        'current_area_code' => 'finished',
    ])->save();

    actingAs($user);
    get('/wts/inventory')
        ->assertOk()
        ->assertSee('Stampa giacenza WTS')
        ->assertSee('LOT-2026-0001')
        ->assertSee('15 01 02')
        ->assertSee('Sfridi da linea stampa')
        ->assertSee('Linea trattamento 3')
        ->assertSee('Ciclo produzione cartotecnica')
        ->assertSee('Disponibile WTS1')
        ->assertSee('Gia lavorato');
});

it('builds the inventory snapshot using the selected reference date', function (): void {
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'production_description' => 'Sfridi da linea stampa',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'PROGRAMMAZIONE-2026-05-15',
        'form_number' => 'FIR-2026-001',
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'status_code' => 'ready_for_processing',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
    ]);

    /** @var \Illuminate\Support\Collection<int, \Modules\Wts\Models\WtsUnit> $units */
    $units = app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 2,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    ($units[0] ?? throw new \RuntimeException('missing unit 0'))->forceFill([
        'created_at' => '2026-06-01 10:00:00',
        'updated_at' => '2026-06-01 10:00:00',
    ])->save();

    ($units[1] ?? throw new \RuntimeException('missing unit 1'))->forceFill([
        'created_at' => '2026-06-03 10:00:00',
        'updated_at' => '2026-06-03 10:00:00',
    ])->save();

    $snapshotAtJuneFirst = app(\Modules\Wts\Queries\GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-01');
    $snapshotAtJuneThird = app(\Modules\Wts\Queries\GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-03');

    expect($snapshotAtJuneFirst['stats']['total'])->toBe(1)
        ->and($snapshotAtJuneThird['stats']['total'])->toBe(2);
});

it('renders the lot detail page with real lot data when a lot is selected', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'SR2024-04817',
        'producer_name' => 'Grafica Veneta Spa',
        'waste_code' => '15 01 02',
        'production_description' => 'Sfridi da linea stampa',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-1001',
        'form_number' => 'FIR-2026-001',
        'batch_number' => 'PART-778',
        'ecos_units_count' => 3,
        'source_company_name' => 'Grafica Veneta Spa',
        'vehicle_reference' => 'AB123CD',
        'gross_quantity' => 1250,
        'entry_weight' => 1238,
        'status_code' => 'ready_for_labelling',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-0001',
        'status_code' => 'registered',
        'current_area_code' => 'to_process',
        'initial_units_count' => 2,
    ]);

    app(GenerateWtsUnitSeriesAction::class)->execute(
        lot: $lot,
        unitTypeCode: 'big_bag',
        unitsCount: 2,
        technicalSheetSnapshot: $snapshot,
        printReasonCode: 'initial_print',
    );

    actingAs($user);
    get('/wts/lot-detail?lot='.$lot->id)
        ->assertOk()
        ->assertSee('LOT-2026-0001')
        ->assertSee('SR2024-04817')
        ->assertSee('FIR-2026-001')
        ->assertSee('Sfridi da linea stampa')
        ->assertSee('+ Aggiungi Big bag')
        ->assertSee('- Togli Big bag')
        ->assertSee('+ Aggiungi Pallet')
        ->assertSee('+ Aggiungi IBC')
        ->assertSee('Stampa tutte le etichette (etichettatrice)');
});

it('shows placeholder colli rows instead of fake BIGBAG demo rows when no units exist yet', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'ST-5',
        'producer_name' => 'Produttore test',
        'waste_code' => '15 01 02',
    ]);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-ST-5',
        'form_number' => 'FIR-ST-5',
        'source_company_name' => 'Produttore test',
        'gross_quantity' => 500,
        'entry_weight' => 490,
        'status_code' => 'taken_charge',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-ST5',
        'status_code' => 'registered',
        'current_area_code' => 'incoming',
        'initial_units_count' => 2,
    ]);

    actingAs($user);
    get('/wts/lot-detail?lot='.$lot->id)
        ->assertOk()
        ->assertSee('Collo 1 di 2')
        ->assertSee('Collo 2 di 2')
        ->assertSee('Da generare')
        ->assertSee('Anteprima')
        ->assertDontSee('BIGBAG-01')
        ->assertDontSee('BIGBAG-04');
});

it('shows an empty colli message when no units and no estimated count exist', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $record = WtsInboundRecord::query()->create([
        'legacy_reference' => 'ECOS-EMPTY',
        'form_number' => 'FIR-EMPTY',
        'source_company_name' => 'Produttore test',
        'gross_quantity' => 100,
        'entry_weight' => 100,
        'status_code' => 'taken_charge',
        'received_at' => now(),
    ]);

    $lot = WtsInboundLot::query()->create([
        'inbound_record_id' => $record->id,
        'lot_code' => 'LOT-2026-EMPTY',
        'status_code' => 'registered',
        'current_area_code' => 'incoming',
        'initial_units_count' => 0,
    ]);

    actingAs($user);
    get('/wts/lot-detail?lot='.$lot->id)
        ->assertOk()
        ->assertSee('Nessun collo generato')
        ->assertDontSee('BIGBAG-01')
        ->assertDontSee('Anteprima');
});

it('shows the static WTS mockup flow when the database has no demo seed', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    actingAs($user);
    get('/wts')
        ->assertOk()
        // Il riquadro "documenti legali demo" del sorgente linkava le pagine del
        // sito vetrina (route terms/privacy), che qui non esistono: rimosso dalla
        // dashboard e quindi non piu' atteso.
        ->assertSee('Flusso operativo da programmazione a WTS1');

    actingAs($user);
    get('/wts/ingresses')
        ->assertOk()
        ->assertSee('Conferimenti del giorno')
        ->assertSee('Data conferimenti')
        ->assertSee('Peso aperto')
        ->assertSee('Colli ECOS stimati')
        ->assertSee('CER/EER')
        ->assertSee('Colli ECOS');

    actingAs($user);
    get('/wts/lot-detail')
        ->assertOk()
        ->assertSee('Nessun lotto selezionato')
        ->assertSee('Seleziona un lotto dalla lista ingressi')
        ->assertSee('Torna alla lista ingressi');

    actingAs($user);
    get('/wts/work-queue')
        ->assertOk()
        ->assertSee('Giacenze per lavorazione')
        ->assertSee('Nessuna giacenza da lavorare al momento.')
        ->assertDontSee('Apri lavorazione assegnata');

    actingAs($user);
    get('/wts/material-detail')
        ->assertOk()
        ->assertSee('Dettaglio collo da giacenza')
        ->assertSee('Aperto da UI-05 o da QR')
        ->assertSee('Apri lavorazione assegnata');

    actingAs($user);
    get('/wts/processing')
        ->assertOk()
        ->assertSee('UI-07 - Seleziona i colli e apri la lavorazione')
        ->assertSee('Seleziona colli e apri triturazione');
});

it('renders the separate WTS processing pages', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    actingAs($user);
    get('/wts/sorting')
        ->assertOk()
        ->assertSee('Selezione e cernita')
        ->assertSee('Registra selezione e cernita');

    actingAs($user);
    get('/wts/unpackaging')
        ->assertOk()
        ->assertSee('Sconfezionamento')
        ->assertSee('Registra sconfezionamento');

    actingAs($user);
    get('/wts/grinding-washing')
        ->assertOk()
        ->assertSee('Triturazione e lavaggio')
        ->assertSee('Registra triturazione');

    actingAs($user);
    get('/wts/pressing')
        ->assertOk()
        ->assertSee('Pressatura')
        ->assertSee('Registra pressatura');

    actingAs($user);
    get('/wts/processing-sheet')
        ->assertOk()
        ->assertSee('Foglio lavorazione')
        ->assertSee('Fogli disponibili')
        ->assertSee('DATI REALI WTS');
});

it('shows the progressivo and the colli of each viaggio, not those of the shared technical sheet', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    // Stessa scheda tecnica, due viaggi diversi nello stesso giorno: la riga
    // deve mostrare il progressivo e i colli del proprio viaggio.
    $snapshot = WtsTechnicalSheetSnapshot::query()->create([
        'sheet_code' => 'ST2024-04817',
        'producer_name' => 'GRAFICA VENETA SPA',
        'waste_code' => '150110',
        'legacy_payload' => [
            'program_number' => '128759',
            'document_type' => 'ING',
            'ecos_units' => 10,
        ],
    ]);

    foreach ([['128700-1', '128700', 9], ['128759-1', '128759', 10]] as [$reference, $programNumber, $units]) {
        WtsInboundRecord::query()->create([
            'legacy_reference' => $reference,
            'source_company_name' => 'GRAFICA VENETA SPA',
            'status_code' => 'ready_for_labelling',
            'technical_sheet_snapshot_id' => $snapshot->id,
            'received_at' => now()->startOfDay(),
            'legacy_payload' => [
                'program_number' => $programNumber,
                'document_type' => 'ING',
                'ecos_units' => $units,
                'movement_date' => now()->startOfDay()->toDateTimeString(),
            ],
        ]);
    }

    actingAs($user);
    get('/wts/ingresses')
        ->assertOk()
        ->assertSee('128700')
        ->assertSee('128759');
});
