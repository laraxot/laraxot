<?php

declare(strict_types=1);

/**
 * PHPStan-only stubs for laravel/cashier (optional runtime dependency).
 */

namespace Laravel\Cashier;

class Checkout
{
    public static function guest(): self
    {
        return new self();
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @param  array<string, mixed>  $sessionOptions
     */
    public function create(array $items, array $sessionOptions = []): CheckoutSession
    {
        return new CheckoutSession();
    }
}

class CheckoutSession
{
    public function asStripeCheckoutSession(): StripeCheckoutSession
    {
        return new StripeCheckoutSession();
    }
}

class StripeCheckoutSession
{
    public string $id = '';

    public string $url = '';

    /** @var array<string, scalar|null> */
    public array $metadata = [];

    public string $payment_status = '';
}

class Cashier
{
    public static function stripe(): StripeClient
    {
        return new StripeClient();
    }
}

class StripeClient
{
    public StripeCheckout $checkout;
}

class StripeCheckout
{
    public StripeCheckoutSessions $sessions;
}

class StripeCheckoutSessions
{
    public function retrieve(string $sessionId): StripeCheckoutSession
    {
        return new StripeCheckoutSession();
    }
}
