<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Security;

use Modules\WhatsApp\Settings\WhatsAppSettings;

final readonly class MetaWebhookSignatureVerifier
{
    public function __construct(private WhatsAppSettings $settings) {}

    public function isValid(string $rawBody, ?string $signatureHeader): bool
    {
        $appSecret = $this->settings->appSecret();
        if ($appSecret === '') {
            return false;
        }

        $signature = trim((string) $signatureHeader);
        if ($signature === '' || ! str_starts_with($signature, 'sha256=')) {
            return false;
        }

        $expected = 'sha256='.hash_hmac('sha256', $rawBody, $appSecret);

        return hash_equals($expected, $signature);
    }
}
