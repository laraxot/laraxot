<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Tests\Feature;

use Modules\AiAssistant\Speech\BuildSpokenSummaryAction;
use Modules\AiAssistant\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

function spoken(string $markdown): string
{
    return app(BuildSpokenSummaryAction::class)->execute($markdown);
}

it('returns empty string for blank input', function (): void {
    Assert::assertSame('', spoken(''));
    Assert::assertSame('', spoken("   \n  \n"));
});

it('keeps plain prose untouched', function (): void {
    Assert::assertSame('Ho aggiornato l\'intervento numero 15.', spoken('Ho aggiornato l\'intervento numero 15.'));
});

it('strips markdown emphasis and inline code', function (): void {
    Assert::assertSame('Il totale è 85 euro e va confermato.', spoken('Il **totale** è `85 euro` e va _confermato_.'));
});

it('strips headings, blockquotes and list markers', function (): void {
    $md = "# Riepilogo\n\n- Prima voce\n- Seconda voce\n\n> Nota importante";

    Assert::assertSame('Riepilogo Prima voce Seconda voce Nota importante', spoken($md));
});

it('strips numbered list markers', function (): void {
    Assert::assertSame('Primo passo Secondo passo', spoken("1. Primo passo\n2. Secondo passo"));
});

it('converts links and images to their text', function (): void {
    Assert::assertSame('Vedi il rapportino e foto.', spoken('Vedi [il rapportino](https://x.test/r) e ![foto](img.png).'));
});

it('removes fenced code blocks', function (): void {
    $md = "Ecco il comando:\n```php\n\$x = 1;\necho \$x;\n```\nFine.";

    Assert::assertSame('Ecco il comando: Fine.', spoken($md));
});

it('removes markdown tables entirely', function (): void {
    $md = "Riepilogo materiali:\n\n| Materiale | Prezzo |\n|---|---|\n| Valvola | 5 € |\n| Raccordo | 2 € |\n\nTotale calcolato.";

    Assert::assertSame('Riepilogo materiali: Totale calcolato.', spoken($md));
});

it('limits the summary to a few sentences', function (): void {
    $md = 'Prima frase. Seconda frase. Terza frase. Quarta frase. Quinta frase.';

    Assert::assertSame('Prima frase. Seconda frase. Terza frase.', spoken($md));
});

it('caps very long output at a word boundary with an ellipsis', function (): void {
    $sentence = 'parola '.str_repeat('lunga ', 80); // one long sentence > 350 chars
    $result = spoken($sentence);

    Assert::assertLessThanOrEqual(351, mb_strlen($result));
    Assert::assertStringEndsWith('…', $result);
    Assert::assertStringNotContainsString('  ', $result);
});

it('caps a single unbroken long token without a trailing space', function (): void {
    $result = spoken(str_repeat('a', 400));

    Assert::assertStringEndsWith('…', $result);
    Assert::assertSame(351, mb_strlen($result));
});
