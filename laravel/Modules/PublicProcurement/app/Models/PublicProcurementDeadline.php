<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicProcurement\Database\Factories\PublicProcurementDeadlineFactory;

final class PublicProcurementDeadline extends BaseModel
{
    /**
 * @method static PublicProcurementDeadlineFactory factory($count = null, $state = [])
 */

    protected static function newFactory(): PublicProcurementDeadlineFactory
    {
        return PublicProcurementDeadlineFactory::new();
    }

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
