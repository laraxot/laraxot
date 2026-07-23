<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Compliance\Actions\EvaluateWorkOrderReadinessAction;
use Modules\Customer\Models\Customer;
use Modules\Document\Models\Media;
use Modules\HR\Models\Employee;
use Modules\Vehicle\Models\Vehicle;
use Modules\WorkOrder\Models\WorkOrder;

final class ComplianceDocument extends Model
{
    use SoftDeletes;

    protected $table = 'compliance_documents';

    /** @var array<int, string> */
    protected $guarded = [];

    protected static function booted(): void
    {
        self::saved(static function (ComplianceDocument $document): void {
            $document->refreshWorkOrderReadiness();
        });

        self::deleted(static function (ComplianceDocument $document): void {
            $document->refreshWorkOrderReadiness();
        });

        self::restored(static function (ComplianceDocument $document): void {
            $document->refreshWorkOrderReadiness();
        });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'issued_on' => 'date',
            'expires_on' => 'date',
            'ai_classification' => 'array',
            'ai_extracted_fields' => 'array',
            'ai_processed_at' => 'datetime',
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    public function computedStatus(): string
    {
        if ($this->status === 'waived' || $this->is_required === false) {
            return 'waived';
        }

        if ($this->expires_on !== null && $this->expires_on->isPast()) {
            return 'expired';
        }

        if ($this->expires_on !== null && $this->expires_on->isBetween(now(), now()->addDays(30))) {
            return 'expiring';
        }

        return $this->status === 'missing' && $this->media_id !== null ? 'valid' : (string) $this->status;
    }

    public function refreshWorkOrderReadiness(): void
    {
        if ($this->work_order_id === null) {
            return;
        }

        /** @var WorkOrder|null $workOrder */
        $workOrder = WorkOrder::query()->find($this->work_order_id);
        if (! $workOrder instanceof WorkOrder) {
            return;
        }

        app(EvaluateWorkOrderReadinessAction::class)->execute($workOrder);
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return BelongsTo<Employee, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return BelongsTo<Subcontractor, $this> */
    public function subcontractor(): BelongsTo
    {
        return $this->belongsTo(Subcontractor::class);
    }

    /** @return BelongsTo<Equipment, $this> */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /** @return BelongsTo<Media, $this> */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /** @return BelongsTo<User, $this> */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
