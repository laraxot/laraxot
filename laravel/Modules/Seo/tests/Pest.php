<?php

declare(strict_types=1);

use Modules\Seo\Tests\TestCase;

/*
 * Bootstrap Pest — modulo Seo.
 * `pest()->extend(TestCase::class)->in(...)` è la forma **consigliata** (XOT-5.41).
 * Non duplicare `uses(TestCase::class)` nei file: XOR → TestCaseAlreadyInUse.
 */
pest()->extend(TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature');

uses()->group('seo');
