<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Modules\Xot\Models\XotBasePivot;

/**
 * Base pivot for Billing module, inherits from XotBasePivot.
 */
abstract class BasePivot extends XotBasePivot
{
    protected $connection = 'billing';
}
