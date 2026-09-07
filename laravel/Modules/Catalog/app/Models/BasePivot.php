<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Xot\Models\XotBasePivot;

/**
 * Base Pivot for Catalog module.
 *
 * @see XotBasePivot
 */
abstract class BasePivot extends XotBasePivot
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'catalog';
}
