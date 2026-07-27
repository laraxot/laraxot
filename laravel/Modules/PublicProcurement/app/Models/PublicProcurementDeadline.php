<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicProcurement\Database\Factories\PublicProcurementDeadlineFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static PublicProcurementDeadlineFactory factory($count = null, $state = [])
 */
final class PublicProcurementDeadline extends XotBaseModel
{
    /** @phpstan-use HasXotFactory<PublicProcurementDeadlineFactory> */
    use HasXotFactory;

    use SoftDeletes;

    protected $table = 'public_procurement_deadlines';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }
}
