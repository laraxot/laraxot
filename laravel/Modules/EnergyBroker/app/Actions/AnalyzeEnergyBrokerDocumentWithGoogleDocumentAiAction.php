<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Support\Facades\Http;
use Spatie\QueueableAction\QueueableAction;

final class AnalyzeEnergyBrokerDocumentWithGoogleDocumentAiAction
{
    use QueueableAction;

    /**
     * @return array{provider: string, text: string, pages: int, raw_response: array<string, mixed>}
     */
    public function execute(string $filename, string $mimeType, string $fileData): array
    {
        if (trim($filename) === '') {
            throw new \RuntimeException('Nome file documento mancante.');
        }

        if (trim($mimeType) === '') {
            throw new \RuntimeException('Mime type documento mancante.');
        }

        if (trim($fileData) === '') {
            throw new \RuntimeException('File documento vuoto.');
        }

        $projectNumber = $this->stringConfig('services.google_document_ai.project_number');
        $location = $this->stringConfig('services.google_document_ai.location', 'eu');
        $processorId = $this->stringConfig('services.google_document_ai.processor_id');
        $timeout = (int) config('services.google_document_ai.timeout', 90);

        $accessToken = $this->resolveAccessToken($timeout);
        $endpoint = sprintf(
            'https://%s-documentai.googleapis.com/v1/projects/%s/locations/%s/processors/%s:process',
            $location,
            $projectNumber,
            $location,
            $processorId,
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout($timeout)
            ->post($endpoint, [
                'skipHumanReview' => true,
                'rawDocument' => [
                    'mimeType' => $mimeType,
                    'content' => $fileData,
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Errore Google Document AI: '.mb_substr($response->body(), 0, 500));
        }

        $decoded = $response->json();
        if (! is_array($decoded)) {
            throw new \RuntimeException('Risposta Google Document AI non valida.');
        }

        $text = trim((string) data_get($decoded, 'document.text', ''));
        $pages = data_get($decoded, 'document.pages');
        $pageCount = is_array($pages) ? count($pages) : ($text !== '' ? 1 : 0);

        return [
            'provider' => 'google_document_ai',
            'text' => $text,
            'pages' => $pageCount,
            'raw_response' => $decoded,
        ];
    }

    private function resolveAccessToken(int $timeout): string
    {
        $configuredAccessToken = trim((string) config('services.google_document_ai.access_token', ''));
        if ($configuredAccessToken !== '') {
            return $configuredAccessToken;
        }

        $credentialsPath = $this->stringConfig('services.google_document_ai.credentials_path');
        if (! is_file($credentialsPath)) {
            throw new \RuntimeException('File credenziali Google Document AI non trovato.');
        }

        $decoded = json_decode((string) file_get_contents($credentialsPath), true);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Credenziali Google Document AI non valide.');
        }

        $clientEmail = trim((string) ($decoded['client_email'] ?? ''));
        $privateKey = (string) ($decoded['private_key'] ?? '');
        if ($clientEmail === '' || trim($privateKey) === '') {
            throw new \RuntimeException('Credenziali Google Document AI incomplete.');
        }

        $assertion = $this->buildJwtAssertion($clientEmail, $privateKey);

        $response = Http::asForm()
            ->acceptJson()
            ->timeout($timeout)
            ->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $assertion,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Errore OAuth Google Document AI: '.mb_substr($response->body(), 0, 500));
        }

        $accessToken = trim((string) data_get($response->json(), 'access_token', ''));
        if ($accessToken === '') {
            throw new \RuntimeException('Access token Google Document AI mancante.');
        }

        return $accessToken;
    }

    private function buildJwtAssertion(string $clientEmail, string $privateKey): string
    {
        $now = now()->timestamp;
        $header = $this->base64UrlEncode(json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ], JSON_THROW_ON_ERROR));
        $payload = $this->base64UrlEncode(json_encode([
            'iss' => $clientEmail,
            'sub' => $clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'iat' => $now,
            'exp' => $now + 3500,
        ], JSON_THROW_ON_ERROR));

        $signingInput = $header.'.'.$payload;
        $signature = '';
        $didSign = openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (! $didSign) {
            throw new \RuntimeException('Impossibile firmare la richiesta Google Document AI.');
        }

        return $signingInput.'.'.$this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function stringConfig(string $key, ?string $fallback = null): string
    {
        $value = trim((string) config($key, $fallback));
        if ($value === '') {
            throw new \RuntimeException(sprintf('Configurazione mancante: %s', $key));
        }

        return $value;
    }
}
