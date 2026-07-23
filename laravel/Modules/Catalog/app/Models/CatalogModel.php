<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

final class CatalogModel extends Model
{
    protected $table = 'catalog_models';

    /** @var array<int, string> */
    protected $guarded = [];
}
