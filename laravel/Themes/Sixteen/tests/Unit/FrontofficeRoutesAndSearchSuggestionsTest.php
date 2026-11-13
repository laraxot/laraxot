<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

use function Safe\file_get_contents;

test('FrontofficeRoutes e SearchSuggestions esistono e usano solo container0', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $routes = file_get_contents($themeRoot.'/app/Infrastructure/Url/FrontofficeRoutes.php');
    $suggestions = file_get_contents($themeRoot.'/app/Presentation/Search/FrontofficeSearchSuggestions.php');
    // La migrazione a un solo template per sezione (`<x-section tpl="design-comuni">`) ha sostituito `search-modal.blade.php`
    // con `search-modal/design-comuni.blade.php`; i suggerimenti non vengono piu' da `FrontofficeSearchSuggestions` ma dal CMS.
    $modal = file_get_contents($themeRoot.'/resources/views/components/sections/search-modal/design-comuni.blade.php');
    $services = file_get_contents($themeRoot.'/resources/views/components/pages/services.blade.php');
    $faq = file_get_contents($themeRoot.'/resources/views/components/pages/faq.blade.php');

    expect($routes)->toContain("route('container0.index'");
    expect($routes)->toContain("route('container0.view'");
    expect($routes)->not->toContain("route('tickets.");
    expect($routes)->toContain('namespace Themes\\Sixteen\\Infrastructure\\Url');
    expect($routes)->toContain('personalAreaPratiche');
    expect($routes)->toContain('faqIndex');
    expect($routes)->toContain('ticketsShow');

    expect($suggestions)->toContain('FrontofficeRoutes::ticketsCreate');
    expect($suggestions)->toContain('FrontofficeRoutes::faqIndex');
    expect($suggestions)->toContain('Infrastructure\\Url\\FrontofficeRoutes');
    expect($suggestions)->toContain('pub_theme::navigation.homepage.report_issue');
    expect($suggestions)->toContain('fixcity::ticket_list.heading.title');

    expect($modal)->toContain("\$d('suggestions'");
    expect($modal)->toContain('FrontofficeRoutes');
    expect($modal)->not->toContain("route('tickets.");

    expect($services)->toContain('FrontofficeRoutes::ticketsCreate');
    expect($faq)->toContain('FrontofficeRoutes::ticketsCreate');
    expect($faq)->toContain('pub_theme::faq.meta.title');

    $ticketList = file_get_contents($themeRoot.'/resources/views/components/blocks/ticket-list/design-comuni.blade.php');
    $userDropdown = file_get_contents($themeRoot.'/resources/views/components/sections/header/partials/user-dropdown.blade.php');
    expect($ticketList)->toContain('FrontofficeRoutes::ticketsIndex');
    expect($ticketList)->toContain('FrontofficeRoutes::faqIndex');
    expect($userDropdown)->toContain('FrontofficeRoutes::personalAreaPratiche');
});

test('mobile views usano solo rotte pubbliche canoniche', function (): void {
    $themeRoot = dirname(__DIR__, 2);
<<<<<<< HEAD
    $mobileViews = iterator_to_array((new Finder)->files()->name('*.blade.php')->in($themeRoot.'/resources/views/mobile'), false);

    expect($mobileViews)->not->toBeEmpty();

    foreach ($mobileViews as $view) {
        $html = file_get_contents($view->getPathname());
=======
    $mobileViews = glob($themeRoot.'/resources/views/mobile/**/*.blade.php') ?: [];
    $mobileViews = array_merge($mobileViews, glob($themeRoot.'/resources/views/mobile/*.blade.php') ?: []);

    expect($mobileViews)->not->toBeEmpty();

    foreach (array_unique($mobileViews) as $path) {
        $html = file_get_contents($path);
>>>>>>> f08c6884 (.)
        expect($html)->not->toContain('/mobile');
        expect($html)->not->toContain("route('area-personale.");
        expect($html)->not->toContain('two::');
    }
});
