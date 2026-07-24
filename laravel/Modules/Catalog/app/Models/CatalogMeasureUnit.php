<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Xot\Models\XotBaseModel;

final class CatalogMeasureUnit extends XotBaseModel
{
    protected $table = 'catalog_measure_units';

    /** @var array<int, string> */
    protected $guarded = [];
}
