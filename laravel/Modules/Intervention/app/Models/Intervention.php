<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Document\Models\Media;
use Modules\Intervention\Database\Factories\InterventionFactory;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Signature\Models\Signature;
use Modules\Signature\Models\Sketch;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * @property int $id
 * @property int|null $work_order_id
 * @property int|null $customer_id
 * @property string|null $performed_by_user_id
 * @property string|null $validated_by_user_id
 * @property Carbon|null $scheduled_at
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 * @property int|null $travel_km
 * @property int|null $travel_minutes
 * @property int|null $work_minutes
 * @property numeric-string|float|null $amount
 * @property numeric-string|float|null $callout_fee
 * @property numeric-string|float|null $materials_total
 * @property numeric-string|float|null $total_amount
 * @property string|null $report_payment_session_id
 * @property Carbon|null $report_paid_at
 * @property string|null $notes
 * @property string|null $notes_html
 * @property string|null $google_event_id
 * @property string|null $google_calendar_id
 * @property string|null $google_sync_status
 * @property Carbon|null $google_synced_at
 * @property string|null $outcome_code
 * @property Carbon|null $validated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, InventoryMovement> $inventoryMovements
 * @property-read Collection<int, InterventionMaterialLine> $materialLines
 * @property-read Collection<int, User> $assignedUsers
 * @property-read Collection<int, TimePunch> $timePunches
 * @property-read Collection<int, IssueReport> $issueReports
 * @property-read Collection<int, Media> $media
 * @property-read Collection<int, Signature> $signatures
 * @property-read Collection<int, Sketch> $sketches
 * @property-read User|null $performedBy
 * @property-read WorkOrder|null $workOrder
 * @property-read Customer|null $customer
 */
final class Intervention extends BaseModel
{
    use HasXotFactory;

    use SoftDeletes;

    protected $table = 'interventions';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'travel_km' => 'integer',
            'travel_minutes' => 'integer',
            'work_minutes' => 'integer',
            'amount' => 'decimal:2',
            'callout_fee' => 'decimal:2',
            'materials_total' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'report_paid_at' => 'datetime',
            'validated_at' => 'datetime',
            'google_synced_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }

    /**
     * belongsToManyX: pivot InterventionUser auto-scoperto, gestisce il
     * cross-connection intervention↔user (belongsToMany nudo perdeva
     * l'accessor pivot in eager load).
     *
     * @return BelongsToMany<User, $this, Pivot, 'pivot'>
     */
    public function assignedUsers(): BelongsToMany
    {
        // Ordine esplicito per nome: finche' gli id erano interi sequenziali
        // l'ordine di inserimento reggeva per caso, con gli UUID non piu'. Chi
        // legge il rapporto si aspetta una lista stabile, non casuale.
        return $this->belongsToManyX(User::class)->orderBy('users.name');
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return HasMany<TimePunch, $this> */
    public function timePunches(): HasMany
    {
        return $this->hasMany(TimePunch::class);
    }

    /** @return HasMany<IssueReport, $this> */
    public function issueReports(): HasMany
    {
        return $this->hasMany(IssueReport::class);
    }

    /** @return HasMany<InventoryMovement, $this> */
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /** @return HasMany<InterventionMaterialLine, $this> */
    public function materialLines(): HasMany
    {
        return $this->hasMany(InterventionMaterialLine::class, 'intervention_id');
    }

    /** @return MorphMany<Media, $this> */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /** @return MorphMany<Signature, $this> */
    public function signatures(): MorphMany
    {
        return $this->morphMany(Signature::class, 'signable');
    }

    /** @return MorphMany<Sketch, $this> */
    public function sketches(): MorphMany
    {
        return $this->morphMany(Sketch::class, 'sketchable');
    }

    public function getRenderedNotesHtml(): string
    {
        return $this->notes_html
            ? DailyNoteHtml::sanitize((string) $this->notes_html)
            : DailyNoteHtml::legacyToHtml((string) $this->notes);
    }

    public function refreshEconomicTotals(): void
    {
        $rawInventoryMaterials = $this->inventoryMovements()
            ->where('movement_type', 'scarico')
            ->sum('line_total');
        $inventoryMaterialsTotal = is_numeric($rawInventoryMaterials) ? (float) $rawInventoryMaterials : 0.0;

        $rawManualMaterials = $this->materialLines()->sum('line_total');
        $manualMaterialsTotal = is_numeric($rawManualMaterials) ? (float) $rawManualMaterials : 0.0;

        $materialsTotal = round(max($inventoryMaterialsTotal, $manualMaterialsTotal), 2);
        $laborAmount = is_numeric($this->amount) ? (float) $this->amount : 0.0;
        $calloutFee = is_numeric($this->callout_fee) ? (float) $this->callout_fee : 0.0;

        $this->forceFill([
            'materials_total' => $materialsTotal,
            'total_amount' => round($laborAmount + $calloutFee + $materialsTotal, 2),
        ])->saveQuietly();
    }
}
