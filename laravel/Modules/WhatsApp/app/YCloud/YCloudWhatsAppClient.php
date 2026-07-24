<?php

declare(strict_types=1);

namespace Modules\WhatsApp\YCloud;

use Illuminate\Support\Facades\Http;
use Modules\WhatsApp\Meta\WhatsAppClientInterface;
use Modules\WhatsApp\Settings\WhatsAppSettings;

final readonly class YCloudWhatsAppClient implements WhatsAppClientInterface
{
    public function __construct(private WhatsAppSettings $settings) {}

    public function sendText(string $toPhoneE164, string $body): array
    {
        $this->assertConfigured();

        $response = Http::withHeaders(['X-API-Key' => $this->settings->ycloudApiKey()])
            ->acceptJson()
            ->timeout((int) config('services.whatsapp.timeout', 30))
            ->post($this->endpoint('/whatsapp/messages/sendDirectly'), [
                'from' => $this->settings->businessNumber(),
                'to' => $toPhoneE164,
                'type' => 'text',
                'text' => [
                    'body' => $body,
                    'preview_url' => false,
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Errore YCloud WhatsApp: '.mb_substr($response->body(), 0, 500));
        }

        $decoded = $response->json();

        return $this->toStringKeyedArray($decoded);
    }

    public function verifyConfiguration(?string $testRecipient = null): array
    {
        $this->assertConfigured();

        return [
            'ok' => true,
            'provider' => 'ycloud',
            'base_url' => $this->settings->ycloudBaseUrl(),
            'business_number' => $this->settings->businessNumber(),
            'webhook_secret_configured' => $this->settings->ycloudWebhookSecret() !== '',
            'test_recipient' => $testRecipient,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function toStringKeyedArray(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];
        foreach ($value as $key => $item) {
            if (is_string($key)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    private function endpoint(string $path): string
    {
        return $this->settings->ycloudBaseUrl().'/'.ltrim($path, '/');
    }

    private function assertConfigured(): void
    {
        if ($this->settings->businessNumber() === '') {
            throw new \RuntimeException('Numero WhatsApp aziendale mancante.');
        }

        if ($this->settings->ycloudApiKey() === '') {
            throw new \RuntimeException('API key YCloud mancante.');
        }
    }
}
