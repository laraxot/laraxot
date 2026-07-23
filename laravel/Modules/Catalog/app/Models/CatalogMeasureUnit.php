<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

final class CatalogMeasureUnit extends Model
{
    protected $table = 'catalog_measure_units';

    /** @var array<int, string> */
    protected $guarded = [];
}
