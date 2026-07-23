<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Servizio comunale prenotabile (tema Sixteen).
 *
 * @property int $id
 * @property string $name
 * @property bool $is_active
 * @property bool $requires_appointment
 */
class Service extends Model
{
    protected $table = 'sixteen_services';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'is_active',
        'requires_appointment',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
        'requires_appointment' => 'boolean',
    ];

    /**
     * @param  Builder<Service>  $query
     * @return Builder<Service>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  Builder<Service>  $query
     * @return Builder<Service>
     */
    public function scopeRequiresAppointment(Builder $query): Builder
    {
        return $query->where('requires_appointment', true);
    }
}
