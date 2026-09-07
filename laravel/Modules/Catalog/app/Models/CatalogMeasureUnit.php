<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogMeasureUnitFactory;

final class CatalogMeasureUnit extends BaseModel
{

    protected $table = 'catalog_measure_units';

    /** @var array<int, string> */
    protected $guarded = [];
}
