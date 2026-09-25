<?php

declare(strict_types=1);

namespace Modules\Notify\Phpstan;

use Modules\Notify\Models\BaseModel;
use Modules\Notify\Models\Traits\HasContact;

/** PHPStan probe host for {@see HasContact}. */
final class HasContactPhpstanProbe extends BaseModel
{
    use HasContact;

    protected $table = 'notify_phpstan_trait_probes';
}
