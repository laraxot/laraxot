<?php

declare(strict_types=1);

use Dom\Element;
use Dom\HTMLDocument;
use Illuminate\Support\Facades\Blade;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

uses(TestCase::class);

/*
 * Story header-footer-design-comuni-markup (footer): `sections/footer/v1.blade.php` rende la struttura del footer del riferimento
 * Design Comuni (it-footer > it-footer-main > it-footer-small-prints), con i dati di footer.json e dell'ente (DCP-289).
 * Mai `href="#"`, mai un `<a>` senza destinazione; i social si aprono in una nuova scheda con `rel="noopener noreferrer"`.
 */
function footerV1Element(string $locale): Element
{
    // stessa chiamata del layout (components/layouts/app.blade.php), senza dipendere dal resto della pagina
    LaravelLocalization::setLocale($locale);
    app()->setLocale($locale);
    $html = Blade::render('<x-section slug="footer" tpl="v1" />');
    $footer = HTMLDocument::createFromString($html, LIBXML_NOERROR)->querySelector('footer#footer');
    Assert::assertNotNull($footer, 'Footer assente.');

    return $footer;
}

it('renders the Design Comuni footer structure', function (string $locale): void {
    config(['ente' => ['social' => []]]);
    $footer = footerV1Element($locale);

    expect($footer->getAttribute('class'))->toBe('it-footer')
        ->and($footer->querySelector('.it-footer-main > .container > .row'))->not->toBeNull()
        ->and($footer->querySelector('.it-footer-main')?->parentElement)->toBe($footer)
        ->and($footer->querySelector('.it-footer-small-prints')?->parentElement)->toBe($footer)
        ->and($footer->querySelector('.it-footer-small-prints .it-footer-small-prints-list li'))->not->toBeNull()
        ->and($footer->querySelector('.logo-wrapper .it-brand-wrapper a svg.fc-brand-mark'))->not->toBeNull()
        ->and(trim((string) $footer->querySelector('.it-brand-text h2.no_toc')?->textContent))->toBe('FixCity')
        ->and($footer->querySelectorAll('.footer-items-wrapper h4.footer-heading-title')->length)->toBeGreaterThan(1)
        ->and($footer->querySelectorAll('ul.footer-list li a')->length)->toBeGreaterThan(3)
        ->and($footer->querySelectorAll('.footer-bottom a')->length)->toBeGreaterThan(1)
        ->and($footer->querySelector('[data-element="faq"]'))->not->toBeNull()
        ->and($footer->querySelector('[data-element="report-inefficiency"]'))->not->toBeNull()
        ->and($footer->querySelector('[data-element="cookie-preferences"]'))->not->toBeNull()
        ->and($footer->querySelector('a[data-element="privacy-policy-link"][href$="/'.$locale.'/privacy"]'))->not->toBeNull()
        ->and($footer->querySelector('a[data-element="legal-notes"]'))->not->toBeNull()
        ->and($footer->querySelector('a[data-element="accessibility-link"]'))->not->toBeNull()
        // link interni con il prefisso della lingua (FrontofficeUrl)
        ->and($footer->querySelector('ul.footer-list a[href$="/'.$locale.'/tickets"]'))->not->toBeNull()
        // senza social dell'ente la colonna "Seguici su" non c'e'
        ->and($footer->querySelectorAll('ul.social')->length)->toBe(0)
        ->and($footer->querySelectorAll('a[href="#"]')->length)->toBe(0)
        ->and($footer->querySelectorAll('a:not([href])')->length)->toBe(0);
})->with(['it', 'en']);

it('opens the ente socials in a new tab with noopener noreferrer', function (string $locale): void {
    config(['ente' => ['social' => [['network' => 'facebook', 'url' => 'https://social.example.test/ente']]]]);
    $footer = footerV1Element($locale);

    $link = $footer->querySelector('.footer-items-wrapper ul.list-inline.social li.list-inline-item a');
    expect($link)->not->toBeNull()
        ->and($link?->getAttribute('href'))->toBe('https://social.example.test/ente')
        ->and($link?->getAttribute('target'))->toBe('_blank')
        ->and($link?->getAttribute('rel'))->toBe('noopener noreferrer')
        ->and(trim((string) $link?->querySelector('.visually-hidden')?->textContent))->not->toBe('');
})->with(['it', 'en']);

/*
 * Collisione footer v1 (2026-10-26): il ramo Design Comuni leggeva le etichette dei recapiti da `pub_theme::footer.*`, che esiste solo in
 * italiano (en/de/es: chiave grezza o italiano per fallback) e non copre orari, numero verde e DPO. Le etichette vengono da
 * `footer.json` -> `contact.labels`, gia' tradotto nelle quattro lingue.
 */
it('labels the ente contact rows in the language of the page', function (string $locale, array $labels, string $italianLeak): void {
    config(['ente' => ['hours' => 'Lun-Ven 9-12', 'phone' => '+39 000 0000000', 'toll_free' => '800 000 000']]);
    $footer = trim((string) preg_replace('/\s+/u', ' ', footerV1Element($locale)->textContent));

    expect($footer)->not->toContain('pub_theme::footer.');
    foreach ($labels as $label) {
        expect($footer)->toContain($label.':');
    }
    if ($italianLeak !== '') {
        expect($footer)->not->toContain($italianLeak);
    }
})->with([
    'it' => ['it', ['Orari', 'Telefono', 'Numero verde'], ''],
    'en' => ['en', ['Opening hours', 'Phone', 'Toll-free number'], 'Telefono'],
    'de' => ['de', ['Öffnungszeiten', 'Telefon', 'Gebührenfreie Nummer'], 'Telefono'],
    'es' => ['es', ['Horario', 'Teléfono', 'Número gratuito'], 'Telefono'],
]);
