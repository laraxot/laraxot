<?php

declare(strict_types=1);

use Tests\TestCase;

use function Pest\Laravel\get;

uses(TestCase::class);

/*
 * `/it/administration` e `/it/news` erano cartelle Folio dedicate (pages/administration, pages/news), rimosse in 5d60a0430f:
 * oggi sono vietate (docs/page-directory-structure.md, NoSemanticFolioPageDirectoriesTest). Le stesse pagine passano ora dal
 * CMS: le novita' sono `/it/blog` (pages/blog/index.json via [container0]) e "Amministrazione" esiste solo come pagina di
 * parita' `/it/tests/amministrazione` (pages/tests/[slug]). Il contratto resta: nessun prop titolo, `<title>` sicuro.
 */
it('renders Folio pages with safe metadata when no title props are supplied', function (string $path): void {
    get($path)
        ->assertOk()
        ->assertSee('<title>', false)
        ->assertDontSee('Undefined variable $title');
})->with([
    'English password reset' => ['/en/auth/password/reset'],
    'Administration parity page' => ['/it/tests/amministrazione'],
    'News CMS page' => ['/it/blog'],
    'Services CMS page' => ['/it/services'],
]);
