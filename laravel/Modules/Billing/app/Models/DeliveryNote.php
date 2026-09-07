<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * Documento di trasporto ricevuto dal fornitore.
 *
 * @property int $id
 * @property int $supplier_id
 * @property int|null $work_order_id
 * @property string $number
 * @property Carbon|null $received_on
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static \Modules\Billing\Database\Factories\DeliveryNoteFactory factory($count = null, $state = [])
 */
final class DeliveryNote extends BaseModel
{
    use SoftDeletes;

    protected $table = 'delivery_notes';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['received_on' => 'date'];
    }

    /** @return BelongsTo<Supplier, $this> */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /** @return HasMany<DeliveryNoteLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(DeliveryNoteLine::class, 'delivery_note_id');
    }
}
