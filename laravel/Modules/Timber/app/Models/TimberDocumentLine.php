<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberDocumentLineFactory;

/**
 * @property int $document_id
 * @property int|null $product_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $unit_price
 * @property string $discount_percent
 * @property string|null $lot_code
 * @property string $line_total
 * @property int $position
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberDocumentLine extends BaseModel
{

    protected $table = 'timber_document_lines';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:4',
            'discount_percent' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<TimberDocument, $this> */
    public function document(): BelongsTo
    {
        return $this->belongsTo(TimberDocument::class, 'document_id');
    }

    /** @return BelongsTo<TimberProduct, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(TimberProduct::class, 'product_id');
    }

    /**
     * Net line total: quantity * unit price, minus the line discount.
     */
    public function computeLineTotal(): float
    {
        $gross = (float) $this->quantity * (float) $this->unit_price;
        $net = $gross * (1 - ((float) $this->discount_percent / 100));

        return round($net, 2);
    }

    protected static function newFactory(): TimberDocumentLineFactory
    {
        return TimberDocumentLineFactory::new();
    }
}
