<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Filament\Pages\WtsMaterialDetailPage;
use Modules\Wts\Filament\Pages\WtsWorkQueuePage;
use Modules\User\Models\User;
use Modules\Wts\Ecos\EcosOdbcConnection;
use Modules\Wts\Legacy\ConfiguredLegacyTechnicalSheetGateway;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsIngressOverviewQuery;
use Modules\Wts\Queries\SearchWtsAuditQuery;
use Modules\Wts\Support\WtsLegacyPayload;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('parses legacy payload helpers across valid invalid and fallback inputs', function (): void {
    config()->set('wts.legacy_movement_date_keys', [123, 'movement_date']);

    $payload = [
        'sheet_code' => ' ST-001 ',
        'movement_date' => '2026-06-10 08:15:00',
    ];

    expect(WtsLegacyPayload::firstString($payload, ['missing', 'sheet_code']))->toBe('ST-001')
        ->and(WtsLegacyPayload::firstString(null, ['sheet_code']))->toBeNull()
        ->and(WtsLegacyPayload::movementDate($payload, '2026-06-09')?->toDateString())->toBe('2026-06-10')
        ->and(WtsLegacyPayload::movementDate(['movement_date' => 'not-a-date'], '2026-06-09')?->toDateString())->toBe('2026-06-09')
        ->and(WtsLegacyPayload::matchesDate($payload, null, '2026-06-10'))->toBeTrue()
        ->and(WtsLegacyPayload::matchesDate(['movement_date' => 'not-a-date'], null, '2026-06-10'))->toBeFalse()
        ->and(WtsLegacyPayload::matchesDate(null, '2026-06-09', '2026-06-09'))->toBeTrue()
        ->and(WtsLegacyPayload::matchesDate(null, null, '2026-06-09'))->toBeFalse();
});

it('loads technical sheet data from configured legacy table and fallback config', function (): void {
    Schema::create('legacy_sheets', function (Blueprint $table): void {
        $table->string('sheet_code')->primary();
        $table->string('producer_name')->nullable();
        $table->string('waste_code')->nullable();
        $table->string('hp_codes')->nullable();
        $table->string('packaging_description')->nullable();
        $table->string('production_description')->nullable();
        $table->string('note_1')->nullable();
        $table->string('note_2')->nullable();
    });

    DB::table('legacy_sheets')->insert([
        'sheet_code' => 'ST-DB-001',
        'producer_name' => 'Produttore DB',
        'waste_code' => '19 12 04',
        'hp_codes' => 'HP5',
        'packaging_description' => 'Fusto metallico',
        'production_description' => 'Residuo da test',
        'note_1' => 'Nota A',
        'note_2' => 'Nota B',
    ]);

    config()->set('wts.legacy.connection', config('database.default'));
    config()->set('wts.legacy.table', 'legacy_sheets');
    config()->set('wts.legacy.sheet_code_column', 'sheet_code');

    $gateway = new ConfiguredLegacyTechnicalSheetGateway(app('db'));

    $dbSheet = $gateway->findBySheetCode(' ST-DB-001 ');
    $fallbackSheet = $gateway->findBySheetCode('ST-TERM-001');

    expect($gateway->findBySheetCode('   '))->toBeNull()
        ->and($dbSheet?->sheetCode)->toBe('ST-DB-001')
        ->and($dbSheet?->producerName)->toBe('Produttore DB')
        ->and($dbSheet?->note1)->toBe('Nota A')
        ->and($fallbackSheet?->sheetCode)->toBe('ST-TERM-001')
        ->and($fallbackSheet?->producerName)->toBe('Impianti Termoidraulici Estensi Srl');
});

it('renders reports page and covers work queue utility branches', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $unit = WtsUnit::factory()->create([
        'status_code' => 'available',
        'current_area_code' => 'to_process',
    ]);
    $step = WtsProcessingStep::factory()->create([
        'work_sheet_code' => 'WS-RPT-001',
    ]);
    $step->selectedUnits()->sync([$unit->id]);
    $material = WtsDerivedMaterial::factory()->create([
        'processing_step_id' => $step->id,
        'output_code' => 'OUT-RPT-001',
    ]);

    config()->set('wts.stock_views', [
        'to_process' => 'Da lavorare',
        99 => 'Da ignorare',
        'finished' => 'Finito',
    ]);
    config()->set('wts.processing_step_types', [
        'sorting' => 'Selezione e cernita',
        77 => 'Da ignorare',
    ]);

    actingAs($user);
    get('/wts/reports')
        ->assertOk()
        ->assertSee('WS-RPT-001')
        ->assertSee('OUT-RPT-001');

    actingAs($user);
    get('/wts/work-queue')
        ->assertOk()
        ->assertSee('Da lavorare');

    Livewire::actingAs($user);
    Livewire::test(WtsWorkQueuePage::class)
        ->call('moveUnit', 999999, 'to_process')
        ->call('moveUnit', $unit->id, '')
        ->assertHasNoErrors();

    expect($material->fresh()?->output_code)->toBe('OUT-RPT-001')
        ->and($unit->fresh()?->current_area_code)->toBe('to_process');
});

