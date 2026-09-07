<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Mockery\Expectation;
=======
use Mockery\CompositeExpectation;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Mockery\Expectation;
>>>>>>> a988596b (first)
use Mockery\MockInterface;

if (! function_exists('typedMock')) {
    /**
     * Mockery::mock() ha un tipo di ritorno nativo generico (LegacyMockInterface),
     * senza estensione PHPStan/Larastan dedicata in questo progetto. Questo helper
     * tipizzato (stesso pattern di Modules/User/tests/Support/helpers.php::typedMock())
     * restituisce l'intersection type reale T&MockInterface, cosi' il mock puo'
     * essere passato a firme che richiedono T senza errori argument.type.
     *
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T&MockInterface
     */
    function typedMock(string $class): MockInterface
    {
        /** @var T&MockInterface $mock */
        $mock = Mockery::mock($class);

        return $mock;
    }
}

if (! function_exists('mockExpectation')) {
    /**
     * Mockery::shouldReceive() dichiara nativamente il tipo di ritorno
     * `Expectation|ExpectationInterface|HigherOrderMessage` (vedi
     * vendor/mockery/mockery/library/Mockery/LegacyMockInterface.php). Quando
     * viene chiamato con un singolo nome di metodo (non un array, non senza
     * argomenti) restituisce sempre una `Expectation` concreta a runtime, ma
     * PHPStan non puo' restringere l'unione in base al valore dell'argomento.
     * Questo helper incapsula quella certezza runtime in un punto solo, cosi'
     * `->with()`, `->andReturn()`, `->once()`, `->times()` restano disponibili
     * senza `method.notFound`/`method.nonObject` sparsi in ogni test.
     *
     * Nota: chiamato con un singolo nome di metodo, `Mock::shouldReceive()`
     * restituisce a runtime una `Mockery\Expectation` concreta (vedi
     * vendor/mockery/mockery/library/Mockery/Mock.php::shouldReceive()), che
     * espone nativamente `with()`, `andReturn()`, `once()`, `times()`. La firma
     * nativa dichiara pero' l'unione `ExpectationInterface|Expectation|
     * HigherOrderMessage`: questo helper la restringe in un punto solo cosi'
     * quei metodi restano disponibili senza `method.notFound`/`method.nonObject`
     * sparsi in ogni test.
     *
     * ATTENZIONE (trovato 2026-09-06, non ancora risolto): questo restringimento
     * e' FALSO quando lo stesso `$method` viene ri-atteso una seconda volta sullo
     * STESSO mock (`NotificationManagerTest::getTemplate()` lo fa per impostare
     * due `->with()` diversi sullo stesso metodo) — in quel caso Mockery
     * restituisce una `Mockery\CompositeExpectation`, non una `Expectation`, e
     * questa dichiarazione di tipo causa un vero `TypeError` a runtime (non solo
     * un mismatch statico). Non risolto qui: allargare l'unione a
     * `Expectation|CompositeExpectation` fa sparire il `TypeError` ma sposta il
     * problema su PHPStan (`CompositeExpectation` espone `with()`/`once()`/
     * `times()` solo via `__call()`, quindi `method.notFound` su ogni chiamata),
     * e comunque `CompositeExpectation::__call()` applica la nuova `->with()` a
     * TUTTE le aspettative gia' composte per quel metodo (vedi il sorgente
     * Mockery), quindi il pattern "due `shouldReceive()` con `->with()` diversi
     * sullo stesso metodo" e' probabilmente da riscrivere (es. un solo
     * `shouldReceive()->andReturnUsing(fn ($arg) => ...)`), non solo da
     * ritipizzare. Root cause di infrastruttura gia' corretta separatamente:
     * questo file non era caricato affatto prima del 2026-09-06 (mancava
     * `autoload-dev.files` in `Modules/Notify/composer.json`, vedi
     * `docs/stories/01.Notify-phpstan-fix.story.md`), quindi il bug qui sopra
     * era sempre stato mascherato da un piu' rumoroso "undefined function
     * mockExpectation()".
     */
<<<<<<< HEAD
<<<<<<< HEAD
    function mockExpectation(MockInterface $mock, string $method): Expectation
    {
        /** @var Expectation $expectation */
=======
    function mockExpectation(MockInterface $mock, string $method): CompositeExpectation
    {
        /** @var CompositeExpectation $expectation */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    function mockExpectation(MockInterface $mock, string $method): Expectation
    {
        /** @var Expectation $expectation */
>>>>>>> a988596b (first)
        $expectation = $mock->shouldReceive($method);

        return $expectation;
    }
}
