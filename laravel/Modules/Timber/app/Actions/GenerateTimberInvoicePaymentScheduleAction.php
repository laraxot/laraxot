<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Fiscal\Actions\CalculatePaymentPlanAction;
use Modules\Quotation\DTO\Money;
use Modules\Timber\Models\TimberInvoice;
use Modules\Timber\Models\TimberPaymentInstallment;
use Safe\DateTimeImmutable;
use Spatie\QueueableAction\QueueableAction;

/**
 * Genera lo scadenzario di una fattura dal totale e dai parametri di rateazione.
 * Rimuove le rate non ancora saldate e le rigenera (idempotente sulle rate aperte).
 */
final class GenerateTimberInvoicePaymentScheduleAction
{
    use QueueableAction;

    public function __construct(
        private readonly CalculatePaymentPlanAction $calculatePaymentPlan,
    ) {}

    public function execute(
        TimberInvoice $invoice,
        int $installments = 1,
        ?DateTimeImmutable $firstDueDate = null,
        int $intervalDays = 30,
        ?string $paymentMethodCode = null,
    ): void {
        $firstDue = $firstDueDate ?? new DateTimeImmutable((string) $invoice->invoice_date);
        $method = $paymentMethodCode ?? $invoice->payment_method_code;

        $plans = $this->calculatePaymentPlan->execute(
            total: Money::fromFloat((float) $invoice->total_amount),
            installments: $installments,
            firstDueDate: $firstDue,
            intervalDays: $intervalDays,
        );

        DB::transaction(function () use ($invoice, $plans, $method): void {
            TimberPaymentInstallment::query()
                ->where('invoice_id', $invoice->getKey())
                ->where('status_code', '!=', 'saldato')
                ->delete();

            foreach ($plans as $plan) {
                TimberPaymentInstallment::query()->create([
                    'invoice_id' => $invoice->getKey(),
                    'customer_id' => $invoice->customer_id,
                    'direction' => 'in',
                    'due_date' => $plan->dueDate,
                    'amount' => $plan->amount->asFloat(),
                    'payment_method_code' => $method,
                    'status_code' => 'da_saldare',
                ]);
            }
        });
    }
}
