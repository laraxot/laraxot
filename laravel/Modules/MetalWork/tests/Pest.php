<?php

declare(strict_types=1);

// Gli helper stanno in un file a parte: Pest carica ogni file della suite nello
// stesso processo, e una funzione dichiarata due volte e' un errore fatale.
require_once __DIR__.'/Feature/MetalWorkHelpers.php';

// Ogni file test dichiara uses(\Modules\MetalWork\Tests\TestCase::class) singolarmente.
