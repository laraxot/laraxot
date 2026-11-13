<?php

declare(strict_types=1);

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Tests\TestCase;
use Themes\Sixteen\Infrastructure\Url\FrontofficeUrl;

uses(TestCase::class);

/*
 * fromStoredUrl normalizza i path interni salvati nel CMS (`/it/servizi` -> path nella lingua attiva). Un link esterno
 * (social, portale della regione) o un `tel:`/`mailto:` non e' un path del sito: va restituito com'era, altrimenti
 * `https://www.facebook.com/fixcity` diventa `/it/fixcity` (HeaderTenantFallbackTest).
 */
beforeEach(function (): void {
    LaravelLocalization::setLocale('it');
});

it('keeps external and non-http urls unchanged', function (string $url): void {
    expect(FrontofficeUrl::fromStoredUrl($url))->toBe($url);
})->with([
    'social' => ['https://www.facebook.com/fixcity'],
    'region portal' => ['https://www.regione.toscana.it/'],
    'phone' => ['tel:+39050123456'],
    'mail' => ['mailto:urp@example.test'],
]);

it('still localizes internal cms paths', function (): void {
    expect(FrontofficeUrl::fromStoredUrl('/it/servizi'))
        ->toEndWith('/it/servizi')
        ->and(FrontofficeUrl::fromStoredUrl('/servizi'))->toEndWith('/it/servizi');
});
