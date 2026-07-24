<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberPurchaseRecordFactory;
use Modules\TimberBilling\Support\WoodSpeciesCatalog;
use Modules\User\Models\User;

/**
 * @property int $supplier_id
 * @property int|null $transporter_id
 * @property string|null $origin_forest
 * @property string|null $ddt_number
 * @property Carbon|null $ddt_date
 * @property string $wood_species
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string|null $gross_weight
 * @property string|null $net_weight
 * @property string|null $unit_purchase_price
 * @property string|null $certifications
 * @property string|null $photo_path
 * @property string|null $notes
 * @property string $status_code
 * @property int|null $created_by_user_id
 * @property Carbon|null $received_at
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $purchase_price_unit
 */
final class TimberPurchaseRecord extends Model
{
    /** @use HasFactory<TimberPurchaseRecordFactory> */
    use HasFactory;

    protected $table = 'timber_purchase_records';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'gross_weight' => 'decimal:2',
            'net_weight' => 'decimal:2',
            'unit_purchase_price' => 'decimal:2',
            'ddt_date' => 'date',
            'received_at' => 'datetime',
        ];
    }

    /** @return Attribute<string, never> */
    protected function woodSpeciesName(): Attribute
    {
        return Attribute::get(fn (): string => WoodSpeciesCatalog::name((string) $this->wood_species));
    }

    /** @return BelongsTo<TimberSupplier, $this> */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(TimberSupplier::class, 'supplier_id');
    }

    /** @return BelongsTo<TimberTransporter, $this> */
    public function transporter(): BelongsTo
    {
        return $this->belongsTo(TimberTransporter::class, 'transporter_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /** @return HasOne<TimberLot, $this> */
    public function lot(): HasOne
    {
        return $this->hasOne(TimberLot::class, 'purchase_record_id');
    }

    protected static function newFactory(): TimberPurchaseRecordFactory
    {
        return TimberPurchaseRecordFactory::new();
    }
}
