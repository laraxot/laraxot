<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Speech;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use function Safe\fopen;
use function Safe\json_decode;
use function Safe\preg_match;

final class OpenAiWhisperTranscriptionProvider implements SpeechTranscriptionProviderInterface
{
    public function transcribe(UploadedFile $audio, string $language): string
    {
        $apiKeyValue = config('services.openai.key');
        $apiKey = is_string($apiKeyValue) ? $apiKeyValue : '';
        if (trim($apiKey) === '' || $apiKey === 'invalid key') {
            throw new \RuntimeException('OPENAI_API_KEY mancante in configurazione (config: services.openai.key).');
        }

        $baseUrlValue = config('speech.transcription.openai.base_url', 'https://api.openai.com/v1/');
        $modelValue = config('speech.transcription.openai.model', 'whisper-1');
        $timeoutValue = config('speech.transcription.openai.timeout', 60);
        $baseUrl = is_string($baseUrlValue) ? $baseUrlValue : 'https://api.openai.com/v1/';
        $model = is_string($modelValue) ? $modelValue : 'whisper-1';
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
                    Log::error('OpenAI Whisper error', [
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

                    throw new \RuntimeException('Errore Whisper OpenAI: '.$message);
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

                if (self::shouldRejectTranscriptionQuality($json)) {
                    Log::warning('OpenAI Whisper transcription rejected by quality guard', [
                        'attempt' => $attempt,
                        'provider' => 'openai_whisper',
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

        throw new \RuntimeException('Errore Whisper OpenAI: '.$lastError?->getMessage());
    }

    /**
     * Heuristics/guard su output "verbose_json" di Whisper.
     *
     * @param array<string, mixed> $json
     */
    public static function shouldRejectTranscriptionQuality(array $json): bool
    {
        try {
            $segments = $json['segments'] ?? null;
            if (! is_array($segments) || $segments === []) {
                return false;
            }

            $fullText = '';
            if (isset($json['text']) && is_string($json['text'])) {
                $fullText = trim($json['text']);
            }
            if ($fullText === '') {
                return false;
            }

            if (mb_strlen($fullText) <= 40 && preg_match('/\\p{L}/u', $fullText) === 1) {
                return false;
            }

            $avgLogprobs = [];
            $noSpeechProbs = [];
            $compressionRatios = [];

            foreach ($segments as $seg) {
                if (! is_array($seg)) {
                    continue;
                }
                $segText = '';
                if (isset($seg['text']) && is_string($seg['text'])) {
                    $segText = trim($seg['text']);
                }
                if ($segText === '') {
                    continue;
                }
                if (isset($seg['avg_logprob']) && is_numeric($seg['avg_logprob'])) {
                    $avgLogprobs[] = (float) $seg['avg_logprob'];
                }
                if (isset($seg['no_speech_prob']) && is_numeric($seg['no_speech_prob'])) {
                    $noSpeechProbs[] = (float) $seg['no_speech_prob'];
                }
                if (isset($seg['compression_ratio']) && is_numeric($seg['compression_ratio'])) {
                    $compressionRatios[] = (float) $seg['compression_ratio'];
                }
            }

            if ($avgLogprobs === [] && $noSpeechProbs === [] && $compressionRatios === []) {
                return false;
            }

            $minAvgLogprobAllowed = -0.5;
            $maxNoSpeechProbAllowed = 0.6;
            $maxCompressionRatioAllowed = 2.4;

            if ($avgLogprobs !== [] && min($avgLogprobs) < $minAvgLogprobAllowed) {
                return true;
            }

            if ($noSpeechProbs !== []) {
                $avgNoSpeech = array_sum($noSpeechProbs) / max(count($noSpeechProbs), 1);
                $maxNoSpeech = max($noSpeechProbs);
                if ($avgNoSpeech >= $maxNoSpeechProbAllowed || $maxNoSpeech >= 0.85) {
                    return true;
                }
            }

            if ($compressionRatios !== [] && max($compressionRatios) > $maxCompressionRatioAllowed) {
                return true;
            }

            return false;
        } catch (\Throwable) {
            return false;
        }
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

