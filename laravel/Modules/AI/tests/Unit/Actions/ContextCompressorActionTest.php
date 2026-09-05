<?php

declare(strict_types=1);

namespace Modules\AI\Tests\Unit\Actions;

use Modules\AI\Actions\ContextCompressorAction;
use Modules\AI\Tests\TestCase;

use function Safe\putenv;

uses(TestCase::class);

/**
 * Forces the extractive (non-OpenAI) fallback path by clearing OPENAI_API_KEY
 * for the duration of the callback, then restores the original value.
 *
 * @template TReturn
 *
 * @param  callable(): TReturn  $callback
 * @return TReturn
 */
function withoutOpenAiKey(callable $callback)
{
    $originalKey = getenv('OPENAI_API_KEY');

    putenv('OPENAI_API_KEY');

    try {
        return $callback();
    } finally {
        if ($originalKey === false) {
            putenv('OPENAI_API_KEY');
        } else {
            putenv("OPENAI_API_KEY={$originalKey}");
        }
    }
}

describe('ContextCompressorAction', function (): void {
    test('_returns_text_unchanged_when_within_target_length', function (): void {
        $text = 'Short text well within the target length.';

        $result = ContextCompressorAction::compress($text, 200);

        $this->assertSame($text, $result);
    });

    test('_extractive_fallback_stays_within_target_and_keeps_sentence_boundaries', function (): void {
        $sentences = [];
        for ($i = 1; $i <= 20; $i++) {
            $sentences[] = "Questa e la frase numero {$i} di un testo lungo da comprimere.";
        }
        $text = implode(' ', $sentences);

        $result = withoutOpenAiKey(fn () => ContextCompressorAction::compress($text, 200));

        $this->assertLessThanOrEqual(200, mb_strlen($result));
        $this->assertStringStartsWith('Questa e la frase numero 1', $result);
        $this->assertMatchesRegularExpression('/\.$/', $result);
    });

    test('_extractive_fallback_hard_truncates_when_no_sentence_boundary_fits', function (): void {
        $text = str_repeat('a', 500);

        $result = withoutOpenAiKey(fn () => ContextCompressorAction::compress($text, 50));

        $this->assertSame(str_repeat('a', 50), $result);
    });
});
