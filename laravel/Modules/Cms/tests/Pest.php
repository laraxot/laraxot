<?php

declare(strict_types=1);

/*
 * |--------------------------------------------------------------------------
 * | Test Case
 * |--------------------------------------------------------------------------
 * |
 * | Il TestCase di default per tutti i test del modulo Cms.
 * | Ogni file di test dichiara esplicitamente uses(Modules\Cms\Tests\TestCase::class),
 * | seguendo il pattern DRY comune a tutti i moduli (vedi Modules/Xot/tests/Pest.php).
 * | Nessun ->in() qui: e' un metodo interno al namespace Pest, non analizzabile
 * | da PHPStan al di fuori di esso (method.internalClass).
 * |
 */

/*
 * |--------------------------------------------------------------------------
 * | Expectations
 * |--------------------------------------------------------------------------
 * |
 * | Qui puoi definire aspettative globali per il modulo Cms.
 * | Quando definisci qui expectation globali, saranno disponibili
 * | in tutti i test del modulo.
 * |
 */

require_once __DIR__.'/PestHelpers.php';
require_once __DIR__.'/PestStubs.php';

expect()->extend('toBeSubclassOf', function (string $parentClass) {
    $class = $this->value;
    if (! is_string($class)) {
        throw new InvalidArgumentException('Expected a class name string');
    }

    return $this->and(is_subclass_of($class, $parentClass));
});

/*
 * |--------------------------------------------------------------------------
 * | Functions
 * |--------------------------------------------------------------------------
 * |
 * | Qui puoi definire funzioni helper globali per i test del modulo.
 * | Queste funzioni saranno disponibili in tutti i test.
 * |
 */
