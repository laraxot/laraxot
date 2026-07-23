<?php

declare(strict_types=1);

/**
 * Contratto markup story 7-3: niente handler JS assente; navigazione come link.
 */
test('il blade segnalazione-02-dati espone stepper nav e non usa confirmAndProceed', function (): void {
    $bladePath = dirname(__DIR__, 4).'/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php';
    expect(file_exists($bladePath))->toBeTrue('Blade tema mancante: '.$bladePath);

    $html = (string) file_get_contents($bladePath);
    expect($html)->toContain('steppers-btn-confirm');
    expect($html)->toContain('steppers-btn-prev');
    expect($html)->toContain('fixcity::segnalazione.actions.next.label');
    expect($html)->toContain('fixcity::segnalazione.breadcrumb.home.label');
    expect($html)->not->toContain('fixcity::common.');
    expect($html)->not->toContain('confirmAndProceed()');
});

/**
 * Story 7-7: il blade espone gli elementi strutturali necessari per lo stepper responsive.
 */
test('il blade segnalazione-02-dati ha steppers-header e steppers-index per la responsive', function (): void {
    $bladePath = dirname(__DIR__, 4).'/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-02-dati.blade.php';
    $html = (string) file_get_contents($bladePath);

    // Elementi strutturali del stepper necessari per il CSS responsive
    expect($html)->toContain('steppers-header');
    expect($html)->toContain('steppers-index');
    // Il blade usa classi dinamiche Blade (non literal 'class="active"')
    expect($html)->toContain("'active'");
    expect($html)->toContain("'confirmed'");
});

/**
 * Story 7-7: il CSS §27.18 usa la strategia "nasconde non-active" invece di overflow-x scroll.
 */
test('segnalazione-parity.css §27.18 nasconde i passi non-attivi su mobile/tablet', function (): void {
    $cssPath = dirname(__DIR__, 4).'/Themes/Sixteen/resources/css/segnalazione-parity.css';
    expect(file_exists($cssPath))->toBeTrue('CSS parity mancante: '.$cssPath);

    $css = (string) file_get_contents($cssPath);

    // Deve contenere il nuovo titolo sezione §27.18 responsive
    expect($css)->toContain('27.18 Stepper — responsive tablet/mobile');

    // Deve avere la regola che nasconde i li:not(.active)
    expect($css)->toContain('.steppers-header ul li:not(.active)');

    // Deve riabilitare .steppers-index sul breakpoint < 992px per 02-dati
    // Verificiamo che il selettore scoped esista nel contesto del media query
    expect($css)->toContain('.page-content[data-slug="tests.segnalazione-02-dati"] .steppers-index');

    // NON deve più usare overflow-x: auto per il blocco §27.18 (era il bug)
    // Ricercando il pattern specifico della vecchia riga bugata
    expect($css)->not->toContain('27.18 Stepper — scroll orizzontale');
});
