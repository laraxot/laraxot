<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerSupplyPointFactory;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property string $service_type
 * @property string|null $pod
 * @property string|null $pdr
 * @property string|null $supply_code
 * @property string|null $supplier_name
 * @property string|null $offer_name
 * @property string|null $annual_consumption
 * @property string|null $power_kw
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class EnergyBrokerSupplyPoint extends BaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_supply_points';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'annual_consumption' => 'decimal:3',
            'power_kw' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return BelongsTo<CustomerSite, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(CustomerSite::class, 'customer_site_id');
    }

    /** @return HasMany<EnergyBrokerSupplyHistory, $this> */
    public function histories(): HasMany
    {
        return $this->hasMany(EnergyBrokerSupplyHistory::class, 'energy_broker_supply_point_id');
    }

    public static function factory(): EnergyBrokerSupplyPointFactory
    {
        return EnergyBrokerSupplyPointFactory::new();
    }

    protected static function newFactory(): EnergyBrokerSupplyPointFactory
    {
        return EnergyBrokerSupplyPointFactory::new();
    }
}
