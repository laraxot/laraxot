<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Meta;

use Illuminate\Support\Facades\Http;
use Modules\WhatsApp\Settings\WhatsAppSettings;

final readonly class MetaWhatsAppClient implements WhatsAppClientInterface
{
    public function __construct(private WhatsAppSettings $settings) {}

    public function sendText(string $toPhoneE164, string $body): array
    {
        $this->assertConfigured();

        $response = Http::withToken($this->settings->accessToken())
            ->acceptJson()
            ->timeout($this->timeoutSeconds())
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
            throw new \RuntimeException('Errore Meta WhatsApp: '.mb_substr($response->body(), 0, 500));
        }

        $decoded = $response->json();

        return $this->toStringKeyedArray($decoded);
    }

    public function verifyConfiguration(?string $testRecipient = null): array
    {
        $this->assertConfigured();

        $response = Http::withToken($this->settings->accessToken())
            ->acceptJson()
            ->timeout($this->timeoutSeconds())
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
        $result = $this->toStringKeyedArray($result);

        return [
            'ok' => true,
            'status' => $response->status(),
            'phone_number' => $result,
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

    private function timeoutSeconds(): int
    {
        $configured = config('services.whatsapp.timeout', 30);

        return is_numeric($configured) ? (int) $configured : 30;
    }

    private function endpoint(string $path): string
    {
        return sprintf(
            '%s/%s/%s%s',
            $this->settings->baseUrl(),
            $this->settings->apiVersion(),
            $this->settings->phoneNumberId(),
            $path !== '' ? '/'.ltrim($path, '/') : '',
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
