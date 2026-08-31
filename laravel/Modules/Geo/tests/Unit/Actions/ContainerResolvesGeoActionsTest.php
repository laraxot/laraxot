<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Actions;

use Modules\Geo\Actions\CalculateDistanceAction;
use Modules\Geo\Actions\GoogleMaps\GetAddressFromGoogleMapsAction;
use Modules\Geo\Actions\Here\GetAddressFromHereMapsAction;
use Modules\Geo\Tests\TestCase;
use PHPUnit\Framework\Assert;

/*
 * Supera `tests/Unit/Services/ServicesTest.php`, rimosso il 2026-08-25.
 *
 * Quel file istanziava `Modules\Geo\Services\{GeoService, GoogleMapsService,
 * HereService}`, tre classi cancellate dalla migrazione verso le Action
 * (regola no-services). Il test non e' stato riscritto contro le Action per
 * comodita': verificava una cosa sola, che il container sapesse costruire i
 * punti d'ingresso del modulo, ed e' quella che vive ancora qui.
 *
 * Aveva anche un difetto suo: `uses(\Modules\Geo\Tests\TestCase::class)` compariva **prima** delle
 * dichiarazioni `use`, quindi `TestCase` si risolveva nel namespace del file e
 * non nell'import. In PHP l'import e' di file ma la risoluzione dei nomi segue
 * l'ordine: scrivere `use` dopo l'uso non lo copre.
 *
 * Le asserzioni non usano `toBeInstanceOf()`: `app(X::class)` e' gia' tipizzato
 * `X`, quindi sarebbe garantita e PHPStan la segnala come ridondante. Cio' che
 * qui non e' garantito, e vale la pena verificare, e' che la risoluzione non
 * sollevi e che l'oggetto esponga il contratto `execute()` delle Action.
 *
 * Story ROOT-17.10.
 */

test('il container risolve le action di geocoding senza sollevare', function (): void {
    Assert::assertTrue(is_callable([app(GetAddressFromGoogleMapsAction::class), 'execute']));
    Assert::assertTrue(is_callable([app(GetAddressFromHereMapsAction::class), 'execute']));
    Assert::assertTrue(is_callable([app(CalculateDistanceAction::class), 'execute']));
});

test('ogni risoluzione restituisce una istanza nuova, non un singleton condiviso', function (): void {
    Assert::assertNotSame(
        app(GetAddressFromGoogleMapsAction::class),
        app(GetAddressFromGoogleMapsAction::class),
    );
});
