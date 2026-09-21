<?php

declare(strict_types=1);

use Modules\Cms\Tests\TestCase;

/*
 * |--------------------------------------------------------------------------
 * | Test Case
 * |--------------------------------------------------------------------------
 * |
 * | `pest()->extend(TestCase::class)->in(...)` è la forma **consigliata**
 * | (Pest configuring-tests, XOT-5.41 + pest-plugin-phpstan).
 * | Non duplicare `uses(TestCase::class)` nei file di test: XOR → TestCaseAlreadyInUse.
 * |
 */

require_once __DIR__.'/PestHelpers.php';

pest()->extend(TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature');
