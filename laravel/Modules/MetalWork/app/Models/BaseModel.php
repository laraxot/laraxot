<?php

declare(strict_types=1);

namespace Modules\MetalWork\Models;

use Modules\Xot\Models\XotBaseModel;

abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'metal_work';
}
