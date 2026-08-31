<?php

declare(strict_types=1);

uses(\Modules\Billing\Tests\TestCase::class);

use Modules\Billing\Actions\ProjectCashFlowAction;
use Modules\Billing\Datas\CashFlowEntry;
use Modules\Billing\Datas\CashFlowProjection;
use Modules\Billing\Datas\OpenItem;
use Modules\Billing\Tests\TestCase;
use Modules\Quotation\DTO\Money;
use Safe\DateTimeImmutable;

function flowItem(string $dueOn, float $amount): OpenItem
{
    return new OpenItem(
        reference: 'DOC/'.$dueOn,
        counterparty: 'Controparte',
        dueOn: new DateTimeImmutable($dueOn),
        outstanding: Money::fromFloat($amount),
    );
}

/**
 * @param  list<OpenItem>  $receivables
 * @param  list<OpenItem>  $payables
 */
function project(float $opening, array $receivables, array $payables, string $from = '2026-08-16', string $to = '2026-10-31'): CashFlowProjection
{
    return (new ProjectCashFlowAction())->execute(
        openingBalance: Money::fromFloat($opening),
        receivables: $receivables,
        payables: $payables,
        from: new DateTimeImmutable($from),
        to: new DateTimeImmutable($to),
    );
}

it('walks the balance forward through what comes in and goes out', function (): void {
    $projection = project(
        opening: 5000.0,
        receivables: [flowItem('2026-09-01', 3000.0)],
        payables: [flowItem('2026-09-15', 2000.0)],
    );

    expect($projection->entries)->toHaveCount(2)
        ->and($projection->entries[0]->balance->asFloat())->toBe(8000.0)
        ->and($projection->entries[1]->balance->asFloat())->toBe(6000.0)
        ->and($projection->closingBalance()->asFloat())->toBe(6000.0);
});

it('nets money moving both ways on the same day', function (): void {
    $projection = project(
        opening: 1000.0,
        receivables: [flowItem('2026-09-01', 800.0)],
        payables: [flowItem('2026-09-01', 300.0)],
    );

    expect($projection->entries)->toHaveCount(1)
        ->and($projection->entries[0]->inflow->asFloat())->toBe(800.0)
        ->and($projection->entries[0]->outflow->asFloat())->toBe(300.0)
        ->and($projection->entries[0]->net()->asFloat())->toBe(500.0)
        ->and($projection->entries[0]->balance->asFloat())->toBe(1500.0);
});

it('adds up several invoices falling due on one day', function (): void {
    $projection = project(
        opening: 0.0,
        receivables: [flowItem('2026-09-01', 100.0), flowItem('2026-09-01', 250.0)],
        payables: [],
    );

    expect($projection->entries[0]->inflow->asFloat())->toBe(350.0);
});

it('expects overdue money on the first day of the horizon, not in the past', function (): void {
    $projection = project(
        opening: 0.0,
        receivables: [flowItem('2026-05-01', 400.0)],
        payables: [],
    );

    expect($projection->entries)->toHaveCount(1)
        ->and($projection->entries[0]->date->format('Y-m-d'))->toBe('2026-08-16');
});

it('leaves out anything falling due beyond the horizon', function (): void {
    $projection = project(
        opening: 2000.0,
        receivables: [flowItem('2027-01-15', 9000.0)],
        payables: [],
    );

    expect($projection->entries)->toBe([])
        ->and($projection->closingBalance()->asFloat())->toBe(2000.0);
});

it('keeps an invoice due on the closing day of the horizon', function (): void {
    $projection = project(
        opening: 0.0,
        receivables: [flowItem('2026-10-31', 500.0)],
        payables: [],
    );

    expect($projection->entries)->toHaveCount(1);
});

it('orders the days even when the invoices arrive shuffled', function (): void {
    $projection = project(
        opening: 0.0,
        receivables: [flowItem('2026-10-01', 100.0), flowItem('2026-09-01', 100.0)],
        payables: [],
    );

    expect(array_map(
        static fn (CashFlowEntry $e): string => $e->date->format('Y-m-d'),
        $projection->entries,
    ))->toBe(['2026-09-01', '2026-10-01']);
});

it('names the first day the money runs out', function (): void {
    $projection = project(
        opening: 1000.0,
        receivables: [flowItem('2026-10-01', 5000.0)],
        payables: [flowItem('2026-09-01', 4000.0)],
    );

    expect($projection->hasShortfall())->toBeTrue()
        ->and($projection->firstShortfallOn()?->format('Y-m-d'))->toBe('2026-09-01')
        ->and($projection->lowestBalance()->asFloat())->toBe(-3000.0)
        ->and($projection->closingBalance()->asFloat())->toBe(2000.0);
});

it('says nothing about a shortfall when the balance never dips below zero', function (): void {
    $projection = project(
        opening: 1000.0,
        receivables: [flowItem('2026-09-01', 500.0)],
        payables: [],
    );

    expect($projection->hasShortfall())->toBeFalse()
        ->and($projection->firstShortfallOn())->toBeNull()
        ->and($projection->entries[0]->isShortfall())->toBeFalse();
});

it('treats an exactly empty balance as still solvent', function (): void {
    $projection = project(
        opening: 100.0,
        receivables: [],
        payables: [flowItem('2026-09-01', 100.0)],
    );

    expect($projection->entries[0]->balance->asFloat())->toBe(0.0)
        ->and($projection->hasShortfall())->toBeFalse();
});

it('falls back to the opening balance when nothing moves at all', function (): void {
    $projection = project(opening: 750.0, receivables: [], payables: []);

    expect($projection->entries)->toBe([])
        ->and($projection->closingBalance()->asFloat())->toBe(750.0)
        ->and($projection->lowestBalance()->asFloat())->toBe(750.0)
        ->and($projection->hasShortfall())->toBeFalse();
});

it('reports the worst point even when the balance recovers by the end', function (): void {
    $projection = project(
        opening: 500.0,
        receivables: [flowItem('2026-10-01', 3000.0)],
        payables: [flowItem('2026-09-01', 1000.0)],
    );

    expect($projection->lowestBalance()->asFloat())->toBe(-500.0)
        ->and($projection->closingBalance()->asFloat())->toBe(2500.0);
});
