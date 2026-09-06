<?php

declare(strict_types=1);

namespace Modules\Quotation\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Catalog\Models\Product;
use Modules\Quotation\Database\Factories\QuotationTemplateItemFactory;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * @property int $id
 * @property int $quotation_template_id
 * @property int|null $product_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_price
 * @property string $discount_percent
 * @property string $line_total
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class QuotationTemplateItem extends BaseModel
{
    use HasXotFactory;

    protected $table = 'quotation_template_items';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    protected static function newFactory(): QuotationTemplateItemFactory
    {
        return QuotationTemplateItemFactory::new();
    }

    /** @return BelongsTo<QuotationTemplate, $this> */
    public function template(): BelongsTo
    {
        return $this->belongsTo(QuotationTemplate::class, 'quotation_template_id');
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
