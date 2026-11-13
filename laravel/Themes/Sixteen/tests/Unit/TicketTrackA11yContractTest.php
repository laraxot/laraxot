<?php

declare(strict_types=1);

use function Safe\file_get_contents;
use function Safe\preg_match;

require_once __DIR__.'/Support/css-source.php';

/**
 * DCP-291, FO-5-01 e FO-5-18. Sono test sul SORGENTE: dicono che la regola e' scritta, non che il tema compilato
 * la applichi. La prova vera e' la misura nel browser dopo `npm run build` (larghezza del campo = larghezza del genitore
 * a 320 e 375 px; contrasto del badge >= 4.5:1).
 */
$themeRoot = dirname(__DIR__, 2);
$trackCss = static fn (): string => file_get_contents($themeRoot.'/resources/css/app/21-ticket-track.css');

test('il campo del codice di tracciamento occupa tutta la larghezza del contenitore sotto 576 px', function () use ($trackCss): void {
    // Bootstrap Italia: `.input-group > .form-control { width: 1% }`. In colonna lo stretch non agisce (width non e' auto), quindi
    // il campo resta largo quanto il suo padding (16 px a 320 e 375). La regola deve avere specificita' maggiore di quella di Bootstrap.
    $mobile = array_values(array_filter(
        sixteenCssRules($trackCss()),
        static fn (array $rule): bool => str_contains($rule['media'], 'max-width: 575.98px') && str_contains($rule['selector'], '.dc-ticket-track__input'),
    ));

    $withWidth = array_values(array_filter($mobile, static fn (array $rule): bool => ($rule['declarations']['width'] ?? '') === '100%'));

    expect($withWidth)->not->toBeEmpty();
    expect($withWidth[0]['selector'])->toContain('.dc-ticket-track__controls > .dc-ticket-track__input.form-control');
});

test('il badge di stato della traccia ha contrasto AA sul verde del tema', function () use ($trackCss, $themeRoot): void {
    $color = sixteenCssDeclaration($trackCss(), '.dc-ticket-track__status', 'color');
    preg_match('/--bs-primary:\s*(#[0-9a-f]{6})/i', file_get_contents($themeRoot.'/resources/css/style-apply.css'), $primary);
    $primaryColor = $primary[1] ?? '';

    // Il markup applica sempre `bg-primary` (stato-indipendente): un solo accoppiamento colore/sfondo da misurare.
    $markup = file_get_contents($themeRoot.'/resources/views/components/pages/ticket-track.blade.php');
    expect($markup)->toContain('badge rounded-pill bg-primary dc-ticket-track__status');

    expect($color)->not->toBeNull();
    expect($primaryColor)->not->toBe('');
    expect(sixteenContrastRatio((string) $color, $primaryColor))->toBeGreaterThanOrEqual(4.5);
});
