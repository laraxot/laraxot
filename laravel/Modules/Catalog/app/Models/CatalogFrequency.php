<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

final class CatalogFrequency extends Model
{
    protected $table = 'catalog_frequencies';

    /** @var array<int, string> */
    protected $guarded = [];
}
