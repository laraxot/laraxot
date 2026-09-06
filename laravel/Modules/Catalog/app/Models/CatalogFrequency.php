<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogFrequencyFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static CatalogFrequencyFactory factory($count = null, $state = [])
 */
final class CatalogFrequency extends XotBaseModel
{
    /** @phpstan-use HasXotFactory */
    use HasXotFactory;

    protected $table = 'catalog_frequencies';

    /** @var array<int, string> */
    protected $guarded = [];
}
