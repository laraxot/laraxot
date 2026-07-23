<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Documento di trasporto ricevuto dal fornitore. */
final class DeliveryNote extends Model
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
