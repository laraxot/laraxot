<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use DateTimeInterface;
use Modules\Timber\Models\TimberPaymentInstallment;
use Spatie\QueueableAction\QueueableAction;

/**
 * Invia i solleciti: marca come "sollecitato" le rate in incasso scadute e
 * ancora da saldare alla data indicata. Restituisce il numero di rate sollecitate.
 */
final class SendTimberPaymentRemindersAction
{
    use QueueableAction;

    public function execute(?DateTimeInterface $asOf = null): int
    {
        $asOfDate = ($asOf ?? now())->format('Y-m-d');

        $installments = TimberPaymentInstallment::query()
            ->where('direction', 'in')
            ->where('status_code', 'da_saldare')
            ->whereDate('due_date', '<=', $asOfDate)
            ->get();

        foreach ($installments as $installment) {
            $installment->forceFill([
                'status_code' => 'sollecitato',
                'reminded_at' => now(),
            ])->save();
        }

        return $installments->count();
    }
}
