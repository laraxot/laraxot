<?php

declare(strict_types=1);

uses(\Modules\AiAssistant\Tests\TestCase::class);

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\AiAssistant\Llm\LlmChatJsonClient;

function fakeChatResponse(string $content): void
{
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake([
        '*/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => $content]]],
        ], 200),
    ]);
}

it('logs the raw LLM response when services.llm.log_raw is on', function (): void {
    config(['services.llm.log_raw' => true]);
    fakeChatResponse('{"based_on_customer":"Pinco Pallino","customer":{"legal_name":"Davide"}}');

    Log::spy();

    app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    Log::shouldHaveReceived('info')->withArgs(function (string $message, array $context): bool {
        return str_contains($message, '[LLM]')
            && is_string($context['raw_content'] ?? null)
            && str_contains($context['raw_content'], 'Pinco Pallino');
    })->once();
});

it('returns an empty array (never throws) on a connection timeout', function (): void {
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    // Simulate cURL error 28 (timeout) — must be swallowed, not bubbled up.
    Http::fake(fn () => throw new ConnectionException('cURL error 28: Operation timed out'));

    expect(app(LlmChatJsonClient::class)->complete('system', 'user'))->toBe([]);
});

it('does NOT log when services.llm.log_raw is off', function (): void {
    config(['services.llm.log_raw' => false]);
    fakeChatResponse('{"customer":{"legal_name":"Davide"}}');

    Log::spy();

    app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    Log::shouldNotHaveReceived('info');
});

it('logs the raw body even when the provider returns an HTTP error', function (): void {
    config(['services.llm.log_raw' => true, 'services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake(['*/chat/completions' => Http::response('rate limited', 429)]);

    Log::spy();

    $result = app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    expect($result)->toBe([]);
    Log::shouldHaveReceived('info')->withArgs(function (string $message, array $context): bool {
        return ($context['ok'] ?? null) === false && ($context['status'] ?? null) === 429;
    })->once();
});
