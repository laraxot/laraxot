<?php

declare(strict_types=1);

use Tests\TestCase;

uses(TestCase::class);

it('uses a truthful product identity and hides unconfigured tenant links', function (string $locale, string $tagline): void {
    app()->setLocale($locale);
    config()->set('app.name', 'Laravel');
    config()->set('comune.nome', 'Nome Comune');
    config()->set('comune.regione', 'Regione');
    config()->set('comune.regione_url', null);
    config()->set('comune.sottotitolo', null);
    config()->set('comune.social', []);

    $html = view('pub_theme::components.sections.header.v1')->render();

    expect($html)
        ->toContain('FixCity')
        ->toContain($tagline)
        ->not->toContain('Nome Comune')
        ->not->toContain('Nome della Regione')
        ->not->toContain('Region name')
        ->not->toContain('href="#"')
        ->not->toContain('it-socials');
})->with([
    'Italian' => ['it', 'Segnalazioni e servizi della città'],
    'English' => ['en', 'City reports and services'],
]);

it('renders only configured social destinations with safe new-tab attributes', function (): void {
    config()->set('comune.social', [
        'facebook' => 'https://www.facebook.com/fixcity',
        'twitter' => '',
    ]);

    $html = view('pub_theme::components.sections.header.v1')->render();

    expect($html)
        ->toContain('https://www.facebook.com/fixcity')
        ->toContain('rel="noopener noreferrer"')
        ->not->toContain('href="#"')
        ->not->toContain('twitter.label');
});
