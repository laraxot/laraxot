<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $color
 * @property bool $is_terminal
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class WorkOrderStatus extends BaseModel
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
