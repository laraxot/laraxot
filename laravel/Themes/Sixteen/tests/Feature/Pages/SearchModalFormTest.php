<?php

declare(strict_types=1);

use Dom\HTMLDocument;
use Modules\Fixcity\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\get;

uses(TestCase::class);

/**
 * DCP-288 (FO-1-01): la modale "Cerca" dell'intestazione non cercava. Dal 2026-10-26 invia `q` alla ricerca globale /{lang}/ricerca
 * (Modules/Fixcity/docs/stories/site-wide-front-office-search.story.md), non piu' al solo elenco segnalazioni. Il campo si chiamava `autocomplete-two`, l'elenco legge `q`;
 * il form non aveva `action` (dalla home restava sulla home) e il pulsante "Cerca" era `type="button"`.
 *
 * @return array{action: string, method: string, role: string, input: ?string, hasSubmit: bool, placeholder: string, label: string}
 */
function searchModalForm(string $locale): array
{
    $html = get('/'.$locale)->assertOk()->getContent();
    Assert::assertIsString($html);
    $form = HTMLDocument::createFromString($html, LIBXML_NOERROR)->querySelector('#search-modal form');
    Assert::assertNotNull($form, 'la modale di ricerca ha un form ('.$locale.')');
    $input = $form->querySelector('input[type="search"]');
    Assert::assertNotNull($input, 'il form ha il campo di ricerca ('.$locale.')');

    return [
        'action' => (string) $form->getAttribute('action'),
        'method' => strtolower((string) $form->getAttribute('method')),
        'role' => (string) $form->getAttribute('role'),
        'input' => $input->getAttribute('name'),
        'hasSubmit' => $form->querySelector('button[type="submit"]') !== null,
        'placeholder' => (string) $input->getAttribute('placeholder'),
        'label' => trim((string) $form->querySelector('label[for="'.$input->getAttribute('id').'"]')?->textContent),
    ];
}

it('submits the query as q with a GET to the localized site search page', function (string $locale): void {
    $form = searchModalForm($locale);

    expect($form['method'])->toBe('get')
        ->and($form['role'])->toBe('search')
        ->and($form['input'])->toBe('q')
        ->and($form['hasSubmit'])->toBeTrue()
        ->and($form['action'])->toEndWith('/'.$locale.'/ricerca');
})->with(['it', 'en', 'de', 'es']);

// Il giro completo modale -> /{lang}/ricerca -> segnalazione trovata sta in SiteSearchPageTest: la pagina interroga anche gli
// articoli e lo schema scratch va prima allineato (articles.type), cosa che un file Pest con uses() non puo' fare.

it('promises a whole-site search now that the results page searches the whole site', function (string $locale, string $wholeSiteWording): void {
    $form = searchModalForm($locale);

    expect($form['placeholder'])->toContain($wholeSiteWording)
        ->and($form['label'])->toContain($wholeSiteWording);
})->with([
    'it' => ['it', 'nel sito'],
    'en' => ['en', 'the site'],
    'de' => ['de', 'Website'],
    'es' => ['es', 'en el sitio'],
]);
