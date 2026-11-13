<?php

declare(strict_types=1);

/** @return list<string> */
function folioBladePaths(string $themeRoot): array
{
    $paths = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($themeRoot.'/resources/views/pages', FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && str_ends_with($file->getFilename(), '.blade.php')) {
            $paths[] = $file->getPathname();
        }
    }

    return $paths;
}

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

test('Sixteen usa app come unica shell pubblica e non mantiene il layout design-comuni', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $layoutsRoot = $themeRoot.'/resources/views/components/layouts';
    $dynamicPage = file_get_contents($themeRoot.'/resources/views/pages/[container0]/[slug0]/index.blade.php');

    expect(file_exists($layoutsRoot.'/app.blade.php'))->toBeTrue();
    expect(file_exists($layoutsRoot.'/design-comuni.blade.php'))->toBeFalse();
    expect($dynamicPage)->toContain("'layouts.app'");
    expect($dynamicPage)->not->toContain("'layouts.design-comuni'");
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
    expect($html)->toContain('<x-layouts.app');
    expect($html)->toContain('</x-layouts.app>');
    expect($html)->toContain('<x-page side="content"');
    expect($html)->toContain(':slug="$this->pageSlug"');
    expect($html)->toContain(':data="$this->data"');
    expect($html)->toContain('data-dc');
    expect($html)->toContain('data-slug="{{ $this->pageSlug }}"');
    expect($html)->not->toContain('resolveHomeTitle');
    expect($html)->not->toContain('pageTitle');
    expect($html)->not->toContain('metaDescription');
    expect($html)->not->toContain('supportedLocales');
    expect($html)->not->toContain('nativePage');
    expect($html)->not->toContain('utilityPage');
    expect($html)->not->toContain('x-dynamic-component');
    expect($html)->not->toContain("@include('pub_theme::components.pages.");
    expect($html)->not->toContain("=== 'domande-frequenti'");
    expect($html)->not->toContain("=== 'blog'");
    expect($html)->not->toContain("=== 'events'");
    // Logica alias/404/area-personale fuori dalla shell (Support + Action riusabili).
    expect($html)->toContain('ResolveContainer0IndexAction');
    expect($html)->not->toContain('foIndexAliases');
    expect($html)->not->toContain('Auth::check');
});

test('le pagine CMS container0 non hanno route Folio esplicite concorrenti', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $faqPage = $themeRoot.'/resources/views/pages/domande-frequenti/index.blade.php';

    // FAQ è domande-frequenti.index nel CMS: una view omonima bypasserebbe il resolver JSON.
    expect(file_exists($faqPage))->toBeFalse();
});

test('folio pages con Component vietano props extends section e php slug hack', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    foreach (folioBladePaths($themeRoot) as $path) {
        if (str_contains($path, '.old')) {
            continue;
        }
        $html = (string) file_get_contents($path);
        if (! str_contains($html, 'extends Component')) {
            continue;
        }
        expect($html)->not->toContain('@props(');
        expect($html)->not->toContain("@extends('layouts.app')");
        expect($html)->not->toContain('@extends("layouts.app")');
        expect($html)->not->toContain("@section('content')");
        expect($html)->not->toMatch('/@php\s+\$pageSlug\s*=/');
    }
});

test('folio pages non usano @volt con variabile dinamica', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    foreach (folioBladePaths($themeRoot) as $path) {
        $html = (string) file_get_contents($path);
        if (! str_contains($html, 'extends Component')) {
            continue;
        }
        expect($html)->not->toContain('@volt($');
        expect($html)->not->toContain("@volt('folio.");
    }
});
