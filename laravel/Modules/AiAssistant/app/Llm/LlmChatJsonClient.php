<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Llm;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Small shared client for "prompt in → JSON object out" LLM calls over the
 * OpenAI-compatible chat/completions API. Resolves the first configured provider
 * (DeepSeek → Groq → OpenAI). Any failure (no key, HTTP error, bad JSON) returns
 * an empty array so callers can normalize a graceful, non-blocking result.
 */
final class LlmChatJsonClient
{
    /**
     * Whether any LLM provider (DeepSeek/Groq/OpenAI) has an API key configured.
     * Callers can skip work that is useless without the LLM (and avoid needless
     * network calls, e.g. in tests) when this is false.
     */
    public function isConfigured(): bool
    {
        return $this->resolveProvider() !== null;
    }

    /**
     * @param  string|null  $modelOverride  use a specific model (e.g. a stronger
     *                                      "pro" model on retry) instead of the default
     * @return array<string, mixed>
     */
    public function complete(string $systemPrompt, string $userPrompt, ?string $modelOverride = null): array
    {
        $provider = $this->resolveProvider();
        if ($provider === null) {
            $this->logRaw('no provider configured (missing API key)', ['user_prompt' => $userPrompt]);

            return [];
        }

        $model = $modelOverride !== null && trim($modelOverride) !== '' ? trim($modelOverride) : $provider['model'];

        // A timeout / connection failure must never bubble up and crash the request:
        // treat it like any other failure and return an empty (non-blocking) result.
        try {
            $response = Http::withToken($provider['key'])
                ->acceptJson()
                ->timeout($provider['timeout'])
                ->post(rtrim($provider['base'], '/').'/chat/completions', [
                    'model' => $model,
                    'temperature' => 0,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                ]);
        } catch (\Throwable $e) {
            $this->logRaw('llm request failed', ['model' => $model, 'error' => $e->getMessage()]);

            return [];
        }

        $content = $response->json('choices.0.message.content');

        $this->logRaw('llm response', [
            // raw_content first so it's immediately visible in the log.
            'raw_content' => is_string($content) ? $content : $response->body(),
            'model' => $model,
            'status' => $response->status(),
            'ok' => $response->successful(),
            // Only the tail of the prompt (the dictated transcript) — the fixed
            // instructions above it are noise for diagnosis.
            'prompt_tail' => mb_substr($userPrompt, -400),
        ]);

        if (! $response->successful()) {
            return [];
        }

        return is_string($content) ? $this->parseJsonObject($content) : [];
    }

    /**
     * Logs the raw LLM exchange when services.llm.log_raw is on. Off by default:
     * the payload contains customer data. Use LLM_LOG_RAW=true to diagnose what the
     * model actually returns in production, then turn it back off.
     *
     * @param  array<string, mixed>  $context
     */
    private function logRaw(string $message, array $context): void
    {
        if (config('services.llm.log_raw') === true) {
            Log::info('[LLM] '.$message, $context);
        }
    }

    /**
     * @return array{base: string, key: string, model: string, timeout: int}|null
     */
    private function resolveProvider(): ?array
    {
        $deepSeekKey = config('services.deepseek.key', '');
        if (is_string($deepSeekKey) && trim($deepSeekKey) !== '') {
            $base = config('services.deepseek.base_uri', 'https://api.deepseek.com/v1');
            $model = config('services.deepseek.model', 'deepseek-chat');

            return [
                'base' => is_string($base) ? $base : 'https://api.deepseek.com/v1',
                'key' => trim($deepSeekKey),
                'model' => is_string($model) ? $model : 'deepseek-chat',
                'timeout' => $this->intConfig('services.deepseek.timeout', 45),
            ];
        }

        $groqKey = config('services.groq.key', '');
        if (is_string($groqKey) && trim($groqKey) !== '') {
            $base = config('services.groq.base_uri', 'https://api.groq.com/openai/v1');
            $model = config('services.groq.model', 'openai/gpt-oss-120b');

            return [
                'base' => is_string($base) ? $base : 'https://api.groq.com/openai/v1',
                'key' => trim($groqKey),
                'model' => is_string($model) ? $model : 'openai/gpt-oss-120b',
                'timeout' => $this->intConfig('services.groq.timeout', 45),
            ];
        }

        $openAiKey = config('services.openai.key');
        if (is_string($openAiKey) && trim($openAiKey) !== '') {
            $model = config('services.openai.model', 'gpt-4o-mini');

            return [
                'base' => 'https://api.openai.com/v1',
                'key' => trim($openAiKey),
                'model' => is_string($model) ? $model : 'gpt-4o-mini',
                'timeout' => $this->intConfig('services.openai.timeout', 45),
            ];
        }

        return null;
    }

    private function intConfig(string $key, int $default): int
    {
        $value = config($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @return array<string, mixed>
     */
    private function parseJsonObject(string $text): array
    {
        $trimmed = trim($text);
        if ($trimmed === '') {
            return [];
        }

        $start = strpos($trimmed, '{');
        $end = strrpos($trimmed, '}');
        if ($start === false || $end === false || $end < $start) {
            return [];
        }

        /** @var mixed $decoded */
        $decoded = json_decode(substr($trimmed, $start, $end - $start + 1), true);

        return is_array($decoded) ? $decoded : [];
    }
}
