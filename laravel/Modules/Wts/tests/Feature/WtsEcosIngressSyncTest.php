<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Ecos\EcosOdbcConnection;
use Modules\Wts\Actions\SyncEcosIngressesAction;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Queries\GetWtsIngressOverviewQuery;

/**
 * Righe rappresentative della PROGRAMMAZIONE conferimenti ECOS (codprog/codprgd),
 * con gli stessi alias prodotti da SyncEcosIngressesAction::sql().
 * I valori sono quelli reali verificati 1:1 contro la lista "Conferimenti" di bbros
 * per il 20/07/2026 (vedi docs/waste/13-query-conferimenti-programmati-wts.md).
 *
 * @return array<int, array<string, mixed>>
 */
function ecosProgrammedIngressRows(): array
{
    $base = [
        'document_type' => 'ING',
        'commercial_company_name' => 'NIAGARA SRL',
        'transporter_name' => 'CARPISPURGO SRL',
        'production_description' => 'imballaggi contenenti residui di sostanze pericolose',
        'ecos_units' => 0,
        'gross_quantity' => 0.0,
        'movement_date' => '2026-07-20 00:00:00',
        'imp_cod_pr' => 'C13539',
        'imp_uni_pr' => 1,
        'imp_cod_de' => 'WTS',
        'imp_uni_de' => 1,
    ];

    return [
        array_merge($base, [
            'legacy_reference' => '125416-1',
            'program_number' => '125416',
            'sheet_code' => 'ST2022-03332',
            'producer_name' => 'SCAM SPA',
            'hp_codes' => null,
            'waste_code' => '150203',
        ]),
        array_merge($base, [
            'legacy_reference' => '125616-1',
            'program_number' => '125616',
            'sheet_code' => 'ST2015-03608',
            'producer_name' => 'METERSIT SRL',
            'hp_codes' => 'HP5 HP13',
            'waste_code' => '150110',
        ]),
        array_merge($base, [
            'legacy_reference' => '125987-1',
            'program_number' => '125987',
            'sheet_code' => 'ST2020-01231',
            'producer_name' => 'SUN CHEMICAL GROUP SPA',
            'hp_codes' => 'HP14',
            'waste_code' => '150202',
        ]),
        array_merge($base, [
            'legacy_reference' => '127054-1',
            'program_number' => '127054',
            'sheet_code' => 'ST2024-01751',
            'producer_name' => 'EMICON A.C. SPA',
            'commercial_company_name' => 'ECO STUDIO SRL',
            'hp_codes' => null,
            'waste_code' => '150103',
        ]),
    ];
}

function mockEcosIngressConnection(): void
{
    config()->set('wts.ecos.sync_ingresses', true);

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')->andReturn(ecosProgrammedIngressRows());
    app()->instance(EcosOdbcConnection::class, $connection);
}

it('imports the ECOS programmazione conferimenti (codprog) instead of registered movements', function (): void {
    mockEcosIngressConnection();

    $result = app(SyncEcosIngressesAction::class)->execute('2026-07-20');

    expect($result['attempted'])->toBeTrue()
        ->and($result['error'])->toBeNull()
        ->and($result['imported'])->toBe(4)
        ->and(WtsInboundRecord::query()->count())->toBe(4)
        ->and(WtsTechnicalSheetSnapshot::query()->count())->toBe(4);
});

it('resolves ragione sociale, commerciale, trasportatore, scheda, HP and CER exactly like bbros', function (): void {
    mockEcosIngressConnection();

    app(SyncEcosIngressesAction::class)->execute('2026-07-20');

    $scam = WtsInboundRecord::query()
        ->where('legacy_reference', '125416-1')
        ->with('technicalSheetSnapshot')
        ->firstOrFail();

    expect($scam->source_company_name)->toBe('SCAM SPA')
        ->and($scam->commercial_company_name)->toBe('NIAGARA SRL')
        ->and($scam->vehicle_reference)->toBe('CARPISPURGO SRL')
        ->and($scam->form_number)->toBeNull()
        ->and($scam->technicalSheetSnapshot?->legacy_system)->toBe('ecos')
        ->and($scam->technicalSheetSnapshot?->sheet_code)->toBe('ST2022-03332')
        ->and($scam->technicalSheetSnapshot?->producer_name)->toBe('SCAM SPA')
        ->and($scam->technicalSheetSnapshot?->waste_code)->toBe('150203')
        ->and($scam->technicalSheetSnapshot?->hp_codes)->toBeNull();

    $metersit = WtsTechnicalSheetSnapshot::query()->where('sheet_code', 'ST2015-03608')->firstOrFail();
    expect($metersit->hp_codes)->toBe('HP5 HP13')
        ->and($metersit->waste_code)->toBe('150110');

    $emicon = WtsInboundRecord::query()->where('legacy_reference', '127054-1')->firstOrFail();
    expect($emicon->commercial_company_name)->toBe('ECO STUDIO SRL');
});

it('does not attach a formulario/peso to a programmed (not yet arrived) conferimento', function (): void {
    mockEcosIngressConnection();

    app(SyncEcosIngressesAction::class)->execute('2026-07-20');

    $record = WtsInboundRecord::query()->where('legacy_reference', '125987-1')->firstOrFail();

    // Colli/Peso/Formulario restano vuoti finché il camion non è arrivato (come su bbros).
    expect($record->form_number)->toBeNull()
        ->and($record->entry_weight)->toBeNull();
});

