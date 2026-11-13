<?php

declare(strict_types=1);

/**
 * DCP-198 A7: la mappa del sito non deve elencare path demo Design Comuni inesistenti.
 */
it('lists only FixCity public paths in every locale sitemap', function (): void {
    $forbidden = [
        '/services/report-issue',
        '/services',
        '/administration',
        '/news',
    ];
    $required = [
        '/tickets/create',
        '/tickets',
        '/tickets/track',
        '/domande-frequenti',
        '/privacy',
        '/note-legali',
        '/cookie-policy',
        '/accessibilita',
        '/auth/login',
    ];

    foreach (['it', 'en', 'de', 'es'] as $locale) {
        /** @var array{groups: list<array{links: list<array{path: string}>}>} $sitemap */
        $sitemap = require dirname(__DIR__, 2).'/lang/'.$locale.'/sitemap.php';
        $paths = [];
        foreach ($sitemap['groups'] as $group) {
            foreach ($group['links'] as $link) {
                $paths[] = $link['path'];
            }
        }

        foreach ($forbidden as $path) {
            expect($paths)->not->toContain($path);
        }
        foreach ($required as $path) {
            expect($paths)->toContain($path);
        }
    }
});
