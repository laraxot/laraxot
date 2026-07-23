<?php

declare(strict_types=1);

/**
 * Vietato directory semantiche sotto resources/views/pages (tickets, news, …).
 * URL /it/tickets/{id} → [container0]/[slug0] + CMS tickets.view.
 *
 * @see laravel/Themes/Sixteen/docs/page-directory-structure.md
 */
test('sixteen pages non contiene directory semantiche vietate', function (): void {
    $pagesRoot = dirname(__DIR__, 2).'/resources/views/pages';

    $forbidden = [
        'administration', 'ambiente', 'article', 'articles', 'categories', 'cultura',
        'dashboard', 'eventi', 'famiglia', 'genesis', 'lavoro', 'learn', 'mobilita',
        'news', 'pages', 'profile', 'salute', 'segnalazioni', 'services', 'sport',
        'tickets', 'turismo',
    ];

    foreach ($forbidden as $dir) {
        expect(is_dir($pagesRoot.'/'.$dir))->toBeFalse("Forbidden Folio dir: pages/{$dir}");
    }

    expect(is_dir($pagesRoot.'/[container0]'))->toBeTrue();
});
