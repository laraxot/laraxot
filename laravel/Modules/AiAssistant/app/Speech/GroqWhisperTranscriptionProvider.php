<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Speech;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

use function Safe\fopen;
use function Safe\json_decode;

final class GroqWhisperTranscriptionProvider implements SpeechTranscriptionProviderInterface
{
    public function transcribe(UploadedFile $audio, string $language): string
    {
        $apiKeyValue = config('services.groq.key');
        $apiKey = is_string($apiKeyValue) ? $apiKeyValue : '';
        if (trim($apiKey) === '') {
            throw new \RuntimeException('GROQ_API_KEY mancante in configurazione (config: services.groq.key).');
        }

        $baseUrlValue = config('speech.transcription.groq.base_url');
        if (! is_string($baseUrlValue) || trim($baseUrlValue) === '') {
            $baseUrlValue = config('services.groq.base_uri', 'https://api.groq.com/openai/v1');
        }
        $baseUrl = is_string($baseUrlValue) ? $baseUrlValue : 'https://api.groq.com/openai/v1';
        $baseUrl = rtrim($baseUrl, '/').'/';

        $modelValue = config('speech.transcription.groq.model', 'whisper-large-v3-turbo');
        $timeoutValue = config('speech.transcription.groq.timeout', 60);
        $model = is_string($modelValue) ? $modelValue : 'whisper-large-v3-turbo';
        $timeout = is_int($timeoutValue) ? $timeoutValue : 60;

        $client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => 'Bearer '.$apiKey,
            ],
            'http_errors' => false,
            'timeout' => $timeout,
        ]);

        $originalName = $audio->getClientOriginalName() ?: 'audio.webm';
        $contentType = $this->guessContentType($originalName);

        $lastError = null;
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                $response = $client->post('audio/transcriptions', [
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => fopen((string) $audio->getRealPath(), 'r'),
                            'filename' => $originalName,
                            'headers' => [
                                'Content-Type' => $contentType,
                            ],
                        ],
                        [
                            'name' => 'model',
                            'contents' => $model,
                        ],
                        [
                            'name' => 'temperature',
                            'contents' => '0',
                        ],
                        [
                            'name' => 'response_format',
                            'contents' => 'verbose_json',
                        ],
                        [
                            'name' => 'language',
                            'contents' => $language,
                        ],
                    ],
                ]);

                $status = $response->getStatusCode();
                $body = (string) $response->getBody();

                if ($status < 200 || $status >= 300) {
                    Log::error('Groq Whisper error', [
                        'status' => $status,
                        'body' => mb_substr($body, 0, 2000),
                    ]);

                    $decoded = json_decode($body, true);
                    $message = '';
                    if (
                        is_array($decoded)
                        && isset($decoded['error'])
                        && is_array($decoded['error'])
                        && isset($decoded['error']['message'])
                        && is_string($decoded['error']['message'])
                    ) {
                        $message = $decoded['error']['message'];
                    } else {
                        $message = mb_substr($body, 0, 500);
                    }

                    throw new \RuntimeException('Errore Whisper Groq: '.$message);
                }

                $json = json_decode($body, true);
                /** @var array<string, mixed> $json */
                $json = is_array($json) ? $json : [];
                $text = '';
                if (isset($json['text']) && is_string($json['text'])) {
                    $text = trim($json['text']);
                }

                if ($text === '') {
                    return '';
                }

                if (OpenAiWhisperTranscriptionProvider::shouldRejectTranscriptionQuality($json)) {
                    Log::warning('Groq Whisper transcription rejected by quality guard', [
                        'attempt' => $attempt,
                        'provider' => 'groq_whisper',
                        'language' => $language,
                        'preview' => mb_substr($text, 0, 160),
                    ]);
                    if ($attempt < 2) {
                        continue;
                    }
                    throw new \RuntimeException('TRANSCRIPTION_REJECTED: qualità bassa (avg_logprob/no_speech_prob/compression_ratio)');
                }

                return $text;
            } catch (\Throwable $e) {
                $lastError = $e;
                if ($attempt < 2) {
                    continue;
                }
                throw $e;
            }
        }

        throw new \RuntimeException('Errore Whisper Groq: '.$lastError?->getMessage());
    }

    private function guessContentType(string $originalName): string
    {
        $lowerName = strtolower($originalName);
        if (str_ends_with($lowerName, '.wav')) {
            return 'audio/wav';
        }
        if (str_ends_with($lowerName, '.ogg')) {
            return 'audio/ogg';
        }

        return 'audio/webm';
    }
}
