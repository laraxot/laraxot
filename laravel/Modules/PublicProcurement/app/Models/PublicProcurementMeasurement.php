<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Intervention\Models\Intervention;

final class PublicProcurementMeasurement extends Model
{
    use SoftDeletes;

    protected $table = 'public_procurement_measurements';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'measured_on' => 'date',
            'quantity' => 'decimal:3',
            'amount' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }

    /** @return BelongsTo<PublicProcurementPriceItem, $this> */
    public function priceItem(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementPriceItem::class, 'price_item_id');
    }

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'intervention_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
