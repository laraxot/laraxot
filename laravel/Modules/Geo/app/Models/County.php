<?php

declare(strict_types=1);

namespace Modules\Geo\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;

/**
 * Suddivisione tipo “county” (contesto USA / geonames), non il comune italiano.
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|County newModelQuery()
 * @method static Builder<static>|County newQuery()
 * @method static Builder<static>|County query()
 *
 * @property int $id
 * @property int|null $state_id Stato/regione di appartenenza
 * @property string $county Nome della suddivisione (county/provincia)
 * @property string|null $county_code Codice della suddivisione
 * @property int|null $state_index Indice progressivo entro lo stato
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|County whereCounty($value)
 * @method static Builder<static>|County whereCountyCode($value)
 * @method static Builder<static>|County whereCreatedAt($value)
 * @method static Builder<static>|County whereCreatedBy($value)
 * @method static Builder<static>|County whereDeletedAt($value)
 * @method static Builder<static>|County whereDeletedBy($value)
 * @method static Builder<static>|County whereId($value)
 * @method static Builder<static>|County whereStateId($value)
 * @method static Builder<static>|County whereStateIndex($value)
 * @method static Builder<static>|County whereUpdatedAt($value)
 * @method static Builder<static>|County whereUpdatedBy($value)
 *
 * @mixin \Eloquent
 */
class County extends BaseModel
{
    protected $fillable = [
        'state_id',
        'county',
        'state_index',
    ];
}
