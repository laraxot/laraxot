<?php

declare(strict_types=1);

namespace Modules\Quotation\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Database\Factories\QuotationFactory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\Traits\HasXotFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $source_quotation_id
 * @property int|null $work_order_id
 * @property string|null $project
 * @property float|string|null $cost_total
 * @property Carbon|null $delivery_at
 * @property int|null $lead_time_days
 * @property string|null $code
 * @property string $status_code
 * @property string|null $title
 * @property string|null $notes
 * @property string|null $voice_transcript
 * @property array<string, mixed>|null $voice_ai_response
 * @property array<string, mixed>|null $technical_params
 * @property string $subtotal
 * @property string $discount_amount
 * @property string $taxable_amount
 * @property string $tax_percent
 * @property string $tax_amount
 * @property string $total
 * @property Carbon|null $issued_at
 * @property Carbon|null $confirmed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class Quotation extends BaseModel implements HasMedia
{
    use HasXotFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'quotations';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'delivery_at' => 'datetime',
            'lead_time_days' => 'integer',
            'cost_total' => 'decimal:2',
            'issued_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'taxable_amount' => 'decimal:2',
            'tax_percent' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'technical_params' => 'array',
            'voice_ai_response' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function newFactory(): QuotationFactory
    {
        return QuotationFactory::new();
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return HasMany<QuotationItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }
}
