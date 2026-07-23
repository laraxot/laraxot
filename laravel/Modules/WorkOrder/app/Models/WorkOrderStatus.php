<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Illuminate\Database\Eloquent\Model;

final class WorkOrderStatus extends Model
{
    protected $table = 'work_order_statuses';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_terminal' => 'bool',
        ];
    }
}

