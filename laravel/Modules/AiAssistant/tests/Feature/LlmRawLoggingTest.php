<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Tests\Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\AiAssistant\Llm\LlmChatJsonClient;
use Modules\AiAssistant\Tests\TestCase;

uses(\Modules\AiAssistant\Tests\TestCase::class);

function fakeChatResponse(string $content): void
{
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake([
        '*/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => $content]]],
        ], 200),
    ]);
}

/**
 * @return list<array{level: mixed, message: mixed, context: mixed}>
 */
function captureLogEntries(): array
{
    $entries = [];
    Log::listen(function (mixed $level, mixed $message, mixed $context = []) use (&$entries): void {
        $entries[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    });

    return $entries;
}

it('logs the raw LLM response when services.llm.log_raw is on', function (): void {
    config(['services.llm.log_raw' => true]);
    fakeChatResponse('{"based_on_customer":"Pinco Pallino","customer":{"legal_name":"Davide"}}');

    $entries = captureLogEntries();

    app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    $infoLogs = array_values(array_filter(
        $entries,
        static fn (array $entry): bool => $entry['level'] === 'info'
            && is_string($entry['message'])
            && str_contains($entry['message'], '[LLM]')
            && is_array($entry['context'])
            && is_string($entry['context']['raw_content'] ?? null)
            && str_contains($entry['context']['raw_content'], 'Pinco Pallino'),
    ));

    expect($infoLogs)->toHaveCount(1);
});

it('returns an empty array (never throws) on a connection timeout', function (): void {
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake(fn () => throw new ConnectionException('cURL error 28: Operation timed out'));

    expect(app(LlmChatJsonClient::class)->complete('system', 'user'))->toBe([]);
});

it('does NOT log when services.llm.log_raw is off', function (): void {
    config(['services.llm.log_raw' => false]);
    fakeChatResponse('{"customer":{"legal_name":"Davide"}}');

    $entries = captureLogEntries();

    app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    $infoLogs = array_values(array_filter(
        $entries,
        static fn (array $entry): bool => $entry['level'] === 'info',
    ));

    expect($infoLogs)->toBeEmpty();
});

it('logs the raw body even when the provider returns an HTTP error', function (): void {
    config(['services.llm.log_raw' => true, 'services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake(['*/chat/completions' => Http::response('rate limited', 429)]);

    $entries = captureLogEntries();

    $result = app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    expect($result)->toBe([]);

    $errorLogs = array_values(array_filter(
        $entries,
        static fn (array $entry): bool => $entry['level'] === 'info'
            && is_array($entry['context'])
            && ($entry['context']['ok'] ?? null) === false
            && ($entry['context']['status'] ?? null) === 429,
    ));

    expect($errorLogs)->toHaveCount(1);
});
