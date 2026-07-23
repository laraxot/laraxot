<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

final class InventoryOverviewRow extends Model
{
    protected $table = 'inventory_overview_rows';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    /** @var array<int, string> */
    protected $guarded = [];
}
