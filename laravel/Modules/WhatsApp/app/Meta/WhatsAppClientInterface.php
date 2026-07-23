<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Meta;

interface WhatsAppClientInterface
{
    /**
     * @return array<string, mixed>
     */
    public function sendText(string $toPhoneE164, string $body): array;

    /**
     * @return array<string, mixed>
     */
    public function verifyConfiguration(?string $testRecipient = null): array;
}
