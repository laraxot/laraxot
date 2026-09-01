<?php

declare(strict_types=1);

namespace Modules\UI\Tests\Unit;

use Mockery\ExpectationInterface;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

/*
 * L'helper viveva in tre file di test, ognuno con la propria guardia
 * function_exists('expectMethod'). La guardia non funzionava: la funzione sta in
 * un namespace, e function_exists senza namespace cerca quella globale. Il
 * secondo file caricato faceva quindi fallire l'intera suite con un
 * "Cannot redeclare". Ora e' dichiarata una volta sola.
 */
if (! function_exists(__NAMESPACE__.'\expectMethod')) {
    /**
     * Restringe il tipo di ritorno unione di shouldReceive() per PHPStan.
     *
     * Il tipo dichiarato era Expectation, ma shouldReceive() restituisce una
     * CompositeExpectation: i test morivano con un TypeError. Entrambe
     * implementano ExpectationInterface, che e' il tipo davvero comune.
     */
    function expectMethod(LegacyMockInterface|MockInterface $mock, string $method): ExpectationInterface
    {
        /** @var ExpectationInterface $expectation */
        $expectation = $mock->shouldReceive($method);

        return $expectation;
    }
}
