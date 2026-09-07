<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogModelFactory;

final class CatalogModel extends BaseModel
{

    protected $table = 'catalog_models';

    /** @var array<int, string> */
    protected $guarded = [];
}
