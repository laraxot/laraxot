<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Xot\Models\XotBaseModel;

final class EnergyBrokerSupplyPoint extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_supply_points';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'annual_consumption' => 'decimal:3',
            'power_kw' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(CustomerSite::class, 'customer_site_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(EnergyBrokerSupplyHistory::class, 'energy_broker_supply_point_id');
    }
}
