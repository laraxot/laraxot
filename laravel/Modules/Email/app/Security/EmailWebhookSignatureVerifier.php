<?php

declare(strict_types=1);

namespace Modules\Email\Security;

use Modules\Email\Settings\EmailSettings;

final readonly class EmailWebhookSignatureVerifier
{
    public function __construct(private EmailSettings $settings) {}

    public function isValid(string $rawBody, ?string $signatureHeader): bool
    {
        $secret = $this->settings->inboundSecret();
        if ($secret === '') {
            return true;
        }

        $signature = (string) $signatureHeader;
        $expected = hash_hmac('sha256', $rawBody, $secret);

        return hash_equals($expected, $signature);
    }
}
