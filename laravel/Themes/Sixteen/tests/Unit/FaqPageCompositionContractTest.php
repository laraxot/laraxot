<?php

declare(strict_types=1);

/**
 * La FAQ non e' una pagina del tema: `domande-frequenti/index.json` compone i blocchi condivisi `content-page`
 * (breadcrumb, hero, sezioni search + accordion, rating, contatti) e le domande arrivano da ListPublishedFaqsAction.
 * Niente blocco monolitico, niente `parity_page` (aggancia CSS di pagina), niente CSS o JS che nomini la pagina:
 * il riuso sta nel componente `cmp-*`, mai in un selettore di pagina.
 *
 * @see laravel/Themes/Sixteen/docs/bmad/faq-route-ownership.md
 */
$faqJson = dirname(__DIR__, 4).'/config/local/fixcity/database/content/pages/domande-frequenti/index.json';

test('la FAQ compone i blocchi condivisi del CMS in ogni lingua', function () use ($faqJson): void {
    /** @var array{content_blocks: array<string, list<array<string, mixed>>>} $json */
    $json = json_decode((string) file_get_contents($faqJson), true, 512, JSON_THROW_ON_ERROR);

    foreach (['it', 'en', 'de', 'es'] as $locale) {
        $block = $json['content_blocks'][$locale][0] ?? [];
        $data = $block['data'] ?? [];

        expect($block['type'] ?? null)->toBe('content-page');
        expect($data['view'] ?? null)->toBe('pub_theme::components.blocks.content-page.design-comuni');
        expect($data)->not->toHaveKey('parity_page');
        expect(array_column($data['sections'] ?? [], 'type'))->toBe(['search', 'accordion']);

        [$search, $accordion] = $data['sections'];

        // Le domande vengono dall'Action del dominio, non dal JSON; il campo di ricerca filtra proprio quell'elenco.
        expect($accordion['source'] ?? null)->toBe('faq');
        expect($accordion)->not->toHaveKey('items');
        expect($search['search']['filter'] ?? null)->toBe($accordion['accordion_id'] ?? '?');

        // Le etichette stanno nel JSON della lingua, non in una view del tema.
        expect($data['feedback']['title'] ?? '')->not->toBe('');
        expect($data['contacts']['main']['items'] ?? [])->not->toBe([]);
    }
})->skip(! is_file($faqJson), 'JSON del tenant local/fixcity assente');

test('nessun CSS o JS del tema nomina la pagina FAQ', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $offenders = [];

    foreach (['css', 'js'] as $dir) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($themeRoot.'/resources/'.$dir, FilesystemIterator::SKIP_DOTS),
        );

        foreach ($files as $file) {
            if (! in_array($file->getExtension(), ['css', 'js'], true)) {
                continue;
            }

            $source = (string) file_get_contents($file->getPathname());
            if (preg_match('/data-page=["\']domande-frequenti|dc-faq-parity|dc-faq-(hero|breadcrumb|search)/', $source) === 1) {
                $offenders[] = substr($file->getPathname(), strlen($themeRoot) + 1);
            }
        }
    }

    expect($offenders)->toBe([]);
});
