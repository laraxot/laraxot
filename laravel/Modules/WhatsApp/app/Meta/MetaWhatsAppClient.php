<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Meta;

use Illuminate\Support\Facades\Http;
use Modules\WhatsApp\Settings\WhatsAppSettings;

final readonly class MetaWhatsAppClient implements WhatsAppClientInterface
{
    public function __construct(private WhatsAppSettings $settings)
    {
    }

    public function sendText(string $toPhoneE164, string $body): array
    {
        $this->assertConfigured();

        $response = Http::withToken($this->settings->accessToken())
            ->acceptJson()
            ->timeout((int) config('services.whatsapp.timeout', 30))
            ->post($this->endpoint('messages'), [
                'messaging_product' => 'whatsapp',
                'to' => ltrim($toPhoneE164, '+'),
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $body,
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Errore Meta WhatsApp: ' . mb_substr($response->body(), 0, 500));
        }

        $decoded = $response->json();

        return is_array($decoded) ? $decoded : [];
    }

    public function verifyConfiguration(?string $testRecipient = null): array
    {
        $this->assertConfigured();

        $response = Http::withToken($this->settings->accessToken())
            ->acceptJson()
            ->timeout((int) config('services.whatsapp.timeout', 30))
            ->get($this->endpoint(''), [
                'fields' => 'id,display_phone_number,verified_name',
            ]);

        if (! $response->successful()) {
            return [
                'ok' => false,
                'status' => $response->status(),
                'error' => mb_substr($response->body(), 0, 500),
            ];
        }

        $result = $response->json();
        $result = is_array($result) ? $result : [];

        return [
            'ok' => true,
            'status' => $response->status(),
            'phone_number' => $result,
            'test_recipient' => $testRecipient,
        ];
    }

    private function endpoint(string $path): string
    {
        return sprintf(
            '%s/%s/%s%s',
            $this->settings->baseUrl(),
            $this->settings->apiVersion(),
            $this->settings->phoneNumberId(),
            $path !== '' ? '/' . ltrim($path, '/') : '',
        );
    }

    private function assertConfigured(): void
    {
        if ($this->settings->phoneNumberId() === '') {
            throw new \RuntimeException('Phone Number ID WhatsApp mancante.');
        }

        if ($this->settings->accessToken() === '') {
            throw new \RuntimeException('Access token WhatsApp mancante.');
        }
    }
}
