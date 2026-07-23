<?php

declare(strict_types=1);

namespace Modules\Intervention\Payments;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Checkout;
use Modules\Intervention\Payments\ReportPaymentGatewayInterface;

/**
 * Stripe-backed payment gateway using Laravel Cashier guest checkout.
 * Lives in Infrastructure so the Stripe SDK never leaks into use-cases.
 */
final class CashierReportPaymentGateway implements ReportPaymentGatewayInterface
{
    public function createCheckout(
        int $amountCents,
        string $currency,
        string $description,
        string $successUrl,
        string $cancelUrl,
        array $metadata,
    ): array {
        $checkout = Checkout::guest()->create([
            [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $amountCents,
                    'product_data' => ['name' => $description],
                ],
                'quantity' => 1,
            ],
        ], [
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => $metadata,
        ]);

        $session = $checkout->asStripeCheckoutSession();

        return ['id' => (string) $session->id, 'url' => (string) $session->url];
    }

    public function fetchStatus(string $sessionId): array
    {
        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);
        $reference = $session->metadata['intervention_id'] ?? null;

        return [
            'paid' => $session->payment_status === 'paid',
            'reference' => is_scalar($reference) ? (string) $reference : null,
        ];
    }
}
