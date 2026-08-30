<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignComponentFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * One component/material row of a sign spec (distinta base). Priced by linking
 * to a price-list item; line_total feeds the quotation total.
 *
 * @property int $id
 * @property int $sign_spec_id
 * @property int $position
 * @property string $role_code
 * @property string $label
 * @property string|null $description
 * @property string|null $material
 * @property string|null $color_ral
 * @property numeric-string|null $thickness_mm
 * @property string|null $lighting
 * @property string $source_code
 * @property int|null $price_list_item_id
 * @property numeric-string|null $quantity
 * @property string|null $unit_code
 * @property numeric-string|null $unit_cost
 * @property numeric-string|null $line_total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignComponent extends XotBaseModel
{
    /** @use HasFactory<SignComponentFactory> */
    use HasFactory;

    protected $table = 'sign_components';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'thickness_mm' => 'decimal:2',
            'quantity' => 'decimal:3',
            'unit_cost' => 'decimal:4',
            'line_total' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<SignSpec, $this> */
    public function signSpec(): BelongsTo
    {
        return $this->belongsTo(SignSpec::class, 'sign_spec_id');
    }

    /** @return BelongsTo<SignPriceListItem, $this> */
    public function priceListItem(): BelongsTo
    {
        return $this->belongsTo(SignPriceListItem::class, 'price_list_item_id');
    }

    protected static function newFactory(): SignComponentFactory
    {
        return SignComponentFactory::new();
    }
}
