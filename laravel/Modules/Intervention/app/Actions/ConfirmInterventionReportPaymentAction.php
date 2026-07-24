<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Illuminate\Support\Facades\Date;
use Modules\Intervention\Contracts\ReportPaymentGatewayContract;
use Modules\Intervention\Models\Intervention;
use Spatie\QueueableAction\QueueableAction;

/**
 * Confirms a checkout session with the provider and marks the matching
 * intervention report as paid. Idempotent.
 */
final class ConfirmInterventionReportPaymentAction
{
    use QueueableAction;

    public function __construct(private readonly ReportPaymentGatewayContract $gateway) {}

    public function execute(string $sessionId): bool
    {
        $status = $this->gateway->fetchStatus($sessionId);
        if (! $status['paid']) {
            return false;
        }

        $intervention = Intervention::query()
            ->where('report_payment_session_id', $sessionId)
            ->first();
        if ($intervention === null) {
            return false;
        }

        if ($intervention->report_paid_at === null) {
            $intervention->forceFill(['report_paid_at' => Date::now()])->save();
        }

        return true;
    }
}
