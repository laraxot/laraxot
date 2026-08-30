<?php

declare(strict_types=1);

uses(\Modules\Billing\Tests\TestCase::class);

use Modules\Billing\Actions\CalculateJobMarginAction;
use Modules\Billing\Datas\JobMargin;
use Modules\Billing\Datas\JobMarginReport;
use Modules\Billing\Tests\TestCase;

function jobMargin(
    float $revenue,
    float $labour = 0.0,
    float $material = 0.0,
    float $external = 0.0,
    int $id = 1,
    string $title = 'Sostituzione caldaia',
): JobMargin {
    return (new CalculateJobMarginAction())->execute(
        workOrderId: $id,
        workOrderTitle: $title,
        revenue: $revenue,
        labourCost: $labour,
        materialCost: $material,
        externalCost: $external,
    );
}

it('adds the three cost families into a single total', function (): void {
    $margin = jobMargin(revenue: 3000.0, labour: 800.0, material: 1200.0, external: 250.0);

    expect($margin->totalCost()->asFloat())->toBe(2250.0);
});

it('reports what is left once every cost is paid', function (): void {
    $margin = jobMargin(revenue: 3000.0, labour: 800.0, material: 1200.0, external: 250.0);

    expect($margin->margin()->asFloat())->toBe(750.0)
        ->and($margin->isLoss())->toBeFalse();
});

it('keeps the work order identity alongside the figures', function (): void {
    $margin = jobMargin(revenue: 100.0, id: 42, title: 'Manutenzione condizionatore');

    expect($margin->workOrderId)->toBe(42)
        ->and($margin->workOrderTitle)->toBe('Manutenzione condizionatore');
});

it('expresses the margin as a share of revenue', function (): void {
    $margin = jobMargin(revenue: 2000.0, labour: 1500.0);

    expect($margin->marginPercent())->toBe(25.0);
});

it('has no percentage to give when nothing was billed yet', function (): void {
    $margin = jobMargin(revenue: 0.0, labour: 400.0);

    expect($margin->marginPercent())->toBeNull();
});

it('calls a job a loss only once costs pass revenue', function (): void {
    expect(jobMargin(revenue: 1000.0, labour: 1000.0)->isLoss())->toBeFalse()
        ->and(jobMargin(revenue: 1000.0, labour: 1000.01)->isLoss())->toBeTrue();
});

it('reports a negative percentage on a job that lost money', function (): void {
    $margin = jobMargin(revenue: 1000.0, labour: 1500.0);

    expect($margin->marginPercent())->toBe(-50.0)
        ->and($margin->isLoss())->toBeTrue();
});

it('sorts jobs so the ones bleeding money come first', function (): void {
    $healthy = jobMargin(revenue: 3000.0, labour: 500.0, id: 1);
    $thin = jobMargin(revenue: 1000.0, labour: 950.0, id: 2);
    $losing = jobMargin(revenue: 800.0, labour: 1400.0, id: 3);

    $sorted = (new CalculateJobMarginAction())->worstFirst([$healthy, $thin, $losing]);

    expect(array_map(static fn (JobMargin $m): int => $m->workOrderId, $sorted))->toBe([3, 2, 1]);
});

it('leaves an empty list alone when there is nothing to sort', function (): void {
    expect((new CalculateJobMarginAction())->worstFirst([]))->toBe([]);
});

it('says whether the figures were costed at a real labour rate', function (): void {
    expect((new JobMarginReport([], 2500))->isLabourCostConfigured())->toBeTrue()
        ->and((new JobMarginReport([], 3000, usesDefaultRate: true))->isLabourCostConfigured())->toBeFalse();
});

it('picks out only the jobs that lost money', function (): void {
    $report = new JobMarginReport(
        [jobMargin(revenue: 3000.0, labour: 500.0, id: 1), jobMargin(revenue: 800.0, labour: 1400.0, id: 3)],
        2500,
    );

    expect($report->losingJobs())->toHaveCount(1)
        ->and($report->losingJobs()[0]->workOrderId)->toBe(3)
        ->and($report->isEmpty())->toBeFalse();
});

it('reports an empty run as empty', function (): void {
    expect((new JobMarginReport([], 2500))->isEmpty())->toBeTrue()
        ->and((new JobMarginReport([], 2500))->losingJobs())->toBe([]);
});
