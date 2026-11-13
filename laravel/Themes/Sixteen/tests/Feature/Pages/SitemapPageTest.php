<?php

declare(strict_types=1);

use Tests\TestCase;
use Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes;

uses(TestCase::class);

/*
 * `/{lang}/mappa-sito` (front-office completeness audit, item 3): a breadcrumb like every other page, and the
 * Novita, Eventi, Servizi and Categorie sections the header links to, built with the same URL helpers.
 */
it('shows a breadcrumb and links every main section of the site', function (): void {
    /** @var TestCase $this */
    $response = $this->get('/it/mappa-sito')->assertOk();

    $response->assertSee('data-element="breadcrumb"', false);
    foreach ([
        FrontofficeRoutes::blogIndex(),
        FrontofficeRoutes::eventsIndex(),
        FrontofficeRoutes::servicesIndex(),
        FrontofficeRoutes::index('lista-categorie'),
    ] as $url) {
        $response->assertSee('href="'.$url.'"', false);
    }
});
