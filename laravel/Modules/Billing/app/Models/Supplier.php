<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string $legal_name
 * @property string|null $vat_number
 * @property string|null $email
 * @property string|null $phone
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static \Modules\Billing\Database\Factories\SupplierFactory factory($count = null, $state = [])
 */
final class Supplier extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'suppliers';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /** @return HasMany<DeliveryNote, $this> */
    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class, 'supplier_id');
    }

    /** @return HasMany<SupplierInvoice, $this> */
    public function invoices(): HasMany
    {
        return $this->hasMany(SupplierInvoice::class, 'supplier_id');
    }
}
