<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use Modules\Timber\Models\TimberPaymentInstallment;
use Spatie\QueueableAction\QueueableAction;

/**
 * Segna una rata come saldata; se tutte le rate della fattura risultano saldate,
 * porta la fattura collegata allo stato "pagata".
 */
final class MarkTimberInstallmentPaidAction
{
    use QueueableAction;

    public function execute(TimberPaymentInstallment $installment, ?DateTimeInterface $paidAt = null): TimberPaymentInstallment
    {
        return DB::transaction(function () use ($installment, $paidAt): TimberPaymentInstallment {
            $installment->forceFill([
                'status_code' => 'saldato',
                'paid_at' => $paidAt ?? now(),
            ])->save();

            $invoice = $installment->invoice;

            if ($invoice !== null) {
                $hasOpenInstallments = TimberPaymentInstallment::query()
                    ->where('invoice_id', $invoice->getKey())
                    ->where('status_code', '!=', 'saldato')
                    ->exists();

                if (! $hasOpenInstallments) {
                    $invoice->forceFill(['status_code' => 'paid'])->save();
                }
            }

            return $installment->refresh();
        });
    }
}
