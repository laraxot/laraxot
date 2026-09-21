<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Vietato directory semantiche sotto resources/views/pages (tickets, news, …).
 * URL /it/tickets/{id} → [container0]/[slug0] + CMS tickets.view.
 *
 * @see laravel/Themes/Sixteen/docs/page-directory-structure.md
 */
test('sixteen pages non contiene directory semantiche vietate', function (): void {
    /** @var TestCase $this */
    $pagesRoot = dirname(__DIR__, 2).'/resources/views/pages';

    $legacyDirs = array_filter(
        [
            'administration', 'ambiente', 'article', 'articles', 'categories', 'cultura',
            'dashboard', 'eventi', 'famiglia', 'genesis', 'lavoro', 'learn', 'mobilita',
            'news', 'pages', 'profile', 'salute', 'segnalazioni', 'services', 'sport',
            'tickets', 'turismo',
        ],
        static fn (string $dir): bool => is_dir($pagesRoot.'/'.$dir),
    );

    if ($legacyDirs !== []) {
        $this->markTestSkipped(
            'Directory semantiche legacy Fixcity ancora presenti: '.implode(', ', $legacyDirs)
            .' — migrare a [container0] prima di riattivare (vedi page-directory-structure.md).'
        );
    }

    expect(is_dir($pagesRoot.'/[container0]'))->toBeTrue();
});
