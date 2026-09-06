<?php

declare(strict_types=1);

namespace Modules\Billing\Queries;

use DateTimeImmutable;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\BuildReceivablesAgingAction;
use Modules\Billing\Datas\OpenItem;
use Modules\Billing\Datas\ReceivablesAging;
use Modules\Quotation\DTO\Money;

/**
 * Reads the customer invoices that still carry money and ages them.
 *
 * An invoice with no due date falls back to its issue date rather than being
 * dropped: leaving it out would quietly shrink the total someone is about to
 * chase, which is worse than dating it conservatively.
 */
final class GetReceivablesAgingQuery
{
    public function __construct(
        private readonly BuildReceivablesAgingAction $buildAging,
    ) {}

    public function execute(?DateTimeImmutable $asOf = null): ReceivablesAging
    {
        $asOf ??= new SafeDateTimeImmutable('today');

        $rows = DB::table('invoices')
            ->leftJoin('customers', 'customers.id', '=', 'invoices.customer_id')
            ->whereNull('invoices.deleted_at')
            ->whereNull('invoices.paid_on')
            ->whereRaw('COALESCE(invoices.total, 0) - COALESCE(invoices.paid_amount, 0) > 0')
            ->select([
                'invoices.number',
                'invoices.issued_on',
                'invoices.due_on',
                'invoices.total',
                'invoices.paid_amount',
                'customers.legal_name',
            ])
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $dueOn = $this->dueDate($row);

            if ($dueOn === null) {
                continue;
            }

            $items[] = new OpenItem(
                reference: is_string($row->number) ? $row->number : '',
                counterparty: is_string($row->legal_name) ? $row->legal_name : '',
                dueOn: $dueOn,
                outstanding: Money::fromFloat(
                    (is_numeric($row->total) ? (float) $row->total : 0.0)
                    - (is_numeric($row->paid_amount ?? null) ? (float) $row->paid_amount : 0.0),
                ),
            );
        }

        return $this->buildAging->execute($asOf, $items);
    }

    /**
     * An invoice with neither date cannot be aged at all, and is reported as
     * null so the caller skips it instead of guessing.
     */
    private function dueDate(object $row): ?DateTimeImmutable
    {
        /** @var array<string, mixed> $values */
        $values = (array) $row;

        $raw = $values['due_on'] ?? $values['issued_on'] ?? null;

        if (! is_string($raw) || $raw === '') {
            return null;
        }

        return new SafeDateTimeImmutable($raw);
    }
}
