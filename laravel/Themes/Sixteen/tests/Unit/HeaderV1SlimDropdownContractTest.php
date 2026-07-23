<?php

declare(strict_types=1);

/**
 * Contratto Story 7-54: slim header section — dropdown lingua/utente via data-bs-toggle + app.js (no Alpine inline).
 */
test('header v1 slim usa data-bs-toggle per lingua e utente e non langOpen/userOpen Alpine', function (): void {
    $laravelRoot = dirname(__DIR__, 4);
    $themeRoot = dirname(__DIR__, 2);
    $bladePath = $laravelRoot.'/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php';
    expect(file_exists($bladePath))->toBeTrue('Blade header v1 mancante: '.$bladePath);

    $v1 = (string) file_get_contents($bladePath);
    expect($v1)->toContain('language-switcher');
    expect($v1)->toContain('user-dropdown');

    $langPath = $themeRoot.'/resources/views/components/sections/header/partials/language-switcher.blade.php';
    $userPath = $themeRoot.'/resources/views/components/sections/header/partials/user-dropdown.blade.php';
    expect(file_exists($langPath))->toBeTrue('Partial language-switcher mancante');
    expect(file_exists($userPath))->toBeTrue('Partial user-dropdown mancante');

    $langHtml = (string) file_get_contents($langPath);
    $userHtml = (string) file_get_contents($userPath);

    expect($langHtml)->toContain('data-bs-toggle="dropdown"');
    expect($langHtml)->toContain('aria-controls="languages"');
    expect($userHtml)->toContain('id="header-user-toggle"');
    expect($userHtml)->toContain('data-bs-toggle="dropdown"');
    expect($userHtml)->toContain('btn btn-primary btn-icon btn-full');
    expect($userHtml)->toContain('rounded-icon');
    expect($userHtml)->toContain('d-none d-lg-block');
    expect($userHtml)->toContain('icon-white d-none d-lg-block');
    expect($userHtml)->not->toContain('nav-link dropdown-toggle');
    expect($userHtml)->not->toContain('text-gray-800');
    expect($userHtml)->toContain("route('services.categories')");
    expect($userHtml)->not->toContain("route('tests.view'");

    expect($v1.$langHtml.$userHtml)->not->toContain('langOpen');
    expect($v1.$langHtml.$userHtml)->not->toContain('userOpen');
    expect($v1.$langHtml.$userHtml)->not->toContain('x-data="{ langOpen');
    expect($v1.$langHtml.$userHtml)->not->toContain('x-data="{ userOpen');
});

test('header v1 slim wrapper non forza background hex inline (token CSS)', function (): void {
    $bladePath = dirname(__DIR__, 4).'/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php';
    $html = (string) file_get_contents($bladePath);

    expect($html)->not->toContain('style="background-color: #0066CC"');
    expect($html)->not->toContain("style='background-color: #0066CC'");
});

test('story 8-105: app.css slim usa token --dc-green-dark e nav attivo centrato', function (): void {
    $cssPath = dirname(__DIR__, 2).'/resources/css/app.css';
    expect(file_exists($cssPath))->toBeTrue('app.css tema mancante: '.$cssPath);

    $css = (string) file_get_contents($cssPath);

    expect($css)->toContain('.it-header-slim-wrapper');
    expect($css)->toContain('background: var(--dc-green-dark)');
    expect($css)->toContain('Story 8-105');
    expect($css)->toContain('.it-header-navbar-wrapper .navbar-nav .nav-link.active::after');
    expect($css)->toContain('transform: translateX(-50%)');
});

test('story 8-105: desktop ricerca stack verticale Cerca sopra lente', function (): void {
    $cssPath = dirname(__DIR__, 2).'/resources/css/app.css';
    $css = (string) file_get_contents($cssPath);

    expect($css)->toContain('@media (min-width: 992px)');
    expect($css)->toContain('.it-header-center-content-wrapper .it-search-wrapper');
    expect($css)->toContain('flex-direction: column');
});

test('story 8-105: CTA header btn-primary mappa verde comune', function (): void {
    $cssPath = dirname(__DIR__, 2).'/resources/css/app.css';
    $css = (string) file_get_contents($cssPath);

    expect($css)->toContain('.it-header-center-wrapper .btn-primary');
    expect($css)->toContain('background: var(--dc-green)');
});
