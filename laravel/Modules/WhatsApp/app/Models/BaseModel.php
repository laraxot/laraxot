<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Models;

use Modules\Xot\Models\XotBaseModel;

abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'whats_app';
}
