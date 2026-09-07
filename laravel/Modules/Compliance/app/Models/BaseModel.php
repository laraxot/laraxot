<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Modules\Xot\Models\XotBaseModel;

/**
 * Base model for Compliance module, inherits from XotBaseModel.
 */
abstract class BaseModel extends XotBaseModel
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'compliance';
}
