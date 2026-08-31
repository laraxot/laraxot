<?php

declare(strict_types=1);

uses(TestCase::class);

use Modules\Billing\Actions\BuildReceivablesAgingAction;
use Modules\Billing\Datas\AgingBuckets;
use Modules\Billing\Datas\OpenItem;
use Modules\Billing\Datas\ReceivablesAging;
use Modules\Billing\Tests\TestCase;
use Modules\Quotation\DTO\Money;
use Safe\DateTimeImmutable;

function openItem(string $dueOn, float $amount, string $reference = 'FT/1'): OpenItem
{
    return new OpenItem(
        reference: $reference,
        counterparty: 'Rossi Mario',
        dueOn: new DateTimeImmutable($dueOn),
        outstanding: Money::fromFloat($amount),
    );
}

/**
 * @param  list<OpenItem>  $items
 */
function agingOn(string $asOf, array $items): ReceivablesAging
{
    return (new BuildReceivablesAgingAction())->execute(new DateTimeImmutable($asOf), $items);
}

it('counts an invoice due today as not yet late', function (): void {
    $item = openItem('2026-08-16', 100.0);

    expect($item->daysOverdue(new DateTimeImmutable('2026-08-16')))->toBe(0)
        ->and($item->isOverdue(new DateTimeImmutable('2026-08-16')))->toBeFalse();
});

it('counts the days once an invoice is past its date', function (): void {
    $item = openItem('2026-08-01', 100.0);

    expect($item->daysOverdue(new DateTimeImmutable('2026-08-16')))->toBe(15)
        ->and($item->isOverdue(new DateTimeImmutable('2026-08-16')))->toBeTrue();
});

it('counts down while an invoice is still in the future', function (): void {
    $item = openItem('2026-09-15', 100.0);

    expect($item->daysOverdue(new DateTimeImmutable('2026-08-16')))->toBe(-30);
});

it('ignores the time of day when measuring lateness', function (): void {
    $item = openItem('2026-08-15 23:30', 100.0);

    expect($item->daysOverdue(new DateTimeImmutable('2026-08-16 00:30')))->toBe(1);
});

it('places each number of days late in its own band', function (int $days, string $expected): void {
    expect(AgingBuckets::forDaysOverdue($days))->toBe($expected);
})->with([
    [-1, AgingBuckets::NOT_DUE],
    [0, AgingBuckets::NOT_DUE],
    [1, AgingBuckets::DAYS_1_30],
    [30, AgingBuckets::DAYS_1_30],
    [31, AgingBuckets::DAYS_31_60],
    [60, AgingBuckets::DAYS_31_60],
    [61, AgingBuckets::DAYS_61_90],
    [90, AgingBuckets::DAYS_61_90],
    [91, AgingBuckets::OVER_90],
]);

it('always returns every band so a clean one is not mistaken for a missing one', function (): void {
    $aging = agingOn('2026-08-16', []);

    expect(array_keys($aging->buckets))->toBe(AgingBuckets::all())
        ->and($aging->bucket(AgingBuckets::OVER_90)?->isEmpty())->toBeTrue()
        ->and($aging->total()->asFloat())->toBe(0.0);
});

it('sums what sits in each band', function (): void {
    $aging = agingOn('2026-08-16', [
        openItem('2026-09-01', 500.0),
        openItem('2026-08-10', 200.0),
        openItem('2026-08-01', 300.0),
        openItem('2026-01-01', 1000.0),
    ]);

    expect($aging->bucket(AgingBuckets::NOT_DUE)?->total->asFloat())->toBe(500.0)
        ->and($aging->bucket(AgingBuckets::DAYS_1_30)?->total->asFloat())->toBe(500.0)
        ->and($aging->bucket(AgingBuckets::OVER_90)?->total->asFloat())->toBe(1000.0)
        ->and($aging->bucket(AgingBuckets::DAYS_1_30)?->count())->toBe(2);
});

it('separates what is merely outstanding from what is actually late', function (): void {
    $aging = agingOn('2026-08-16', [
        openItem('2026-09-01', 500.0),
        openItem('2026-07-01', 300.0),
    ]);

    expect($aging->total()->asFloat())->toBe(800.0)
        ->and($aging->overdueTotal()->asFloat())->toBe(300.0);
});

it('puts the oldest debt at the top of its band', function (): void {
    $aging = agingOn('2026-08-16', [
        openItem('2026-08-10', 100.0, 'FT/20'),
        openItem('2026-07-25', 100.0, 'FT/10'),
    ]);

    $items = $aging->bucket(AgingBuckets::DAYS_1_30)->items ?? [];

    expect(array_map(static fn (OpenItem $i): string => $i->reference, $items))->toBe(['FT/10', 'FT/20']);
});

it('returns nothing for a band that does not exist', function (): void {
    expect(agingOn('2026-08-16', [])->bucket('not_a_band'))->toBeNull();
});

it('remembers the date the picture was taken', function (): void {
    expect(agingOn('2026-08-16', [])->asOf->format('Y-m-d'))->toBe('2026-08-16');
});
