<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Support\Facades\Http;
use Spatie\QueueableAction\QueueableAction;

use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\openssl_sign;

final class AnalyzeEnergyBrokerDocumentWithGoogleDocumentAiAction
{
    use QueueableAction;

    /**
     * @return array{provider: string, text: string, pages: int, raw_response: array<array-key, mixed>}
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
        $timeoutConfig = config('services.google_document_ai.timeout', 90);
        $timeout = is_int($timeoutConfig) ? $timeoutConfig : 90;

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

        $textValue = data_get($decoded, 'document.text', '');
        $text = is_string($textValue) ? trim($textValue) : '';
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
        $tokenValue = config('services.google_document_ai.access_token', '');
        $configuredAccessToken = is_string($tokenValue) ? trim($tokenValue) : '';
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

        $emailValue = $decoded['client_email'] ?? '';
        $clientEmail = is_string($emailValue) ? trim($emailValue) : '';
        $keyValue = $decoded['private_key'] ?? '';
        $privateKey = is_string($keyValue) ? $keyValue : '';
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

        $tokenData = data_get($response->json(), 'access_token', '');
        $accessToken = is_string($tokenData) ? trim($tokenData) : '';
        if ($accessToken === '') {
            throw new \RuntimeException('Access token Google Document AI mancante.');
        }

        return $accessToken;
    }

    private function buildJwtAssertion(string $clientEmail, string $privateKey): string
    {
        $now = (int) now()->timestamp;
        $headerJson = json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ], JSON_THROW_ON_ERROR);
        $header = $this->base64UrlEncode(is_string($headerJson) ? $headerJson : '');
        $payloadJson = json_encode([
            'iss' => $clientEmail,
            'sub' => $clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'iat' => $now,
            'exp' => $now + 3500,
        ], JSON_THROW_ON_ERROR);
        $payload = $this->base64UrlEncode(is_string($payloadJson) ? $payloadJson : '');

        $signingInput = $header.'.'.$payload;
        $signature = '';

        try {
            openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Impossibile firmare la richiesta Google Document AI.', previous: $exception);
        }

        return $signingInput.'.'.$this->base64UrlEncode((string) $signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function stringConfig(string $key, ?string $fallback = null): string
    {
        $configValue = config($key, $fallback);
        $value = is_string($configValue) ? trim($configValue) : '';
        if ($value === '') {
            throw new \RuntimeException(sprintf('Configurazione mancante: %s', $key));
        }

        return $value;
    }
}
