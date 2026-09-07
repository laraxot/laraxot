<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Database\Factories\CustomerFactory;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @property int $id
 * @property string $legal_name
 * @property string|null $vat_number
 * @property string|null $tax_code
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $city
 * @property bool $is_active
 *
 * @method static CustomerFactory factory($count = null, $state = [])
 */
final class Customer extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    /** @return HasMany<CustomerSite, $this> */
    public function sites(): HasMany
    {
        return $this->hasMany(CustomerSite::class);
    }

    /** @return HasMany<Contact, $this> */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /** @return HasMany<WorkOrder, $this> */
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }
}
