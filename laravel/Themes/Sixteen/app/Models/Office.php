<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

/**
 * Ufficio comunale per prenotazioni (tema Sixteen).
 *
 * @property int $id
 * @property int $service_id
 * @property string $name
 * @property bool $is_active
 * @property Service|null $service
 */
class Office extends Model
{
    protected $table = 'sixteen_offices';

    /** @var list<string> */
    protected $fillable = [
        'service_id',
        'name',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @param  Builder<Office>  $query
     * @return Builder<Office>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @return list<string>
     */
    public function getAvailableDates(int $days = 30): array
    {
        $dates = [];
        $start = Carbon::today();

        for ($i = 1; $i <= $days; $i++) {
            $dates[] = $start->copy()->addDays($i)->toDateString();
        }

        return $dates;
    }

    /**
     * @return list<array{start: string, end: string}>
     */
    public function getAvailableTimeSlots(string $date): array
    {
        unset($date);

        return [
            ['start' => '09:00', 'end' => '09:30'],
            ['start' => '09:30', 'end' => '10:00'],
            ['start' => '10:00', 'end' => '10:30'],
            ['start' => '11:00', 'end' => '11:30'],
        ];
    }

    public function isSlotAvailable(string $date, string $start): bool
    {
        $dateString = SafeStringCastAction::cast($date);
        $startTime = SafeStringCastAction::cast($start);

        if ($dateString === '' || $startTime === '') {
            return false;
        }

        foreach ($this->getAvailableTimeSlots($dateString) as $slot) {
            if ($slot['start'] === $startTime) {
                return true;
            }
        }

        return false;
    }
}
