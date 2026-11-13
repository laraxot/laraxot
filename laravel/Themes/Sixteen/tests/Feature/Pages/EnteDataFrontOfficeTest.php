<?php

declare(strict_types=1);

use Dom\Element;
use Dom\HTMLDocument;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

use function Safe\preg_match_all;
use function Safe\preg_replace;

uses(TestCase::class);

/*
 * DCP-289 (FO-1-02/03/04/23/27, FO-4-05/07/23/27, FO-6-02, FO-2-29): i dati dell'ente hanno una sola sorgente (`config('ente')`) e un blocco
 * che non ha il suo dato SPARISCE: niente nome di regione di prova (unica eccezione D35: "Demo" nella fascia slim), niente numero verde finto, niente "Seguici su" vuoto,
 * niente riquadro contatti vuoto, niente `href="#"`. Con i dati compilati (valori sintetici: domini `.test`, numeri `000`) i campi compaiono,
 * i telefoni diventano `tel:` validi e nessun valore finisce nella pagina senza escape.
 */
const DCP289_LOCALES = ['it', 'en', 'de', 'es'];

const DCP289_FILLED = [
    'name' => 'Comune di Prova',
    'region_name' => 'Regione di Prova',
    'region_url' => 'https://regione.example.test/portale',
    'address' => "Via di Prova 1\n00000 Provaville",
    'hours' => 'Lun-Ven 9-12',
    'phone' => '+39 000 0000000',
    'email' => 'urp@ente.example.test',
    'pec' => 'ente@pec.example.test',
    'tax_code' => '00000000000',
    'vat' => '00000000001',
    'toll_free' => '800 000 000',
    'dpo_name' => 'DPO di Prova',
    'dpo_email' => 'dpo@ente.example.test',
    'social' => [
        ['network' => 'facebook', 'url' => 'https://social.example.test/ente'],
        ['network' => 'youtube', 'url' => 'https://social.example.test/video', 'label' => 'Canale di Prova'],
    ],
];

/** Intestazioni che il footer mostra sopra i recapiti dell'ente, nelle quattro lingue. */
const DCP289_CONTACT_HEADINGS = ['Contatti', 'Contacts', 'Kontakt', 'Contacto'];

/** Testo che introduce i social nell'intestazione, nelle quattro lingue (header.json, `follow_us`). */
const DCP289_FOLLOW_US = ['Seguici su', 'Follow us on', 'Folgen Sie uns auf', 'Síguenos en'];

/**
 * @param  array<string, mixed>  $ente
 * @return array{0: string, 1: HTMLDocument}
 */
function dcp289Page(TestCase $test, string $locale, string $path, array $ente): array
{
    config(['ente' => $ente]);
    $html = $test->get('/'.$locale.$path)->assertOk()->getContent();
    Assert::assertIsString($html);

    return [$html, HTMLDocument::createFromString($html, LIBXML_NOERROR)];
}

function dcp289Text(?Element $node): string
{
    return $node === null ? '' : trim(preg_replace('/\s+/u', ' ', $node->textContent ?? ''));
}

/**
 * @return list<string>
 */
function dcp289Hrefs(HTMLDocument $document, string $selector): array
{
    $hrefs = [];
    foreach ($document->querySelectorAll($selector) as $anchor) {
        $hrefs[] = (string) $anchor->getAttribute('href');
    }

    return $hrefs;
}

/**
 * @return list<string>
 */
function dcp289FooterHeadings(HTMLDocument $document): array
{
    $headings = [];
    foreach ($document->querySelectorAll('footer h2, footer h3, footer h4') as $heading) {
        $headings[] = dcp289Text($heading);
    }

    return $headings;
}

dataset('dcp289 pages', ['', '/tickets', '/blog', '/events', '/tickets/confirmation', '/privacy']);
dataset('dcp289 locales', DCP289_LOCALES);

it('shows no placeholder and no empty block when the ente gave no data', function (string $locale, string $path): void {
    /** @var TestCase $this */
    [$html, $document] = dcp289Page($this, $locale, $path, ['social' => []]);

    foreach (['05 0505', '050505', 'Name of the Region', 'Name der Region', 'Nombre de la Región', 'Nome della Regione'] as $placeholder) {
        expect($html)->not->toContain($placeholder);
    }
    // D35: unico valore dimostrativo ammesso: "Demo" (header.json `region.name`), una sola volta, come testo (non link) nella fascia slim.
    expect(preg_match_all('/\bDemo\b/u', $html))->toBe(1)
        ->and(dcp289Text($document->querySelector('header .it-header-slim-wrapper-content > span.navbar-brand')))->toBe('Demo');
    expect(dcp289Hrefs($document, 'a[href="#"]'))->toBe([]);

    // senza dato non resta nessun "Seguici su", nessun link di regione, nessuna navigazione vuota
    $header = dcp289Text($document->querySelector('header'));
    foreach (DCP289_FOLLOW_US as $followUs) {
        expect($header)->not->toContain($followUs);
    }
    expect(dcp289Hrefs($document, 'header a[target="_blank"]'))->toBe([]);
    foreach ($document->querySelectorAll('header nav') as $navigation) {
        expect($navigation->querySelectorAll('a')->length)->toBeGreaterThan(0);
    }
    foreach ($document->querySelectorAll('.cmp-contacts') as $box) {
        expect($box->querySelectorAll('li a')->length)->toBeGreaterThan(0);
    }
    expect(array_intersect(dcp289FooterHeadings($document), DCP289_CONTACT_HEADINGS))->toBe([]);
})->with('dcp289 locales')->with('dcp289 pages');

