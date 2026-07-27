<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Modules\Xot\Models\XotBaseModel;

abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'work_order';
}
