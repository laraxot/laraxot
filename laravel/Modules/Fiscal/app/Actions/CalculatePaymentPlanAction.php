<?php

declare(strict_types=1);

namespace Modules\Fiscal\Actions;

use InvalidArgumentException;
use Modules\Fiscal\Datas\PaymentInstallmentPlan;
use Modules\Quotation\DTO\Money;
use Safe\DateTimeImmutable;
use Spatie\QueueableAction\QueueableAction;

/**
 * Splits an amount into N payment installments (scadenze), distributing the
 * rounding remainder cent-by-cent so the sum matches the original total.
 */
final class CalculatePaymentPlanAction
{
    use QueueableAction;

    /**
     * @return array<int, PaymentInstallmentPlan>
     */
    public function execute(
        Money $total,
        int $installments,
        DateTimeImmutable $firstDueDate,
        int $intervalDays,
    ): array {
        if ($installments < 1) {
            throw new InvalidArgumentException('Il numero di rate deve essere almeno 1.');
        }

        if ($intervalDays < 0) {
            throw new InvalidArgumentException('L intervallo tra le rate non puo essere negativo.');
        }

        $totalCents = (int) round($total->asFloat() * 100);
        $base = intdiv($totalCents, $installments);
        $remainder = $totalCents - ($base * $installments);

        $plans = [];
        for ($index = 0; $index < $installments; $index++) {
            $cents = $base + ($index < $remainder ? 1 : 0);
            $dueDate = $firstDueDate->modify(sprintf('+%d days', $index * $intervalDays));

            $plans[] = new PaymentInstallmentPlan(
                dueDate: $dueDate->format('Y-m-d'),
                amount: Money::fromFloat($cents / 100),
            );
        }

        return $plans;
    }
}
