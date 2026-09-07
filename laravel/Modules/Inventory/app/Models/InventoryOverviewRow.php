<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

final class InventoryOverviewRow extends BaseModel
{
    protected $table = 'inventory_overview_rows';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    /** @var array<int, string> */
    protected $guarded = [];
}
