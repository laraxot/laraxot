<?php

declare(strict_types=1);

namespace Modules\Geo\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;

/**
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|State newModelQuery()
 * @method static Builder<static>|State newQuery()
 * @method static Builder<static>|State query()
 *
 * @property int $id
 * @property string $state Nome dello stato/regione
 * @property string|null $state_code Codice dello stato/regione
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|State whereCreatedAt($value)
 * @method static Builder<static>|State whereCreatedBy($value)
 * @method static Builder<static>|State whereDeletedAt($value)
 * @method static Builder<static>|State whereDeletedBy($value)
 * @method static Builder<static>|State whereId($value)
 * @method static Builder<static>|State whereState($value)
 * @method static Builder<static>|State whereStateCode($value)
 * @method static Builder<static>|State whereUpdatedAt($value)
 * @method static Builder<static>|State whereUpdatedBy($value)
 *
 * @mixin \Eloquent
 */
class State extends BaseModel
{
    protected $fillable = [
        'state',
        'state_code',
    ];
}
