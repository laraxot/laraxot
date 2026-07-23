<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Payments\ReportPaymentGatewayInterface;
use Modules\Intervention\Domain\Exceptions\ReportPaymentNotAvailableException;
use Modules\Intervention\Models\Intervention;

/**
 * Starts a card payment for an intervention report and returns the hosted
 * checkout URL to redirect the customer to.
 */
final class CreateInterventionReportCheckoutAction
{
    public function __construct(private readonly ReportPaymentGatewayInterface $gateway) {}

    public function execute(Intervention $intervention, string $successUrl, string $cancelUrl): string
    {
        $amount = (float) $intervention->total_amount;
        if ($amount <= 0) {
            throw new ReportPaymentNotAvailableException('No amount is due for this report.');
        }

        if ($intervention->report_paid_at !== null) {
            throw new ReportPaymentNotAvailableException('This report has already been paid.');
        }

        if (! $intervention->signatures()->exists()) {
            throw new ReportPaymentNotAvailableException('The report must be signed before it can be paid.');
        }

        $result = $this->gateway->createCheckout(
            (int) round($amount * 100),
            'eur',
            'Rapportino intervento #'.$intervention->id,
            $successUrl,
            $cancelUrl,
            ['intervention_id' => (string) $intervention->id],
        );

        $intervention->forceFill(['report_payment_session_id' => $result['id']])->save();

        return $result['url'];
    }
}
