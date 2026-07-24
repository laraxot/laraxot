<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Modules\Xot\Models\XotBaseModel;

final class InventoryOverviewRow extends XotBaseModel
{
    protected $table = 'inventory_overview_rows';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    /** @var array<int, string> */
    protected $guarded = [];
}
