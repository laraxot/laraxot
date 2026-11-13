<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Assert;
use Tests\TestCase;
use Themes\Sixteen\Providers\ThemeServiceProvider;

uses(TestCase::class);

/*
 * SEC-02: il callback SPID accettava una SAMLResponse senza verificarne la firma, quindi chiunque poteva autenticarsi
 * con un codice fiscale arbitrario. Le rotte SPID/CIE si registrano solo con SIXTEEN_SPID_ENABLED=true.
 */
it('keeps SPID and CIE routes switched off unless explicitly enabled', function (): void {
    if (ThemeServiceProvider::digitalIdentityRoutesEnabled()) {
        $this->markTestSkipped('SIXTEEN_SPID_ENABLED=true in questo ambiente.');
    }

    foreach (['spid.login', 'spid.callback', 'spid.slo', 'cie.login', 'cie.callback', 'cie.debug', 'sixteen.test-auth.spid.simulate'] as $name) {
        Assert::assertFalse(Route::has($name), "La rotta {$name} non deve esistere con l'identità digitale disattivata.");
    }
});

it('reads the switch from the sixteen.auth.spid.enabled config key', function (): void {
    config(['sixteen.auth.spid.enabled' => false]);
    Assert::assertFalse(ThemeServiceProvider::digitalIdentityRoutesEnabled());

    config(['sixteen.auth.spid.enabled' => 'true']);
    Assert::assertFalse(ThemeServiceProvider::digitalIdentityRoutesEnabled(), 'Solo il booleano true abilita le rotte.');

    config(['sixteen.auth.spid.enabled' => true]);
    Assert::assertTrue(ThemeServiceProvider::digitalIdentityRoutesEnabled());
});
