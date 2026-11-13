<?php

declare(strict_types=1);

use Tests\TestCase;

use function Safe\file_get_contents;

require_once __DIR__.'/Support/css-source.php';

uses(TestCase::class);

/**
 * DCP-291, FO-5-20 (colori forzati / alto contrasto di Windows). Test sul SORGENTE CSS (serve build): la prova e' la misura nel browser con
 * `forcedColors: 'active'` (bordo calcolato dei pulsanti, bordo e peso del passo attivo dello stepper).
 */
function forcedColorsDeclaration(string $selector, string $property): ?string
{
    $css = file_get_contents(dirname(__DIR__, 2).'/resources/css/app/20-dc-components.css');

    return sixteenCssDeclaration($css, $selector, $property, 'forced-colors: active');
}

test('in colori forzati i pulsanti hanno un bordo, altrimenti lo sfondo forzato li lascia come testo semplice', function (): void {
    expect(forcedColorsDeclaration('.btn', 'border'))->toContain('ButtonText');
});

test('in colori forzati il passo attivo dello stepper si distingue dagli altri senza usare il colore', function (): void {
    expect(forcedColorsDeclaration('.steppers-header li.active', 'border-bottom'))->toContain('solid');
});
