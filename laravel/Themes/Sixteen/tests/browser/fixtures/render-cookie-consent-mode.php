<?php

declare(strict_types=1);

use function Safe\file_put_contents;

/*
 * Rende la vista del banner dei cookie (Modules/Gdpr/resources/views/cookie-consent) in MODO CONSENSO: dichiara un cookie analitico
 * di prova in `gdpr.config.cookie.extra` (la chiave e' sotto `config`: la config del modulo si carica come `gdpr.config.*`).
 * Uso, da laravel/:  OUT=/percorso/cookie-consent-mode.html php artisan tinker --execute="require 'Themes/Sixteen/tests/browser/fixtures/render-cookie-consent-mode.php';"
 * poi:               COOKIE_CONSENT_FIXTURE=/percorso/cookie-consent-mode.html npx playwright test tests/browser/cookie-banner.spec.mjs
 */
config(['gdpr.config.cookie.extra' => [['name' => '_demo_analytics', 'category' => 'analytics', 'purpose' => 'Misura le visite', 'duration' => '30 giorni']]]);
app()->setLocale('it');
$out = getenv('OUT');
if (! is_string($out) || $out === '') {
    throw new RuntimeException('Impostare OUT con il percorso del file da scrivere.');
}
file_put_contents($out, view('cookie-consent::index')->render());
echo "scritto {$out}\n";
