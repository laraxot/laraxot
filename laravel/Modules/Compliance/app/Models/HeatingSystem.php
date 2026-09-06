<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Compliance\Database\Factories\HeatingSystemFactory;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Xot\Actions\Factory\GetFactoryAction;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string|null $code
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property string|null $description
 * @property string|null $fuel_type
 * @property string|null $total_power_kw
 * @property string|null $plant_registry_code
 * @property CarbonInterface|null $installation_date
 * @property string|null $notes
 * @property bool $is_active
 */
final class HeatingSystem extends XotBaseModel
{
    use HasXotFactory;
    use SoftDeletes;

    protected static function newFactory(): HeatingSystemFactory
    {
        return HeatingSystemFactory::new();
    }

    protected $table = 'heating_systems';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'total_power_kw' => 'decimal:2',
            'installation_date' => 'date',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return BelongsTo<CustomerSite, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(CustomerSite::class, 'customer_site_id');
    }

    /** @return HasMany<Boiler, $this> */
    public function boilers(): HasMany
    {
        return $this->hasMany(Boiler::class);
    }

    /** @return HasMany<MaintenanceControl, $this> */
    public function controls(): HasMany
    {
        return $this->hasMany(MaintenanceControl::class);
    }
}
