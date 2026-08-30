<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Tests\Feature;

use Modules\AiAssistant\Speech\BuildSpokenSummaryAction;
use Modules\AiAssistant\Tests\TestCase;

uses(\Modules\AiAssistant\Tests\TestCase::class);

function spoken(string $markdown): string
{
    return app(BuildSpokenSummaryAction::class)->execute($markdown);
}

it('returns empty string for blank input', function (): void {
    expect(spoken(''))->toBe('')
        ->and(spoken("   \n  \n"))->toBe('');
});

it('keeps plain prose untouched', function (): void {
    expect(spoken('Ho aggiornato l\'intervento numero 15.'))
        ->toBe('Ho aggiornato l\'intervento numero 15.');
});

it('strips markdown emphasis and inline code', function (): void {
    expect(spoken('Il **totale** è `85 euro` e va _confermato_.'))
        ->toBe('Il totale è 85 euro e va confermato.');
});

it('strips headings, blockquotes and list markers', function (): void {
    $md = "# Riepilogo\n\n- Prima voce\n- Seconda voce\n\n> Nota importante";

    expect(spoken($md))->toBe('Riepilogo Prima voce Seconda voce Nota importante');
});

it('strips numbered list markers', function (): void {
    expect(spoken("1. Primo passo\n2. Secondo passo"))
        ->toBe('Primo passo Secondo passo');
});

it('converts links and images to their text', function (): void {
    expect(spoken('Vedi [il rapportino](https://x.test/r) e ![foto](img.png).'))
        ->toBe('Vedi il rapportino e foto.');
});

it('removes fenced code blocks', function (): void {
    $md = "Ecco il comando:\n```php\n\$x = 1;\necho \$x;\n```\nFine.";

    expect(spoken($md))->toBe('Ecco il comando: Fine.');
});

it('removes markdown tables entirely', function (): void {
    $md = "Riepilogo materiali:\n\n| Materiale | Prezzo |\n|---|---|\n| Valvola | 5 € |\n| Raccordo | 2 € |\n\nTotale calcolato.";

    expect(spoken($md))->toBe('Riepilogo materiali: Totale calcolato.');
});

it('limits the summary to a few sentences', function (): void {
    $md = 'Prima frase. Seconda frase. Terza frase. Quarta frase. Quinta frase.';

    expect(spoken($md))->toBe('Prima frase. Seconda frase. Terza frase.');
});

it('caps very long output at a word boundary with an ellipsis', function (): void {
    $sentence = 'parola '.str_repeat('lunga ', 80); // one long sentence > 350 chars
    $result = spoken($sentence);

    expect(mb_strlen($result))->toBeLessThanOrEqual(351)
        ->and($result)->toEndWith('…')
        ->and($result)->not->toContain('  ');
});

it('caps a single unbroken long token without a trailing space', function (): void {
    $result = spoken(str_repeat('a', 400));

    expect($result)->toEndWith('…')
        ->and(mb_strlen($result))->toBe(351);
});
