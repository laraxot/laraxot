<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Intervention\Database\Factories\InterventionReportShareFactory;

/**
 * A tokenized, optionally-expiring public link to an intervention's report PDF.
 *
 * @property int $id
 * @property int $intervention_id
 * @property string $token
 * @property int|null $created_by_user_id
 * @property CarbonImmutable|null $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 *
 * @method static InterventionReportShareFactory factory($count = null, $state = [])
 */
final class InterventionReportShare extends BaseModel
{
    /** @use HasFactory<InterventionReportShareFactory> */
    use HasFactory;

    protected $table = 'intervention_report_shares';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'expires_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'intervention_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function publicUrl(): string
    {
        return route('interventions.report.public', ['token' => $this->token]);
    }
}
