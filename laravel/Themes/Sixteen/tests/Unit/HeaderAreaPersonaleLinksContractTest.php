<?php

declare(strict_types=1);

use function Safe\file_get_contents;

/**
 * Contratto header area personale: Folio + FrontofficeRoutes, no wrapper path custom.
 * Source-level (no TestCase): il bootstrap Laravel non e' richiesto per queste guardie.
 */
test('Frontoffice URL helpers esistono per nav CMS', function (): void {
    $themeRoot = dirname(__DIR__, 2);

    expect(file_exists($themeRoot.'/app/Actions/Url/BuildLocalizedFrontofficePathAction.php'))->toBeTrue();
    expect(file_exists($themeRoot.'/app/Actions/Url/NormalizeStoredFrontofficeUrlAction.php'))->toBeTrue();
    expect(file_exists($themeRoot.'/app/Infrastructure/Url/FrontofficeRoutes.php'))->toBeTrue();
});

test('user-dropdown usa FrontofficeRoutes area personale e route Folio', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $html = file_get_contents($themeRoot.'/resources/views/components/sections/header/partials/user-dropdown.blade.php');

    expect($html)->toContain("route('services.categories')");
    expect($html)->toContain('FrontofficeRoutes::personalAreaPratiche');
    expect($html)->toContain('FrontofficeRoutes::personalAreaNotifiche');
    expect($html)->toContain('FrontofficeRoutes::personalAreaImpostazioni');
    expect($html)->toContain("route('logout')");
    expect($html)->not->toContain('FrontofficeUrl::personalArea');
    expect($html)->not->toContain("route('tests.view'");
    expect($html)->not->toContain("route('user.services'");
    expect($html)->not->toContain("route('area-personale");
    expect($html)->not->toContain('area-personale.notifiche');
    expect($html)->not->toContain("route('profile.edit')");
    expect($html)->not->toContain('ui::ui.profile');
    expect($html)->toContain('pub_theme::header.user.dropdown.notifications.label');
});

test('guest CTA header usa route login Folio', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $paths = [
        $themeRoot.'/resources/views/components/sections/header/partials/personal-area-guest-cta.blade.php',
        $themeRoot.'/resources/views/components/sections/header/partials/personal-area-login-cta.blade.php',
    ];

    foreach ($paths as $path) {
        if (! file_exists($path)) {
            continue;
        }
        $html = file_get_contents($path);
        expect($html)->toContain("route('login')");
        expect($html)->not->toContain('FrontofficeUrl::login()');
    }
});

test('header area-personale partials non contengono locale hardcoded ne FrontofficeUrl personalArea', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $areaPersonalePartials = [
        'user-dropdown.blade.php',
        'personal-area-guest-cta.blade.php',
        'personal-area-guest-parity.blade.php',
        'personal-area-login-cta.blade.php',
    ];

    foreach ($areaPersonalePartials as $file) {
        $path = $themeRoot.'/resources/views/components/sections/header/partials/'.$file;
        if (! file_exists($path)) {
            continue;
        }
        $html = file_get_contents($path);
        expect($html)->not->toContain('href="/it/');
        expect($html)->not->toContain("href='/it/");
        expect($html)->not->toContain('href="/{{ app()->getLocale()');
        expect($html)->not->toContain('/profilo/');
        expect($html)->not->toContain('FrontofficeUrl::personalArea');
        expect($html)->not->toContain('<span>I miei servizi</span>');
    }
});

test('header riusa partial canonici area personale', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    // Dal 2026-11-11 l'header e' uno solo (`sections/header/v1`): `personal-area-guest-parity` e `header-comune` non sono piu' il consumer,
    // il guest ha `personal-area-login-cta` (gia' coperto sopra) e v1 include il partial `user-dropdown`.
    $partial = $themeRoot.'/resources/views/components/sections/header/partials/user-dropdown.blade.php';
    $consumer = $themeRoot.'/resources/views/components/sections/header/v1.blade.php';

    expect(file_exists($partial))->toBeTrue();
    expect(file_exists($consumer))->toBeTrue();
    expect(file_get_contents($consumer))->toContain('partials.user-dropdown');
    expect(file_get_contents($partial))->toContain('FrontofficeRoutes::personalAreaPratiche');
});

test('header v1 localizza gli url di header.json via fromStoredUrl', function (): void {
    // `nav-primary` e `nav-secondary` non esistono piu': la navigazione e' dentro v1 e passa da `$storedNavHref`.
    $html = file_get_contents(dirname(__DIR__, 2).'/resources/views/components/sections/header/v1.blade.php');

    expect($html)->toContain('FrontofficeUrl::fromStoredUrl');
    expect($html)->toContain('$storedNavHref(');
    expect($html)->not->toContain('href="/it/');
});

test('FrontofficeUrl non espone wrapper personalArea', function (): void {
    $php = file_get_contents(dirname(__DIR__, 2).'/app/Infrastructure/Url/FrontofficeUrl.php');

    expect($php)->not->toContain('personalAreaServices');
    expect($php)->not->toContain('personalAreaNotifications');
    expect($php)->toContain('fromStoredUrl');
});

test('nessun blade Sixteen usa FrontofficeUrl personalArea wrapper', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($themeRoot.'/resources/views', FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (! $file instanceof SplFileInfo) {
            continue;
        }
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        if (! str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }
        $html = file_get_contents($file->getPathname());
        expect($html)->not->toContain('FrontofficeUrl::personalArea', $file->getPathname());
    }
});

test('header v1 e partial area personale non usano le chiavi e i wrapper dell\'header legacy', function (): void {
    // Le varianti legacy (`header/authenticated`, `layout/design-comuni-header`, `ui/app/header`) sono state tolte con "header v1 unico template":
    // il divieto vale ora per i file vivi, cosi' non rientrano da un copia-incolla.
    $headerViews = dirname(__DIR__, 2).'/resources/views/components/sections/header';
    $live = [
        $headerViews.'/v1.blade.php',
        $headerViews.'/partials/user-dropdown.blade.php',
        $headerViews.'/partials/personal-area-login-cta.blade.php',
    ];

    foreach ($live as $path) {
        expect(file_exists($path))->toBeTrue($path.' manca');
        $html = file_get_contents($path);
        expect($html)->not->toContain('FrontofficeUrl::personalArea');
        expect($html)->not->toContain('pub_theme::ui.header_area_personale');
        expect($html)->not->toContain('ui::ui.profile');
    }
});
