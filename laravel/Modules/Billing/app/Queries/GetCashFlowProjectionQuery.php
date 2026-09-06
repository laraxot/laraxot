<?php

declare(strict_types=1);

namespace Modules\Billing\Queries;

use DateTimeImmutable;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\ProjectCashFlowAction;
use Modules\Billing\Datas\CashFlowProjection;
use Modules\Billing\Datas\OpenItem;
use Modules\Quotation\DTO\Money;

/**
 * Projects the bank balance forward over unpaid invoices in both directions.
 *
 * The opening balance is supplied by the caller rather than derived: the app
 * does not hold bank data, and inventing an opening figure would make every
 * number downstream wrong in the same direction.
 */
final class GetCashFlowProjectionQuery
{
    public function __construct(
        private readonly ProjectCashFlowAction $projectCashFlow,
    ) {}

    public function execute(
        float $openingBalance,
        ?DateTimeImmutable $from = null,
        ?DateTimeImmutable $to = null,
    ): CashFlowProjection {
        $from ??= new SafeDateTimeImmutable('today');
        $to ??= $from->modify('+90 days');

        return $this->projectCashFlow->execute(
            openingBalance: Money::fromFloat($openingBalance),
            receivables: $this->openItems('invoices', 'customers', 'customer_id'),
            payables: $this->openItems('supplier_invoices', 'suppliers', 'supplier_id'),
            from: $from,
            to: $to,
        );
    }

    /**
     * @return list<OpenItem>
     */
    private function openItems(string $table, string $counterpartyTable, string $foreignKey): array
    {
        $rows = DB::table($table)
            ->leftJoin($counterpartyTable, $counterpartyTable.'.id', '=', $table.'.'.$foreignKey)
            ->whereNull($table.'.deleted_at')
            ->whereNull($table.'.paid_on')
            ->whereRaw($this->outstandingCondition($table))
            ->select([
                $table.'.number',
                $table.'.issued_on',
                $table.'.due_on',
                $table.'.total',
                $table.'.paid_amount',
                $counterpartyTable.'.legal_name',
            ])
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $raw = $row->due_on ?? $row->issued_on;

            if (! is_string($raw) || $raw === '') {
                continue;
            }

            $items[] = new OpenItem(
                reference: is_string($row->number) ? $row->number : '',
                counterparty: is_string($row->legal_name) ? $row->legal_name : '',
                dueOn: new SafeDateTimeImmutable($raw),
                outstanding: Money::fromFloat(
                    (is_numeric($row->total) ? (float) $row->total : 0.0) 
                    - (is_numeric($row->paid_amount ?? null) ? (float) $row->paid_amount : 0.0),
                ),
            );
        }

        return $items;
    }

    /**
     * La condizione "residuo ancora da incassare/pagare" e' l'unico pezzo di SQL
     * grezzo qui dentro: la teniamo su una lista chiusa di tabelle cosi' il nome
     * non puo' mai arrivare dall'esterno.
     *
     * @return literal-string
     */
    private function outstandingCondition(string $table): string
    {
        return match ($table) {
            'invoices' => 'COALESCE(invoices.total, 0) - COALESCE(invoices.paid_amount, 0) > 0',
            'supplier_invoices' => 'COALESCE(supplier_invoices.total, 0) - COALESCE(supplier_invoices.paid_amount, 0) > 0',
            default => throw new \InvalidArgumentException(sprintf('Tabella non prevista per il calcolo del residuo: %s', $table)),
        };
    }
}
