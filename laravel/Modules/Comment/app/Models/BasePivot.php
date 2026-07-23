<?php

declare(strict_types=1);

namespace Modules\Comment\Models;

use Modules\Xot\Models\XotBasePivot;

/**
 * Base Pivot for Comment module.
 *
 * Extends XotBasePivot which provides all standard properties and casts.
 *
 * @see XotBasePivot
 */
abstract class BasePivot extends XotBasePivot
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'comment';
}
