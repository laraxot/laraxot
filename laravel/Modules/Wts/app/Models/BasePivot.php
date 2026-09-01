<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Modules\Xot\Models\XotBasePivot;

/**
 * Class BasePivot.
 */
abstract class BasePivot extends XotBasePivot
{
    /** @var string */
    protected $connection = 'wts';
}