it('covers material detail fallback branches and invalid moves', function (): void {
    Role::findOrCreate('wts_admin');

    $user = \Modules\Wts\Tests\TestCase::createUser();
    $user->syncRoles(['wts_admin']);

    $unit = WtsUnit::factory()->create([
        'status_code' => 'available',
        'current_area_code' => 'to_process',
    ]);

    config()->set('wts.stock_views', [
        'to_process' => 'Da lavorare',
        88 => 'Da ignorare',
    ]);

    actingAs($user);
    get('/wts/material-detail?unit='.$unit->id)
        ->assertOk()
        ->assertSee($unit->unit_code);

    Livewire::actingAs($user);
    Livewire::withQueryParams(['unit' => 999999])
        ->test(WtsMaterialDetailPage::class)
        ->call('moveToArea', 'to_process')
        ->assertHasNoErrors();

    Livewire::actingAs($user);
    Livewire::withQueryParams(['unit' => $unit->id])
        ->test(WtsMaterialDetailPage::class)
        ->call('moveToArea', '')
        ->assertHasNoErrors();

    expect($unit->fresh()?->current_area_code)->toBe('to_process');
});

it('searches the audit query by lot code and form number fallbacks', function (): void {
    $lot = WtsInboundLot::factory()->create([
        'lot_code' => 'LOT-AUDIT-001',
    ]);
    $unit = WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $lot->inboundRecord?->technical_sheet_snapshot_id,
        'unit_code' => 'UNIT-AUDIT-FALLBACK-01',
        'qr_code' => 'QR-AUDIT-FALLBACK-01',
    ]);

    $lotResult = app(SearchWtsAuditQuery::class)->execute('LOT-AUDIT-001');
    $recordResult = app(SearchWtsAuditQuery::class)->execute($lot->inboundRecord->form_number ?? '');

    expect($lotResult['lot']?->id)->toBe($lot->id)
        ->and($lotResult['unit']?->id)->toBe($unit->id)
        ->and($recordResult['record']?->id)->toBe($lot->inboundRecord?->id)
        ->and($recordResult['lot']?->id)->toBe($lot->id);
});

it('reports ingress data source status for demo real ecos and driver error states', function (): void {
    $query = app(GetWtsIngressOverviewQuery::class);

    config()->set('wts.ecos.sync_ingresses', false);
    $demoStatus = $query->dataSourceStatus($query->execute());

    $ecosSnapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'legacy_system' => 'ecos',
    ]);
    $ecosRecord = WtsInboundRecord::factory()->create([
        'technical_sheet_snapshot_id' => $ecosSnapshot->id,
    ]);
    $realStatus = $query->dataSourceStatus(new EloquentCollection([$ecosRecord]));

    expect($demoStatus['label'])->toBe('DATI DEMO')
        ->and($realStatus['label'])->toBe('DATI REALI ECOS');

    // Il ramo di errore va simulato, non dedotto dall'ambiente: con un driver
    // SQL Server installato e la VPN attiva la connessione riesce davvero, e il
    // test finirebbe per fare una query di rete su ECOS di produzione.
    config()->set('wts.ecos.sync_ingresses', true);

    app()->bind(EcosOdbcConnection::class, function (): EcosOdbcConnection {
        /** @var \Modules\Wts\Ecos\EcosOdbcConnection&\Mockery\MockInterface $connection */
        $connection = Mockery::mock(EcosOdbcConnection::class);
        $connection->shouldReceive('select')->andThrow(new RuntimeException('ECOS non raggiungibile'));

        return $connection;
    });

    $errorQuery = app(GetWtsIngressOverviewQuery::class);
    $errorQuery->execute();
    $errorStatus = $errorQuery->dataSourceStatus(new EloquentCollection);

    expect($errorStatus['label'])->toBe('DATI DEMO - ECOS NON RAGGIUNGIBILE');
});
