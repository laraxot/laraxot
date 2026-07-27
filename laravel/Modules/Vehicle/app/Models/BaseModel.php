<?php

declare(strict_types=1);

namespace Modules\Vehicle\Models;

use Modules\Xot\Models\XotBaseModel;

abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'vehicle';
}
