<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Xot\Models\XotBaseModel;

final class CatalogModel extends XotBaseModel
{
    protected $table = 'catalog_models';

    /** @var array<int, string> */
    protected $guarded = [];
}
