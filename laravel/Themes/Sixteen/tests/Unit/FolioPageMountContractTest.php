<?php

declare(strict_types=1);

/**
 * Contratto pagine Folio dinamiche: mount() con params route, no request()->route() in @php.
 */
test('container0 slug0 pages usano Volt Component mount non request route', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $paths = [
        $themeRoot.'/resources/views/pages/[container0]/index.blade.php',
        $themeRoot.'/resources/views/pages/[container0]/[slug0]/index.blade.php',
        $themeRoot.'/resources/views/pages/[container0]/[slug0]/[container1]/index.blade.php',
    ];

    foreach ($paths as $path) {
        $html = (string) file_get_contents($path);
        expect($html)->toContain('extends Component');
        expect($html)->toContain('function mount(string $container0');
        expect($html)->not->toContain("request()->route('container0'");
        expect($html)->not->toContain('request()->route("container0"');
        expect($html)->not->toMatch('/@php\s+\$container0\s*=/');
    }
});

test('folio pages con Component richiedono @volt statico uguale a name()', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $expectations = [
        $themeRoot.'/resources/views/pages/[container0]/index.blade.php' => "@volt('container0.index')",
        $themeRoot.'/resources/views/pages/[container0]/[slug0]/index.blade.php' => "@volt('container0.view')",
        $themeRoot.'/resources/views/pages/[container0]/[slug0]/[container1]/index.blade.php' => "@volt('container1.index')",
        $themeRoot.'/resources/views/pages/tests/[slug].blade.php' => "@volt('tests.view')",
    ];

    foreach ($expectations as $path => $voltDirective) {
        $html = (string) file_get_contents($path);
        expect($html)->toContain($voltDirective);
        expect($html)->toContain('@endvolt');
    }
});

test('container0 index usa mount lineare filament way senza logica dominio', function (): void {
    $path = dirname(__DIR__, 2).'/resources/views/pages/[container0]/index.blade.php';
    $html = (string) file_get_contents($path);

    expect($html)->toContain("name('container0.index')");
    expect($html)->toContain("\$this->pageSlug = \$container0.'.index'");
    expect($html)->not->toContain('resolveHomeTitle');
    expect($html)->not->toContain('CmsPage');
    expect($html)->not->toContain('pageTitle');
    expect($html)->not->toContain('metaDescription');
    expect($html)->not->toContain('supportedLocales');
});

test('folio pages con Component vietano props extends section e php slug hack', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    foreach (glob($themeRoot.'/resources/views/pages/**/*.blade.php') ?: [] as $path) {
        if (str_contains($path, '.old')) {
            continue;
        }
        $html = (string) file_get_contents($path);
        if (! str_contains($html, 'extends Component')) {
            continue;
        }
        expect($html)->not->toContain('@props(');
        expect($html)->not->toContain("@extends('layouts.app')");
        expect($html)->not->toContain("@extends(\"layouts.app\")");
        expect($html)->not->toContain("@section('content')");
        expect($html)->not->toMatch('/@php\s+\$pageSlug\s*=/');
    }
});

test('folio pages non usano @volt con variabile dinamica', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    foreach (glob($themeRoot.'/resources/views/pages/**/*.blade.php') ?: [] as $path) {
        $html = (string) file_get_contents($path);
        if (! str_contains($html, 'extends Component')) {
            continue;
        }
        expect($html)->not->toContain('@volt($');
        expect($html)->not->toContain("@volt('folio.");
    }
});
