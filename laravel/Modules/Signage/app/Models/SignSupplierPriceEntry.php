<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignSupplierPriceEntryFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $sign_price_list_item_id
 * @property string|null $invoice_number
 * @property Carbon $recorded_on
 * @property numeric-string $amount
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignSupplierPriceEntry extends XotBaseModel
{

    protected $table = 'sign_supplier_price_entries';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'amount' => 'decimal:4',
        ];
    }

    /** @return BelongsTo<SignPriceListItem, $this> */
    public function supplierItem(): BelongsTo
    {
        return $this->belongsTo(SignPriceListItem::class, 'sign_price_list_item_id');
    }

    protected static function newFactory(): SignSupplierPriceEntryFactory
    {
        return SignSupplierPriceEntryFactory::new();
    }
}
