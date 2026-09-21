<?php

declare(strict_types=1);

use Modules\Geo\Database\Factories\ComuneFactory;
use Modules\Geo\Database\Factories\RegionFactory;
use Modules\Geo\Models\Comune;
use Modules\Geo\Models\Region;
use Modules\Geo\Tests\TestCase;

/*
 * Bootstrap Pest — modulo Geo.
 * `pest()->extend(TestCase::class)->in(...)` è la forma **consigliata** (XOT-5.41).
 * Non duplicare `uses(TestCase::class)` nei file: XOR → TestCaseAlreadyInUse.
 */

/**
 * @param  array<string, mixed>  $attributes
 */
function createRegion(array $attributes = []): Region
{
    return RegionFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function createComune(array $attributes = []): Comune
{
    return ComuneFactory::new()->createOne($attributes);
}

pest()->extend(TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature');
