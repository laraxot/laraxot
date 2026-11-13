<?php

declare(strict_types=1);

use Dom\HTMLDocument;
use Tests\TestCase;

use function Safe\file_get_contents;

require_once __DIR__.'/Support/css-source.php';

uses(TestCase::class);

/**
 * DCP-291, FO-5-17 (valutazione "Quanto sono chiare le informazioni?"). Markup: vista reale resa da Blade. Stelle: test sul SORGENTE CSS
 * (serve build, la prova e' la misura nel browser: riempimento al passaggio e al clic, frecce da tastiera, un solo nome accessibile).
 */
function renderDcRating(): HTMLDocument
{
    app()->setLocale('it');
    /** @var view-string $viewName */
    $viewName = 'pub_theme::components.blocks.partials.dc-rating';
    $html = view($viewName, ['data' => []])->render();

    return HTMLDocument::createFromString('<body>'.$html.'</body>', LIBXML_NOERROR);
}

test('le stelle stanno nel DOM da 1 a 5, nello stesso ordine in cui si vedono e in cui le frecce le scorrono', function (): void {
    $values = [];
    foreach (renderDcRating()->querySelectorAll('fieldset.rating input[type="radio"]') as $radio) {
        $values[] = $radio->getAttribute('value');
    }

    expect($values)->toBe(['1', '2', '3', '4', '5']);
});

test('ogni stella ha un solo nome accessibile: il testo nascosto, senza svg role img ne aria-labelledby', function (): void {
    $document = renderDcRating();
    $labels = $document->querySelectorAll('fieldset.rating label');

    expect($labels)->toHaveCount(5);
    expect($document->querySelectorAll('fieldset.rating [aria-labelledby]'))->toHaveCount(0);
    expect($document->querySelectorAll('fieldset.rating svg[role]'))->toHaveCount(0);
    expect($document->querySelectorAll('fieldset.rating label svg[aria-hidden="true"]'))->toHaveCount(5);
    foreach ($labels as $label) {
        expect(trim((string) $label->querySelector('.visually-hidden')?->textContent))->not->toBe('');
    }
});

test('il messaggio di ringraziamento e\' annunciato da una regione di stato che resta nel DOM', function (): void {
    $document = renderDcRating();
    $status = $document->querySelector('.cmp-rating [role="status"]');

    expect($status)->not->toBeNull();
    // `.cmp-rating__card-first` e le card dei passi si nascondono con `d-none` (display: none): una regione li' dentro sparisce
    // dall'albero di accessibilita' e il testo che vi compare non viene annunciato.
    $ancestor = $status?->parentElement;
    while ($ancestor !== null) {
        expect((string) $ancestor->getAttribute('class'))->not->toContain('d-none')->not->toContain('cmp-rating__card');
        $ancestor = $ancestor->parentElement;
    }
    expect((string) $status?->getAttribute('x-text'))->toContain('step');
    expect((string) $status?->getAttribute('class'))->toContain('visually-hidden');
});

test('le stelle si dispongono da sinistra a destra e si riempiono fino a quella scelta senza ricorrere all\'ordine inverso del DOM', function (): void {
    $css = file_get_contents(dirname(__DIR__, 2).'/resources/css/app/20-dc-components.css');

    expect(sixteenCssDeclaration($css, 'fieldset.rating.dc-rating-stars', 'flex-direction'))->toBe('row');
    // Con il DOM 1..5 il riempimento "fino a" e' a ritroso: `:has()` sulle stelle successive, non il combinatore `~` delle stelle successive.
    $fills = array_filter(
        sixteenCssRules($css),
        static fn (array $rule): bool => str_contains($rule['selector'], 'dc-rating-stars__item') && isset($rule['declarations']['fill']) && str_contains($rule['selector'], ':has(')
    );
    expect(count($fills))->toBeGreaterThan(0);
    $selectors = implode(' ', array_column($fills, 'selector'));
    expect($selectors)->toContain(':hover')->toContain('input:checked');
});

test('la stella non scelta si distingue dallo sfondo bianco della scheda (1.4.11)', function (): void {
    $css = file_get_contents(dirname(__DIR__, 2).'/resources/css/app/20-dc-components.css');

    $idle = sixteenCssDeclaration($css, 'dc-rating-stars__item svg', 'fill');

    expect($idle)->toMatch('/^#[0-9a-f]{6}$/i');
    expect(sixteenContrastRatio((string) $idle, '#ffffff'))->toBeGreaterThanOrEqual(3.0);
});

test('la scheda di ringraziamento visibile resta quella del riferimento', function (): void {
    expect(renderDcRating()->querySelectorAll('#rating-feedback'))->toHaveCount(1);
});

test('le due varianti usate dalle pagine pubbliche (partial condiviso e copia con la colonna dal JSON) usano le stesse stelle e la stessa regione di stato', function (string $view): void {
    $source = file_get_contents(dirname(__DIR__, 2).'/resources/views/components/blocks/'.$view.'.blade.php');

    expect($source)->toContain('partials.dc-rating-stars')->toContain('role="status"')->not->toContain('<fieldset class="rating">');
})->with(['partials/dc-rating', 'report-partials/rating']);
