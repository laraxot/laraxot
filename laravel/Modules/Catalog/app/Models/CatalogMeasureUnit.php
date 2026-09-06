<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogMeasureUnitFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static CatalogMeasureUnitFactory factory($count = null, $state = [])
 */
final class CatalogMeasureUnit extends XotBaseModel
{
    use HasXotFactory;

    protected $table = 'catalog_measure_units';

    /** @var array<int, string> */
    protected $guarded = [];
}
