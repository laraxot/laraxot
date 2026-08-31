<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberDocumentFactory;
use Modules\User\Models\User;

/**
 * @property string $document_type_code
 * @property string|null $number
 * @property Carbon $document_date
 * @property int|null $customer_id
 * @property string $status_code
 * @property string|null $reference
 * @property string $total_amount
 * @property string|null $notes
 * @property Carbon|null $stock_posted_at
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class TimberDocument extends BaseModel
{

    use SoftDeletes;

    protected $table = 'timber_documents';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'total_amount' => 'decimal:2',
            'stock_posted_at' => 'datetime',
        ];
    }

    /** @return HasMany<TimberDocumentLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(TimberDocumentLine::class, 'document_id');
    }

    /** @return BelongsTo<TimberCustomer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(TimberCustomer::class, 'customer_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Recompute each line total and the document grand total, then persist.
     */
    public function recalculateTotals(): void
    {
        $grandTotal = 0.0;

        foreach ($this->lines()->get() as $line) {
            $lineTotal = $line->computeLineTotal();
            $line->forceFill(['line_total' => $lineTotal])->save();
            $grandTotal += $lineTotal;
        }

        $this->forceFill(['total_amount' => round($grandTotal, 2)])->save();
    }

    public function hasStockImpact(): bool
    {
        $map = config('timber.timber.document_stock_directions', []);

        return is_array($map) && array_key_exists($this->document_type_code, $map);
    }

    protected static function newFactory(): TimberDocumentFactory
    {
        return TimberDocumentFactory::new();
    }
}
