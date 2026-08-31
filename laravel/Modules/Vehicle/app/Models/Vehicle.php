<?php

declare(strict_types=1);

namespace Modules\Vehicle\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Vehicle\Database\Factories\VehicleFactory;

/**
 * @property int $id
 * @property string|null $uuid
 * @property string $plate_number
 * @property string|null $make
 * @property string|null $model
 * @property int|null $year
 * @property int|null $odometer_km
 * @property Carbon|null $last_service_on
 * @property Carbon|null $insurance_expires_on
 * @property string|null $notes
 * @property bool $is_active
 * @property Carbon|null $deleted_at
 *
 * @method static VehicleFactory factory($count = null, $state = [])
 */
final class Vehicle extends BaseModel
{

    use SoftDeletes;

    protected $table = 'vehicles';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'odometer_km' => 'integer',
            'last_service_on' => 'date',
            'insurance_expires_on' => 'date',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return HasMany<VehicleDocument, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }
}
