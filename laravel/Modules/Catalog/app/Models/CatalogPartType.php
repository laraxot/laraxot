<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Xot\Models\XotBaseModel;

final class CatalogPartType extends XotBaseModel
{
    protected $table = 'catalog_part_types';

    /** @var array<int, string> */
    protected $guarded = [];
}