it('surfaces the corrected ingresses through the UI-01 overview for the selected day', function (): void {
    mockEcosIngressConnection();

    $ingresses = app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-07-20');

    expect($ingresses)->toHaveCount(4);

    $sunChemical = $ingresses->firstWhere('legacy_reference', '125987-1');
    expect($sunChemical)->not->toBeNull()
        ->and($sunChemical?->source_company_name)->toBe('SUN CHEMICAL GROUP SPA')
        ->and($sunChemical?->commercial_company_name)->toBe('NIAGARA SRL')
        ->and($sunChemical?->technicalSheetSnapshot?->hp_codes)->toBe('HP14');

    // Il banner deve segnalare dati reali ECOS, non demo.
    expect(app(GetWtsIngressOverviewQuery::class)->dataSourceStatus($ingresses)['label'])
        ->toBe('DATI REALI ECOS');
});

it('does not query ECOS when ingress sync is disabled', function (): void {
    config()->set('wts.ecos.sync_ingresses', false);

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldNotReceive('select');
    app()->instance(EcosOdbcConnection::class, $connection);

    $result = app(SyncEcosIngressesAction::class)->execute('2026-07-20');

    expect($result['attempted'])->toBeFalse()
        ->and($result['imported'])->toBe(0);
});

/**
 * Due viaggi diversi che condividono la STESSA scheda tecnica in due giorni
 * diversi: e' il caso segnalato da WTS (Grafica Veneta e Indaver ripetute).
 *
 * @return array<int, array<int, array<string, mixed>>>
 */
function ecosSameSheetOnTwoDaysRows(): array
{
    $base = [
        'document_type' => 'ING',
        'sheet_code' => 'ST2024-04817',
        'producer_name' => 'GRAFICA VENETA SPA',
        'commercial_company_name' => 'NIAGARA SRL',
        'transporter_name' => 'CARPISPURGO SRL',
        'waste_code' => '150110',
        'production_description' => 'imballaggi contenenti residui di sostanze pericolose',
        'imp_cod_pr' => 'C13539',
        'imp_uni_pr' => 1,
        'imp_cod_de' => 'WTS',
        'imp_uni_de' => 1,
    ];

    return [
        [array_merge($base, [
            'legacy_reference' => '128700-1',
            'program_number' => '128700',
            'movement_date' => '2026-08-12 00:00:00',
            'ecos_units' => 9,
        ])],
        [array_merge($base, [
            'legacy_reference' => '128759-1',
            'program_number' => '128759',
            'movement_date' => '2026-08-20 00:00:00',
            'ecos_units' => 10,
            'movement_units' => 10,
            'movement_quantity' => 960.0,
            'form_number' => 'XBRDH 008028 GH',
            'batch_number' => '54128',
        ])],
    ];
}

it('does not repeat a conferimento when another viaggio shares the same technical sheet', function (): void {
    config()->set('wts.ecos.sync_ingresses', true);

    [$firstDayRows, $secondDayRows] = ecosSameSheetOnTwoDaysRows();

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')->andReturn($firstDayRows, $secondDayRows);
    app()->instance(EcosOdbcConnection::class, $connection);

    app(SyncEcosIngressesAction::class)->execute('2026-08-12');
    app(SyncEcosIngressesAction::class)->execute('2026-08-20');

    // Due viaggi distinti, una sola scheda tecnica condivisa.
    expect(WtsInboundRecord::query()->count())->toBe(2)
        ->and(WtsTechnicalSheetSnapshot::query()->count())->toBe(1);

    $ingresses = app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-08-20');

    expect($ingresses)->toHaveCount(1)
        ->and($ingresses->first()?->legacy_reference)->toBe('128759-1')
        ->and($ingresses->first()?->form_number)->toBe('XBRDH 008028 GH');

    $previousDay = app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-08-12');

    expect($previousDay)->toHaveCount(1)
        ->and($previousDay->first()?->legacy_reference)->toBe('128700-1');
});

it('keeps each viaggio on its own day even without a per-record ECOS payload', function (): void {
    config()->set('wts.ecos.sync_ingresses', false);

    // Record sincronizzato prima che il payload venisse salvato sul viaggio:
    // la data deve arrivare da received_at, non dalla scheda tecnica condivisa.
    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'sheet_code' => 'ST2024-04817',
        'legacy_payload' => [
            'program_number' => '128759',
            'document_type' => 'ING',
            'movement_date' => '2026-08-20 00:00:00',
        ],
    ]);

    WtsInboundRecord::factory()->create([
        'legacy_reference' => '128700-1',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'legacy_payload' => null,
        'received_at' => '2026-08-12 00:00:00',
    ]);

    expect(app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-08-20'))->toHaveCount(0)
        ->and(app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-08-12'))->toHaveCount(1);
});

it('still reads the document type from the technical sheet for records synced before the per-record payload', function (): void {
    config()->set('wts.ecos.sync_ingresses', false);

    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'sheet_code' => 'ST2024-09999',
        'legacy_payload' => [
            'document_type' => 'SCAR',
            'imp_cod_pr' => 'C13539',
            'imp_uni_pr' => 1,
        ],
    ]);

    WtsInboundRecord::factory()->create([
        'legacy_reference' => '128579-1',
        'technical_sheet_snapshot_id' => $snapshot->id,
        'legacy_payload' => null,
        'received_at' => '2026-08-20 00:00:00',
    ]);

    // Gli scarichi restano fuori da UI-01: l'operatore etichetta solo gli ingressi.
    expect(app(GetWtsIngressOverviewQuery::class)->execute(null, '2026-08-20'))->toHaveCount(0);
});
