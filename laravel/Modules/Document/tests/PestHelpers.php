<?php

declare(strict_types=1);

use Modules\Intervention\Database\Factories\InterventionFactory;
use Modules\Intervention\Models\Intervention;

/**
 * Helper Pest/PHPStan — modulo Document.
 *
 * @see Modules/Cms/tests/PestHelpers.php
 */

/**
 * @param  array<string, mixed>  $attributes
 */
function documentCreateIntervention(array $attributes = []): Intervention
{
    return InterventionFactory::new()->createOne($attributes);
}
