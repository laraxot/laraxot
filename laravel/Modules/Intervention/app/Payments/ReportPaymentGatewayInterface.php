<?php

declare(strict_types=1);

namespace Modules\Intervention\Payments;

/**
 * Abstracts the payment provider used to collect an intervention-report
 * payment, so the use-case actions stay framework/provider agnostic.
 */
interface ReportPaymentGatewayInterface
{
    /**
     * Creates a hosted checkout session and returns its id and redirect URL.
     *
     * @param  array<string, string>  $metadata
     * @return array{id: string, url: string}
     */
    public function createCheckout(
        int $amountCents,
        string $currency,
        string $description,
        string $successUrl,
        string $cancelUrl,
        array $metadata,
    ): array;

    /**
     * Returns the settlement status of a previously created checkout session.
     *
     * @return array{paid: bool, reference: string|null}
     */
    public function fetchStatus(string $sessionId): array;
}
