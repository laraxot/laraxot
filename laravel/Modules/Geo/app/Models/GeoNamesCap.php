<?php

declare(strict_types=1);

namespace Modules\Geo\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;

/**
 * Modules\Geo\Models\GeoNamesCap.
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|GeoNamesCap newModelQuery()
 * @method static Builder<static>|GeoNamesCap newQuery()
 * @method static Builder<static>|GeoNamesCap query()
 *
 * @property int $id
 * @property string|null $country_code Codice paese ISO (es. IT)
 * @property string|null $postal_code CAP / codice postale
 * @property string|null $place_name Nome della località
 * @property string|null $admin_name1 Regione
 * @property string|null $admin_code1 Codice regione
 * @property string|null $admin_name2 Provincia
 * @property string|null $admin_code2 Codice provincia
 * @property string|null $admin_name3 Comune
 * @property string|null $admin_code3 Codice comune
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property int|null $accuracy Accuratezza coordinate GeoNames
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|GeoNamesCap whereAccuracy($value)
 * @method static Builder<static>|GeoNamesCap whereAdminCode1($value)
 * @method static Builder<static>|GeoNamesCap whereAdminCode2($value)
 * @method static Builder<static>|GeoNamesCap whereAdminCode3($value)
 * @method static Builder<static>|GeoNamesCap whereAdminName1($value)
 * @method static Builder<static>|GeoNamesCap whereAdminName2($value)
 * @method static Builder<static>|GeoNamesCap whereAdminName3($value)
 * @method static Builder<static>|GeoNamesCap whereCountryCode($value)
 * @method static Builder<static>|GeoNamesCap whereCreatedAt($value)
 * @method static Builder<static>|GeoNamesCap whereCreatedBy($value)
 * @method static Builder<static>|GeoNamesCap whereDeletedAt($value)
 * @method static Builder<static>|GeoNamesCap whereDeletedBy($value)
 * @method static Builder<static>|GeoNamesCap whereId($value)
 * @method static Builder<static>|GeoNamesCap whereLatitude($value)
 * @method static Builder<static>|GeoNamesCap whereLongitude($value)
 * @method static Builder<static>|GeoNamesCap wherePlaceName($value)
 * @method static Builder<static>|GeoNamesCap wherePostalCode($value)
 * @method static Builder<static>|GeoNamesCap whereUpdatedAt($value)
 * @method static Builder<static>|GeoNamesCap whereUpdatedBy($value)
 *
 * @mixin \Eloquent
 */
class GeoNamesCap extends BaseModel
{
    // use Searchable;

    /** @var string */
    protected $table = 'geonames_cap';

    // protected $connection = 'geo';
    /*
     * { function_description }
     *
     */
    /*
     * function __construct(){
     * $this->setConnection('user');
     * parent::__construct();
     * }//end construct
     */
}
