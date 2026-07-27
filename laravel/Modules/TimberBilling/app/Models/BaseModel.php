<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Modules\Xot\Models\XotBaseModel;

abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'timber_billing';
}