it('hides the toll-free number and the contact tel link on the help boxes without data', function (string $locale, string $path): void {
    /** @var TestCase $this */
    [, $document] = dcp289Page($this, $locale, $path, ['social' => []]);

    expect(dcp289Hrefs($document, 'a[href^="tel:"]'))->toBe([]);
})->with('dcp289 locales')->with(['/tickets', '/blog', '/events']);

it('shows the ente data from the single source: region, social, footer contacts', function (string $locale): void {
    /** @var TestCase $this */
    [, $document] = dcp289Page($this, $locale, '', DCP289_FILLED);

    $region = $document->querySelector('header a[href="https://regione.example.test/portale"]');
    expect($region)->not->toBeNull()
        ->and(dcp289Text($region))->toBe('Regione di Prova');

    $expectedSocial = ['https://social.example.test/ente', 'https://social.example.test/video'];
    expect(array_values(array_unique(dcp289Hrefs($document, 'header a[href^="https://social.example.test/"]'))))->toBe($expectedSocial)
        ->and(array_values(array_unique(dcp289Hrefs($document, 'footer a[href^="https://social.example.test/"]'))))->toBe($expectedSocial);

    $footer = dcp289Text($document->querySelector('footer'));
    expect($footer)->toContain('Via di Prova 1')
        ->and($footer)->toContain('00000 Provaville')
        ->and($footer)->toContain('Lun-Ven 9-12')
        ->and($footer)->toContain('00000000000')
        ->and($footer)->toContain('urp@ente.example.test')
        ->and($footer)->toContain('ente@pec.example.test')
        ->and($footer)->toContain('DPO di Prova')
        ->and($footer)->toContain('dpo@ente.example.test');
    expect(dcp289Hrefs($document, 'footer a[href^="tel:"], footer a[href^="mailto:"]'))->toContain('tel:+390000000000')
        ->toContain('tel:800000000')
        ->toContain('mailto:urp@ente.example.test')
        ->toContain('mailto:ente@pec.example.test')
        ->toContain('mailto:dpo@ente.example.test');

    expect(array_intersect(dcp289FooterHeadings($document), DCP289_CONTACT_HEADINGS))->not->toBe([]);
})->with('dcp289 locales');

it('turns the toll-free number into a valid tel link on the help boxes', function (string $locale, string $path): void {
    /** @var TestCase $this */
    [, $document] = dcp289Page($this, $locale, $path, DCP289_FILLED);

    $links = [];
    foreach ($document->querySelectorAll('.cmp-contacts a[href^="tel:"]') as $anchor) {
        $links[(string) $anchor->getAttribute('href')] = dcp289Text($anchor);
    }

    expect($links)->toHaveKey('tel:800000000')
        ->and($links['tel:800000000'])->toContain('800 000 000');
})->with('dcp289 locales')->with(['/tickets']);

it('turns the toll-free number into a valid tel link on blog and events', function (string $path): void {
    /** @var TestCase $this */
    [, $document] = dcp289Page($this, 'it', $path, DCP289_FILLED);

    expect(dcp289Hrefs($document, '.cmp-contacts a[href^="tel:"]'))->toBe(['tel:800000000']);
})->with(['/blog', '/events']);

it('escapes every value of the ente and discards a url that is not http or https', function (string $locale): void {
    /** @var TestCase $this */
    $script = '<script>alert(1)</script>';
    $breakout = '"><img src=x onerror=alert(1)>';
    [$html, $document] = dcp289Page($this, $locale, '', [
        'region_name' => $script,
        'region_url' => 'javascript:alert(1)',
        'address' => $breakout,
        'hours' => $script,
        'phone' => 'tel:abc',
        'social' => [
            ['network' => 'facebook', 'url' => 'javascript:alert(1)'],
            ['network' => 'youtube', 'url' => 'https://social.example.test/video', 'label' => $breakout],
        ],
    ]);

    expect($html)->not->toContain($script)
        ->and($html)->not->toContain('<img src=x onerror')
        ->and($html)->toContain(e($script))
        ->and($html)->not->toContain('javascript:alert')
        ->and(dcp289Hrefs($document, 'a[href^="tel:"]'))->toBe([])
        ->and($document->querySelectorAll('img[src="x"]')->length)->toBe(0);
    // la regione senza url valido resta un testo, non un link
    expect(dcp289Text($document->querySelector('header')))->toContain($script);
    foreach ($document->querySelectorAll('header a') as $anchor) {
        expect(dcp289Text($anchor))->not->toBe($script);
    }
})->with('dcp289 locales');
