<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Ecos\EcosOdbcConnection;
use Modules\Wts\Actions\GenerateWtsInventoryExcelAction;
use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;
use OpenSpout\Reader\XLSX\Reader;

/**
 * @param  list<array<string, mixed>>  $rows
 */
function mockEcosStock(array $rows): void
{
    config()->set('wts.ecos.sync_stock', true);

    $connection = Mockery::mock(EcosOdbcConnection::class);
    $connection->shouldReceive('select')->andReturn($rows);

    app()->instance(EcosOdbcConnection::class, $connection);
}

/**
 * @return array<string, mixed>
 */
function ecosStockRow(string $waste, string $zone, string $producer = 'Produttore X', string $line = 'WD', string $cycle = 'COMB'): array
{
    static $counter = 0;
    $counter++;

    return [
        'batch_code' => 'PART-'.str_pad((string) $counter, 3, '0', STR_PAD_LEFT),
        'unit_sub_code' => '1',
        'sheet_code' => 'ST-'.$counter,
        'waste_code' => $waste,
        'weight_kg' => 100,
        'volume' => 0,
        'colli_count' => 2,
        'zone_code' => $zone,
        'zone_label' => $zone,
        'production_description' => 'Descrizione',
        'packaging_description' => null,
        'note_1' => null,
        'note_2' => null,
        'form_reference' => 'FIR-'.$counter,
        'producer_name' => $producer,
        'treatment_line_code' => $line,
        'production_cycle_code' => $cycle,
    ];
}

/**
 * @param  array<int, \Modules\Wts\Datas\WtsInventoryRow>|\Illuminate\Support\Collection<int, \Modules\Wts\Datas\WtsInventoryRow>  $rows
 * @return list<string>
 */
function wasteCodesOf(iterable $rows): array
{
    $codes = [];
    foreach ($rows as $row) {
        $codes[] = (string) $row->wasteCode;
    }
    sort($codes);

    return $codes;
}

it('filters ECOS stock with the "contains" discriminant across comma-separated values', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1'),
        ecosStockRow('15 01 10*', 'PRESS'),
        ecosStockRow('12 01 99', 'ZONA A'),
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)
        ->execute('12 01', null, '2026-06-10', 'contains');

    expect(wasteCodesOf($overview['rows']))->toBe(['12 01 03', '12 01 99']);
});

it('filters ECOS stock with the "starts_with" discriminant', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1'),
        ecosStockRow('15 01 10*', 'PRESS'),
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)
        ->execute('15', null, '2026-06-10', 'starts_with');

    expect(wasteCodesOf($overview['rows']))->toBe(['15 01 10*']);
});

it('filters ECOS stock with the "equals" discriminant', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1'),
        ecosStockRow('12 01 99', 'ZONA A'),
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)
        ->execute('12 01 03', null, '2026-06-10', 'equals');

    expect(wasteCodesOf($overview['rows']))->toBe(['12 01 03']);
});

it('filters ECOS stock with the "not_equals" discriminant', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1'),
        ecosStockRow('15 01 10*', 'PRESS'),
        ecosStockRow('12 01 99', 'ZONA A'),
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)
        ->execute('12 01 03', null, '2026-06-10', 'not_equals');

    expect(wasteCodesOf($overview['rows']))->toBe(['12 01 99', '15 01 10*']);
});

it('applies the zone discriminant with its own operator', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1'),
        ecosStockRow('12 01 03', 'PRESS'),
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)
        ->execute(null, 'WTS1', '2026-06-10', 'contains', 'equals');

    expect($overview['rows'])->toHaveCount(1)
        ->and($overview['rows']->first()?->zoneCode)->toBe('WTS1');
});

it('treats a comma-only filter value as no filter', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1'),
        ecosStockRow('15 01 10*', 'PRESS'),
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)
        ->execute(' , ', null, '2026-06-10', 'contains');

    expect($overview['rows'])->toHaveCount(2);
});

it('maps producer, treatment line and production cycle from ECOS', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1', 'Acciaierie Alfa', 'WR', 'ACCO'),
    ]);

    $overview = app(GetWtsInventoryOverviewQuery::class)->execute(null, null, '2026-06-10');
    $row = $overview['rows']->first();

    expect($row?->producerName)->toBe('Acciaierie Alfa')
        ->and($row?->treatmentLineCode)->toBe('WR')
        ->and($row?->productionCycleCode)->toBe('ACCO');
});

it('exports the filtered inventory to a real xlsx file', function (): void {
    mockEcosStock([
        ecosStockRow('12 01 03', 'WTS1', 'Acciaierie Alfa', 'WR', 'ACCO'),
        ecosStockRow('15 01 10*', 'PRESS', 'Beta Srl'),
    ]);

    $path = app(GenerateWtsInventoryExcelAction::class)
        ->execute('12 01 03', null, '2026-06-10', 'equals');

    expect($path)->toBeFile();

    $values = [];
    $reader = new Reader;
    $reader->open($path);
    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            $values[] = $row->toArray();
        }
    }
    $reader->close();
    @unlink($path);

    // Intestazione + 1 riga dati (solo il CER filtrato) + riga totale.
    expect($values)->toHaveCount(3)
        ->and($values[0])->toContain('Produttore')
        ->and($values[0])->toContain('Linea')
        ->and($values[0])->toContain('Ciclo')
        ->and($values[1])->toContain('Acciaierie Alfa')
        ->and($values[1])->toContain('WR')
        ->and($values[1])->toContain('ACCO')
        ->and($values[2][0])->toBe('TOTALE');
});
