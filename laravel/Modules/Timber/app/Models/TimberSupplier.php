<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberSupplierFactory;

/**
 * @property string $legal_name
 * @property string|null $vat_number
 * @property string|null $tax_code
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $province
 * @property string $country_code
 * @property string|null $certifications
 * @property string|null $notes
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class TimberSupplier extends BaseModel
{

    use SoftDeletes;

    protected $table = 'timber_suppliers';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
        ];
    }

    /** @return HasMany<TimberPurchaseRecord, $this> */
    public function purchaseRecords(): HasMany
    {
        return $this->hasMany(TimberPurchaseRecord::class, 'supplier_id');
    }

    /** @return MorphMany<TimberContactNote, $this> */
    public function contactNotes(): MorphMany
    {
        return $this->morphMany(TimberContactNote::class, 'noteable');
    }

    protected static function newFactory(): TimberSupplierFactory
    {
        return TimberSupplierFactory::new();
    }
}
