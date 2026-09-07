<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Modules\Catalog\Database\Factories\CatalogFrequencyFactory;

final class CatalogFrequency extends BaseModel
{

    protected $table = 'catalog_frequencies';

    /** @var array<int, string> */
    protected $guarded = [];
}
