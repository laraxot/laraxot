<?php

declare(strict_types=1);

use function Safe\file_get_contents;

test('homepage uses the Sixteen CMS page contract without legacy test slugs', function (): void {
    $path = dirname(__DIR__, 2).'/resources/views/pages/index.blade.php';
    $html = file_get_contents($path);

    expect($html)
        ->toContain("name('home')")
        ->toContain('body-page="homepage"')
        ->toContain('<x-page side="content"')
        ->not->toContain("'tests'")
        ->not->toContain('<style>');
});

test('homepage step icons keep a bounded reusable size', function (): void {
    $path = dirname(__DIR__, 2).'/resources/css/app/16-homepage-civic-reframe.css';
    $css = file_get_contents($path);

    expect($css)
        ->toContain('.fc-how-card__icon svg')
        ->toContain('max-width: none;')
        ->toContain('width: 3.5rem;')
        ->toContain('height: 3.5rem;');
});

test('homepage hero copy uses the shared centered content grid', function (): void {
    $path = dirname(__DIR__, 2).'/resources/views/components/blocks/home/hero.blade.php';
    $view = file_get_contents($path);

    expect($view)->toContain('fc-home-hero__inner container');
});

test('homepage quick actions keep icons and buttons readable', function (): void {
    $path = dirname(__DIR__, 2).'/resources/css/app/16-homepage-civic-reframe.css';
    $css = file_get_contents($path);

    expect($css)
        ->toContain('.quick-action-btn > svg')
        ->toContain('flex-direction: column;')
        ->toContain('white-space: normal;');
});

test('homepage tracking actions wrap without obscuring their labels', function (): void {
    $path = dirname(__DIR__, 2).'/resources/css/app/16-homepage-civic-reframe.css';
    $css = file_get_contents($path);

    expect($css)
        ->toContain('.fc-track-section__actions > div')
        ->toContain('flex-wrap: wrap;')
        ->toContain('@media (max-width: 1023.98px)')
        ->toContain('.quick-action-btn');
});
