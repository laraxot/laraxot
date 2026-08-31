<?php

declare(strict_types=1);

namespace Modules\Tenant\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property string|null $tenant_id
 * @property string|null $plan_name
 * @property string|null $status
 * @property int|null $max_users
 * @property int|null $current_users
 * @property int|null $max_storage_gb
 * @property int|null $current_storage_gb
 * @property string|null $billing_cycle
 * @property float|null $billing_amount
 * @property Carbon|null $next_billing_date
 * @property Carbon|null $expires_at
 * @property-read User|null $creator
 * @property-read Tenant|null $tenant
 * @property-read User|null $updater
 *
 * @method static Builder<static>|TenantSubscription newModelQuery()
 * @method static Builder<static>|TenantSubscription newQuery()
 * @method static Builder<static>|TenantSubscription query()
 *
 * @mixin \Eloquent
 */
class TenantSubscription extends BaseModel
{
    protected $fillable = [
        'tenant_id',
        'plan_name',
        'status',
        'max_users',
        'current_users',
        'max_storage_gb',
        'current_storage_gb',
        'billing_cycle',
        'billing_amount',
        'next_billing_date',
        'expires_at',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'next_billing_date' => 'datetime',
            'expires_at' => 'datetime',
        ]);
    }
}
