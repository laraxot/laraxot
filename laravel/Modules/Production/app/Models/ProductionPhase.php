<?php

declare(strict_types=1);

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Production\Database\Factories\ProductionPhaseFactory;
use Modules\User\Models\User;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $production_order_id
 * @property string $name
 * @property int $sort_order
 * @property string $status
 * @property int|null $assigned_to_user_id
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 *
 * @method static ProductionPhaseFactory factory($count = null, $state = [])
 */
final class ProductionPhase extends XotBaseModel
{

    protected $table = 'production_phases';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<ProductionOrder, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    /** @return BelongsTo<User, $this> */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
