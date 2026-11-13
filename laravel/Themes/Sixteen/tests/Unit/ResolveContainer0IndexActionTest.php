<?php

declare(strict_types=1);

use function Safe\file_get_contents;

test('ResolveContainer0IndexAction delega agli Infrastructure riusabili', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $action = file_get_contents($themeRoot.'/app/Actions/Folio/ResolveContainer0IndexAction.php');
    $aliases = file_get_contents($themeRoot.'/app/Infrastructure/Url/FrontofficeContainerAliases.php');
    $folio = file_get_contents($themeRoot.'/resources/views/pages/[container0]/index.blade.php');

    expect($action)->toContain('FrontofficeContainerAliases');
    expect($action)->toContain('FrontofficeRoutes');
    expect($action)->toContain('Infrastructure\\Url\\FrontofficeContainerAliases');
    expect($action)->toContain('Auth::check');
    expect($action)->toContain('abort(404)');
    expect($action)->toContain('area-personale');
    expect($action)->not->toContain("route('tickets.");

    expect($aliases)->toContain("'servizi' => 'services'");
    expect($aliases)->toContain("'segnalazioni' => 'tickets'");
    expect($aliases)->toContain("'faq' => 'domande-frequenti'");

    expect($folio)->toContain('ResolveContainer0IndexAction');
    expect($folio)->not->toContain('foIndexAliases');
    expect($folio)->not->toContain('Auth::check');
});
