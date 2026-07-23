<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Security;

use Modules\WhatsApp\Settings\WhatsAppSettings;

final readonly class YCloudWebhookSignatureVerifier
{
    public function __construct(private WhatsAppSettings $settings)
    {
    }

    public function isValid(string $rawBody, ?string $signatureHeader): bool
    {
        $secret = $this->settings->ycloudWebhookSecret();
        if ($secret === '') {
            return true;
        }

        $parts = $this->parseSignatureHeader((string) $signatureHeader);
        $timestamp = $parts['t'] ?? '';
        $signature = $parts['s'] ?? '';

        if ($timestamp === '' || $signature === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $rawBody . '.', $secret);

        return hash_equals($expected, $signature);
    }

    /**
     * @return array<string, string>
     */
    private function parseSignatureHeader(string $header): array
    {
        $parsed = [];
        foreach (explode(',', $header) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');
            if ($key !== '' && $value !== '') {
                $parsed[$key] = $value;
            }
        }

        return $parsed;
    }
}
