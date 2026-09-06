<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogModelFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static CatalogModelFactory factory($count = null, $state = [])
 */
final class CatalogModel extends XotBaseModel
{
    /** @phpstan-use HasXotFactory */
    use HasXotFactory;

    protected $table = 'catalog_models';

    /** @var array<int, string> */
    protected $guarded = [];
}
