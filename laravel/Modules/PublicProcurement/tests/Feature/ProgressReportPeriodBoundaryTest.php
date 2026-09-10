<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Modules\PublicProcurement\Actions\CreateContractFromOpportunityAction;
use Modules\PublicProcurement\Actions\GenerateProgressReportFromMeasurementsAction;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementMeasurement;
use Modules\PublicProcurement\Models\PublicProcurementPriceItem;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\PublicProcurement\Tests\TestCase;

uses(TestCase::class);

/**
 * Il SAL copre un periodo chiuso a entrambe le estremita'. Il lavoro misurato
 * l'ultimo giorno e' lavoro fatto dentro il periodo, e deve entrarci: se resta
 * fuori l'impresa non lo vede pagato e lo scopre solo a conti fatti.
 */
function makeContractForProgressReport(): PublicProcurementContract
{
    $source = PublicProcurementTenderSource::query()->create([
        'name' => 'Portale demo',
        'source_type' => 'manual',
        'is_active' => true,
    ]);

    $opportunity = PublicProcurementTenderOpportunity::query()->create([
        'tender_source_id' => $source->id,
        'status' => 'new',
        'contracting_authority' => 'Comune Demo',
        'title' => 'Impianti elettrici scuola comunale',
        'cig' => 'B987654321',
        'cup' => 'H11B26000010004',
        'category' => 'OS30',
        'estimated_amount' => 120000,
        'submission_deadline_at' => CarbonImmutable::now()->addDays(20)->toDateTimeString(),
    ]);

    $contract = app(CreateContractFromOpportunityAction::class)->execute($opportunity);
    $contract->forceFill([
        'contract_amount' => 100000,
        'security_costs' => 5000,
        'retention_rate' => 1,
    ])->save();

    return $contract->refresh();
}

it('conta una misura registrata l ultimo giorno del periodo', function (): void {
    $contract = makeContractForProgressReport();

    $item = PublicProcurementPriceItem::query()->create([
        'public_procurement_contract_id' => $contract->id,
        'description' => 'Quadro elettrico',
        'planned_quantity' => 1,
        'unit_price' => 5000,
        'planned_total' => 5000,
    ]);

    $lastDay = CarbonImmutable::now()->endOfMonth();

    PublicProcurementMeasurement::query()->create([
        'public_procurement_contract_id' => $contract->id,
        'price_item_id' => $item->id,
        'status' => 'approved',
        'measured_on' => $lastDay->toDateString(),
        'quantity' => 1,
        'amount' => 5000,
    ]);

    $report = app(GenerateProgressReportFromMeasurementsAction::class)->execute(
        $contract,
        '1',
        CarbonImmutable::now()->startOfMonth(),
        $lastDay,
    );

    expect((float) $report->work_amount)->toBe(5000.0);
});

it('conta una misura registrata il primo giorno del periodo', function (): void {
    $contract = makeContractForProgressReport();

    $item = PublicProcurementPriceItem::query()->create([
        'public_procurement_contract_id' => $contract->id,
        'description' => 'Cavidotti',
        'planned_quantity' => 1,
        'unit_price' => 3000,
        'planned_total' => 3000,
    ]);

    $firstDay = CarbonImmutable::now()->startOfMonth();

    PublicProcurementMeasurement::query()->create([
        'public_procurement_contract_id' => $contract->id,
        'price_item_id' => $item->id,
        'status' => 'approved',
        'measured_on' => $firstDay->toDateString(),
        'quantity' => 1,
        'amount' => 3000,
    ]);

    $report = app(GenerateProgressReportFromMeasurementsAction::class)->execute(
        $contract,
        '1',
        $firstDay,
        CarbonImmutable::now()->endOfMonth(),
    );

    expect((float) $report->work_amount)->toBe(3000.0);
});

it('lascia fuori una misura del giorno dopo la fine del periodo', function (): void {
    $contract = makeContractForProgressReport();

    $item = PublicProcurementPriceItem::query()->create([
        'public_procurement_contract_id' => $contract->id,
        'description' => 'Corpi illuminanti',
        'planned_quantity' => 1,
        'unit_price' => 2000,
        'planned_total' => 2000,
    ]);

    $periodTo = CarbonImmutable::now()->endOfMonth();

    PublicProcurementMeasurement::query()->create([
        'public_procurement_contract_id' => $contract->id,
        'price_item_id' => $item->id,
        'status' => 'approved',
        'measured_on' => $periodTo->addDay()->toDateString(),
        'quantity' => 1,
        'amount' => 2000,
    ]);

    $report = app(GenerateProgressReportFromMeasurementsAction::class)->execute(
        $contract,
        '1',
        CarbonImmutable::now()->startOfMonth(),
        $periodTo,
    );

    expect((float) $report->work_amount)->toBe(0.0);
});
