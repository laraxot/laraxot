<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicProcurement\Database\Factories\PublicProcurementPriceItemFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static PublicProcurementPriceItemFactory factory($count = null, $state = [])
 */
final class PublicProcurementPriceItem extends XotBaseModel
{
    /** @phpstan-use HasXotFactory<PublicProcurementPriceItemFactory> */
    use HasXotFactory;

    use SoftDeletes;

    protected $table = 'public_procurement_price_items';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'planned_quantity' => 'decimal:3',
            'unit_price' => 'decimal:4',
            'planned_total' => 'decimal:2',
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }
}
