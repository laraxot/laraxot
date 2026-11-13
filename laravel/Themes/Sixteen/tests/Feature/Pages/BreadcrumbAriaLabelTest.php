<?php

declare(strict_types=1);

use Dom\HTMLDocument;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

use function Pest\Laravel\get;

uses(TestCase::class);

/**
 * DCP-288 (FO-1-28, FO-6-12): le briciole di pane avevano `aria-label="breadcrumb"` fisso in inglese, anche in italiano, tedesco e spagnolo.
 * Il lettore di schermo annuncia quell'etichetta come nome della regione di navigazione: va nella lingua della pagina.
 */
it('names the breadcrumb navigation in the language of the page', function (string $locale, string $path, string $label): void {
    $html = get('/'.$locale.'/'.$path)->assertOk()->getContent();
    Assert::assertIsString($html);
    $nav = HTMLDocument::createFromString($html, LIBXML_NOERROR)->querySelector('.cmp-breadcrumbs nav');

    Assert::assertNotNull($nav, 'la pagina ha le briciole di pane');
    expect($nav->getAttribute('aria-label'))->toBe($label)
        ->and($label)->toBe(__('fixcity::global.breadcrumb', [], $locale));
})->with(function (): Generator {
    $labels = ['it' => 'Percorso di navigazione', 'en' => 'Breadcrumb', 'de' => 'Navigationspfad', 'es' => 'Ruta de navegación'];
    $pages = ['accessibilita', 'cookie-policy', 'domande-frequenti', 'media-policy', 'note-legali', 'privacy', 'terms', 'blog', 'events', 'lista-categorie'];

    foreach ($labels as $locale => $label) {
        foreach ($pages as $page) {
            yield $locale.' '.$page => [$locale, $page, $label];
        }
    }
});

it('draws the categories breadcrumb with the Design Comuni markup, not with utility classes that stack the items', function (): void {
    $html = get('/it/lista-categorie')->assertOk()->getContent();
    Assert::assertIsString($html);
    $items = HTMLDocument::createFromString($html, LIBXML_NOERROR)->querySelectorAll('.cmp-breadcrumbs ol.breadcrumb > li.breadcrumb-item');

    Assert::assertCount(2, $items);
    Assert::assertSame('Home', $items->item(0)?->querySelector('a')?->textContent);
    Assert::assertSame('page', $items->item(1)?->getAttribute('aria-current'));
});
