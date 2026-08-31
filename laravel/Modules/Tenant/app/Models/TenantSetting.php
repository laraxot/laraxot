<?php

declare(strict_types=1);

namespace Modules\Tenant\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

/**
 * @property string|null $tenant_id
 * @property string|null $key
 * @property mixed $value
 * @property string|null $type
 * @property-read User|null $creator
 * @property-read Tenant|null $tenant
 * @property-read User|null $updater
 *
 * @method static Builder<static>|TenantSetting newModelQuery()
 * @method static Builder<static>|TenantSetting newQuery()
 * @method static Builder<static>|TenantSetting query()
 *
 * @mixin \Eloquent
 */
class TenantSetting extends BaseModel
{
    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'type',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
