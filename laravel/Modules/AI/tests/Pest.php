<?php

declare(strict_types=1);

use Modules\AI\Tests\TestCase;

/*
 * Bootstrap Pest — modulo AI.
 * `pest()->extend(TestCase::class)->in(...)` è la forma **consigliata** (XOT-5.41).
 * Non duplicare `uses(TestCase::class)` nei file: XOR → TestCaseAlreadyInUse.
 * Vietato RefreshDatabase (dati sacri).
 */
pest()->extend(TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature');
