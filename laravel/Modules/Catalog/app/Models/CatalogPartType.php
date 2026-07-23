<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

final class CatalogPartType extends Model
{
    protected $table = 'catalog_part_types';

    /** @var array<int, string> */
    protected $guarded = [];
}
