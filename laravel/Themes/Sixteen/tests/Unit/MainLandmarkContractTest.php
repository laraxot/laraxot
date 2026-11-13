<?php

declare(strict_types=1);

use Dom\HTMLDocument;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

use function Safe\file_get_contents;

uses(TestCase::class);

/**
 * DCP-291, FO-5-06. Un solo `main` per pagina e un solo bersaglio dello skip link ("Vai ai contenuti" -> #main-container):
 * focalizzabile (tabindex -1) e con id unico. Molti blocchi del riferimento Design Comuni portano gia' `div.container#main-container`
 * dentro `<main>`, e il CSS del tema usa quell'id (`main #main-container`): il layout non puo' toglierlo ai blocchi, quindi
 * `<main>` cede l'id al blocco quando c'e', e lo tiene quando manca (pagine di traccia: skip link che puntava al nulla).
 */
function renderMainLandmark(string $slot): HTMLDocument
{
    $html = Blade::render('<x-pub_theme::layouts.main-landmark body-page="prova">'.$slot.'</x-pub_theme::layouts.main-landmark>');

    return HTMLDocument::createFromString('<body>'.$html.'</body>', LIBXML_NOERROR);
}

test('senza bersaglio nel contenuto, main e\' il bersaglio dello skip link', function (): void {
    $document = renderMainLandmark('<div class="dc-ticket-track">Traccia</div>');

    $target = $document->querySelector('#main-container');
    expect($document->querySelectorAll('#main-container'))->toHaveCount(1);
    expect($target?->nodeName)->toBe('MAIN');
    expect($target?->getAttribute('tabindex'))->toBe('-1');
    expect($document->querySelectorAll('main'))->toHaveCount(1);
    expect($target?->getAttribute('data-page'))->toBe('prova');
});

test('con il bersaglio nel contenuto, l\'id resta unico e il bersaglio e\' focalizzabile', function (): void {
    $document = renderMainLandmark('<div class="container" id="main-container"><h1>Titolo</h1></div>');

    $target = $document->querySelector('#main-container');
    expect($document->querySelectorAll('#main-container'))->toHaveCount(1);
    expect($target?->nodeName)->toBe('DIV');
    expect($target?->getAttribute('tabindex'))->toBe('-1');
    expect($document->querySelectorAll('main'))->toHaveCount(1);
    expect($document->querySelector('main')?->hasAttribute('id'))->toBeFalse();
});

test('l\'id su una riga a parte e un tabindex gia presente non vengono duplicati', function (): void {
    $document = renderMainLandmark("<section\n  class=\"x\"\n  id=\"main-container\"\n  tabindex=\"0\">Ciao</section>");

    $target = $document->querySelector('#main-container');
    expect($target?->getAttribute('tabindex'))->toBe('0');
    expect(substr_count((string) $document->body?->innerHTML, 'tabindex'))->toBe(1);
});

test('il layout pubblico canonico usa il componente e non emette piu un main con id a mano', function (): void {
    $layout = 'app';
    $source = file_get_contents(dirname(__DIR__, 2).'/resources/views/components/layouts/'.$layout.'.blade.php');

    expect($source)->toContain('<x-layouts.main-landmark')->not->toMatch('/<main[^>]*id="main-container"/');
});

test('le pagine d\'errore a tema non annidano un secondo main dentro quello del layout', function (string $view): void {
    $source = file_get_contents(dirname(__DIR__, 4).'/Modules/Fixcity/resources/views/errors/'.$view.'.blade.php');

    expect($source)->not->toContain('<main');
})->with(['not-found', 'too-many-requests']);
