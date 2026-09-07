<?php

declare(strict_types=1);

namespace Modules\Production\Models;

use Modules\Xot\Models\XotBaseModel;

/**
 * Base model for Production module, inherits from XotBaseModel.
 */
abstract class BaseModel extends XotBaseModel
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'production';
}
