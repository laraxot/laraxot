<?php

declare(strict_types=1);

namespace Modules\Vehicle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Vehicle extends Model
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

