<?php

declare(strict_types=1);

use function Safe\file_get_contents;

test('auth login styling is scoped to reusable component hooks', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $css = file_get_contents($themeRoot.'/resources/css/app/14-auth-login.css');
    $login = file_get_contents($themeRoot.'/resources/views/pages/auth/login.blade.php');
    // `pages/auth-login.blade.php` e' ormai un alias di una riga: il blocco vero sta in `pages/auth/login.blade.php`.
    $component = file_get_contents($themeRoot.'/resources/views/components/pages/auth/login.blade.php');

    expect($css)
        ->toContain('.auth-login-card')
        ->toContain('.auth-register-card')
        ->not->toContain('data-page');
    expect($login)
        ->not->toContain('body-page=')
        ->toContain('data-slug=')
        ->toContain("pageSlug = 'auth.login'");
    expect($component)
        ->toContain('$heading')
        ->toContain('$intro')
        ->toContain('components.utilities.google-button')
        ->not->toContain('submitting: false');
});

test('google auth control does not depend on a third-party image request', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    $googleButton = file_get_contents($themeRoot.'/resources/views/components/utilities/google-button.blade.php');

    expect($googleButton)
        ->toContain('<svg')
        ->toContain("route('oauth.redirect', ['provider' => 'google', 'lang' => \$locale])")
        ->not->toContain("route('oauth.redirect', ['provider' => 'google'])")
        ->not->toContain('accounts.google.com/favicon.ico');
});

test('login paints the Filament password reveal and remember checkbox inside the data-dc scope', function (): void {
    $themeRoot = dirname(__DIR__, 2);
    // DCP-290: la resa condivisa di login, registrazione e recupero sta in components/auth/assets.blade.php.
    $component = file_get_contents($themeRoot.'/resources/views/components/auth/assets.blade.php');
    $dcComponents = file_get_contents($themeRoot.'/resources/css/app/20-dc-components.css');

    // Lo sheet compilato non dipinge `.fi-input-wrp` dentro data-dc: senza queste regole l'occhio di
    // `TextInput::revealable()` cade sotto il campo come un'icona gigante con il testo in chiaro.
    expect($component)
        ->toContain('.fi-input-wrp:has(input.fi-revealable)')
        ->toContain('.fi-input-wrp-actions .fi-ac-btn-action');

    // `body:has(.page-content[data-dc]) input { appearance: none !important }`: senza `!important`
    // il checkbox di `Checkbox::make('remember')` resta 20x20 ma invisibile.
    expect($component)
        ->toContain('input.fi-checkbox-input')
        ->toMatch('/appearance:\s*auto\s*!important/')
        ->toMatch('/-webkit-appearance:\s*checkbox\s*!important/');

    // Stesso contratto nel foglio DC (regole già scoped, non isolate dal PostCSS legacy).
    expect($dcComponents)
        ->toContain('.page-content[data-dc] .auth-login-card input.fi-checkbox-input')
        ->toMatch('/appearance:\s*auto\s*!important/')
        ->toMatch('/-webkit-appearance:\s*checkbox\s*!important/');
});
