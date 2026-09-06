<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogPartTypeFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static CatalogPartTypeFactory factory($count = null, $state = [])
 */
final class CatalogPartType extends XotBaseModel
{
    /** @phpstan-use HasXotFactory */
    use HasXotFactory;

    protected $table = 'catalog_part_types';

    /** @var array<int, string> */
    protected $guarded = [];
}
