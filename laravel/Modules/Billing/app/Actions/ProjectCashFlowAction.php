<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use DateTimeImmutable;
use Modules\Billing\Datas\CashFlowEntry;
use Modules\Billing\Datas\CashFlowProjection;
use Modules\Billing\Datas\OpenItem;
use Modules\Quotation\DTO\Money;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;

/**
 * Walks the money forward from today's balance over what is owed both ways.
 *
 * Two modelling choices worth knowing, because they decide what the chart says:
 * anything already past its due date is placed on the first day of the horizon
 * rather than in the past, since an overdue invoice is still expected; and
 * anything falling due after the horizon is left out entirely instead of being
 * squeezed onto the last day, which would invent a cliff that is not there.
 */
final class ProjectCashFlowAction
{
    /**
     * @param  list<OpenItem>  $receivables  Money expected in.
     * @param  list<OpenItem>  $payables  Money expected out.
     */
    public function execute(
        Money $openingBalance,
        array $receivables,
        array $payables,
        DateTimeImmutable $from,
        DateTimeImmutable $to,
    ): CashFlowProjection {
        $start = $from->setTime(0, 0);
        $end = $to->setTime(0, 0);

        /** @var array<string, array{inflow: Money, outflow: Money}> $days */
        $days = [];

        foreach ($receivables as $item) {
            $this->place($days, $item, $start, $end, inflow: true);
        }

        foreach ($payables as $item) {
            $this->place($days, $item, $start, $end, inflow: false);
        }

        ksort($days);

        $balance = $openingBalance;
        $entries = [];

        foreach ($days as $day => $movement) {
            $balance = $balance->add($movement['inflow'])->sub($movement['outflow']);

            $entries[] = new CashFlowEntry(
                date: new SafeDateTimeImmutable($day),
                inflow: $movement['inflow'],
                outflow: $movement['outflow'],
                balance: $balance,
            );
        }

        return new CashFlowProjection(openingBalance: $openingBalance, entries: $entries);
    }

    /**
     * @param  array<string, array{inflow: Money, outflow: Money}>  $days
     */
    private function place(
        array &$days,
        OpenItem $item,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        bool $inflow,
    ): void {
        $due = $item->dueOn->setTime(0, 0);

        if ($due > $end) {
            return;
        }

        $slot = $due < $start ? $start : $due;
        $key = $slot->format('Y-m-d');

        if (! isset($days[$key])) {
            $days[$key] = ['inflow' => Money::zero(), 'outflow' => Money::zero()];
        }

        if ($inflow) {
            $days[$key]['inflow'] = $days[$key]['inflow']->add($item->outstanding);

            return;
        }

        $days[$key]['outflow'] = $days[$key]['outflow']->add($item->outstanding);
    }
}
