<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Xot\Models\XotBaseModel;

final class CatalogFrequency extends XotBaseModel
{
    protected $table = 'catalog_frequencies';

    /** @var array<int, string> */
    protected $guarded = [];
}
