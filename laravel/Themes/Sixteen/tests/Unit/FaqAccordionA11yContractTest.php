<?php

declare(strict_types=1);

use Dom\HTMLDocument;
use Tests\TestCase;

use function Safe\file_get_contents;

require_once __DIR__.'/Support/css-source.php';

uses(TestCase::class);

/**
 * DCP-291, FO-5-04 e FO-5-21 (accordion della FAQ). Markup: vista reale resa da Blade. Focus: test sul SORGENTE CSS (serve build,
 * la prova e' la misura nel browser: `box-shadow` calcolato sul pulsante in focus).
 */
function renderFaqAccordion(): HTMLDocument
{
    /** @var view-string $viewName */
    $viewName = 'pub_theme::components.blocks.content-page.sections.accordion';
    $html = view($viewName, [
        'icon' => static fn (string $name): string => '#'.$name,
        'section' => [
            'accordion_id' => 'accordion-faq',
            'filterable' => true,
            'empty' => 'Nessuna domanda corrisponde alla ricerca.',
            'items' => [
                ['id' => 'faq-1', 'question' => 'Prima domanda?', 'answer' => 'Prima risposta.'],
                ['id' => 'faq-2', 'question' => 'Seconda domanda?', 'answer' => 'Seconda risposta.'],
            ],
        ],
    ])->render();

    return HTMLDocument::createFromString('<body>'.$html.'</body>', LIBXML_NOERROR);
}

test('ogni pulsante dell\'accordion sta dentro un titolo h2', function (): void {
    $document = renderFaqAccordion();

    expect($document->querySelectorAll('.accordion-item h2.accordion-header > button.accordion-button'))->toHaveCount(2);
    expect($document->querySelectorAll('div.accordion-header'))->toHaveCount(0);
});

test('il risultato del filtro e\' annunciato in una regione di stato', function (): void {
    $status = renderFaqAccordion()->querySelector('.cmp-accordion [role="status"]');

    expect($status)->not->toBeNull();
    // Il testo si aggiorna con Alpine mentre la regione resta nel DOM: una regione che compare con `x-show` non viene annunciata.
    expect((string) $status?->getAttribute('x-text'))->toContain('hits');
    expect((string) $status?->getAttribute('class'))->toContain('visually-hidden');
});

test('il pulsante dell\'accordion in focus mostra l\'anello nero del resto del sito', function (): void {
    $css = file_get_contents(dirname(__DIR__, 2).'/resources/css/app/20-dc-components.css');

    // `.cmp-accordion.faq .accordion-button { box-shadow: none !important }` (0,5,0) batte `:focus:not(.focus--mouse)` di Bootstrap Italia (0,4,1).
    $shadow = sixteenCssDeclaration($css, '.cmp-accordion.faq .accordion-button:focus', 'box-shadow');

    expect($shadow)->not->toBeNull();
    expect($shadow)->toContain('0 0 0 3px #000')->toContain('!important');
});

test('il messaggio dei risultati del filtro esiste nelle quattro lingue con il segnaposto del conteggio', function (string $locale): void {
    /** @var array{search: array<string, string>} $faq */
    $faq = require dirname(__DIR__, 2).'/lang/'.$locale.'/faq.php';

    expect($faq['search']['results'] ?? '')->toContain(':count');
})->with(['it', 'en', 'de', 'es']);
