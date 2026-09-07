<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogPartTypeFactory;

final class CatalogPartType extends BaseModel
{

    protected $table = 'catalog_part_types';

    /** @var array<int, string> */
    protected $guarded = [];
}
