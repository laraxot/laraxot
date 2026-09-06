<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Tests\Feature;

use ArrayObject;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\AiAssistant\Llm\LlmChatJsonClient;
use Modules\AiAssistant\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

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
 * Il contenitore va restituito per riferimento: i log arrivano *dopo* questa
 * chiamata, quindi un array restituito per valore resterebbe vuoto per sempre.
 *
 * @return ArrayObject<int, array{level: mixed, message: mixed, context: mixed}>
 */
function captureLogEntries(): ArrayObject
{
    /** @var ArrayObject<int, array{level: mixed, message: mixed, context: mixed}> $entries */
    $entries = new ArrayObject();

    // Da Laravel 9 il listener riceve un solo MessageLogged, non i tre argomenti
    // separati della firma storica: con la vecchia firma la closure esplode al
    // primo log emesso.
    Log::listen(function (MessageLogged $event) use ($entries): void {
        $entries[] = [
            'level' => $event->level,
            'message' => $event->message,
            'context' => $event->context,
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
        $entries->getArrayCopy(),
        static fn (array $entry): bool => $entry['level'] === 'info'
            && is_string($entry['message'])
            && str_contains($entry['message'], '[LLM]')
            && is_array($entry['context'])
            && is_string($entry['context']['raw_content'] ?? null)
            && str_contains($entry['context']['raw_content'], 'Pinco Pallino'),
    ));

    Assert::assertCount(1, $infoLogs);
});

it('returns an empty array (never throws) on a connection timeout', function (): void {
    config(['services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake(fn () => throw new ConnectionException('cURL error 28: Operation timed out'));

    Assert::assertSame([], app(LlmChatJsonClient::class)->complete('system', 'user'));
});

it('does NOT log when services.llm.log_raw is off', function (): void {
    config(['services.llm.log_raw' => false]);
    fakeChatResponse('{"customer":{"legal_name":"Davide"}}');

    $entries = captureLogEntries();

    app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    $infoLogs = array_values(array_filter(
        $entries->getArrayCopy(),
        static fn (array $entry): bool => $entry['level'] === 'info',
    ));

    Assert::assertEmpty($infoLogs);
});

it('logs the raw body even when the provider returns an HTTP error', function (): void {
    config(['services.llm.log_raw' => true, 'services.deepseek.key' => 'test-key', 'services.deepseek.base_uri' => 'https://api.deepseek.com/v1']);
    Http::fake(['*/chat/completions' => Http::response('rate limited', 429)]);

    $entries = captureLogEntries();

    $result = app(LlmChatJsonClient::class)->complete('system', 'user prompt');

    Assert::assertSame([], $result);

    $errorLogs = array_values(array_filter(
        $entries->getArrayCopy(),
        static fn (array $entry): bool => $entry['level'] === 'info'
            && is_array($entry['context'])
            && ($entry['context']['ok'] ?? null) === false
            && ($entry['context']['status'] ?? null) === 429,
    ));

    Assert::assertCount(1, $errorLogs);
});
