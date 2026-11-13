<?php

declare(strict_types=1);

use Themes\Sixteen\Infrastructure\Url\FrontofficeContainerAliases;

require_once dirname(__DIR__, 2).'/app/Infrastructure/Url/FrontofficeContainerAliases.php';

test('alias Design Comuni puntano ai container canonici', function (): void {
    expect(FrontofficeContainerAliases::canonical('servizi'))->toBe('services');
    expect(FrontofficeContainerAliases::canonical('eventi'))->toBe('events');
    expect(FrontofficeContainerAliases::canonical('novita'))->toBe('blog');
    expect(FrontofficeContainerAliases::canonical('faq'))->toBe('domande-frequenti');
    expect(FrontofficeContainerAliases::canonical('segnalazioni'))->toBe('tickets');
});

test('container canonici non sono alias', function (): void {
    expect(FrontofficeContainerAliases::canonical('tickets'))->toBeNull();
    expect(FrontofficeContainerAliases::canonical('services'))->toBeNull();
    expect(FrontofficeContainerAliases::canonical('domande-frequenti'))->toBeNull();
});

test('mappa alias e unica e tipizzata stringa a stringa', function (): void {
    $map = FrontofficeContainerAliases::map();

    expect($map)->toBeArray();
    expect($map)->not->toBeEmpty();
    foreach ($map as $from => $to) {
        expect($from)->toBeString();
        expect($to)->toBeString();
        expect($from)->not->toBe($to);
    }
});
