<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Document\Models\Media;

/**
 * Impianto installato presso un cliente (videosorveglianza, quadro elettrico, fotovoltaico).
 * Gli schemi/allegati sono {@see Media} polimorfici (kind = "schema").
 *
 * @property int $id
 * @property string|null $code
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property string $system_type
 * @property string|null $description
 * @property string|null $brand
 * @property string|null $model
 * @property string|null $serial_number
 * @property string|null $power_kw
 * @property CarbonInterface|null $installation_date
 * @property bool $is_active
 * @property string|null $notes
 */
final class CustomerSystem extends BaseModel
{
    use SoftDeletes;

    protected $table = 'customer_systems';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'power_kw' => 'decimal:2',
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

    /** @return MorphMany<Media, $this> */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
