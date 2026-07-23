<?php

declare(strict_types=1);

/**
 * Contratto pagine Folio dinamiche TwentyOne: mount() + @volt statico = name().
 */
test('container0 pages usano mount lineare e name filament way', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $indexPath = $themeRoot.'/resources/views/pages/[container0]/index.blade.php';
    $html = (string) file_get_contents($indexPath);

    expect($html)->toContain("name('container0.index')");
    expect($html)->toContain("@volt('container0.index')");
    expect($html)->toContain("\$this->pageSlug = \$container0.'.index'");
    expect($html)->not->toContain('container0.list');
    expect($html)->not->toContain('CmsPage');
    expect($html)->not->toContain('resolveHomeTitle');
});

test('container0 slug0 usa container0.view e ResolvePageAction', function (): void {
    $path = dirname(__DIR__, 2).'/resources/views/pages/[container0]/[slug0]/index.blade.php';
    $html = (string) file_get_contents($path);

    expect($html)->toContain("name('container0.view')");
    expect($html)->toContain("@volt('container0.view')");
    expect($html)->toContain('ResolvePageAction');
    expect($html)->not->toContain('container0.detail');
    expect($html)->not->toContain('container0.list');
});
