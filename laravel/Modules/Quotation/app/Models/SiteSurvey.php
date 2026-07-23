<?php

declare(strict_types=1);

namespace Modules\Quotation\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Document\Models\Media;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * Sopralluogo in loco: rilievo con foto, video e note audio da cui nasce il preventivo.
 */
final class SiteSurvey extends Model
{
    use SoftDeletes;

    protected $table = 'site_surveys';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'performed_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return BelongsTo<CustomerSite, $this> */
    public function customerSite(): BelongsTo
    {
        return $this->belongsTo(CustomerSite::class, 'customer_site_id');
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return BelongsTo<Quotation, $this> */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /** @return BelongsTo<User, $this> */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }

    /** @return MorphMany<Media, $this> */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /** @return MorphMany<Media, $this> */
    public function photos(): MorphMany
    {
        return $this->media()->where('kind', 'photo');
    }

    /** @return MorphMany<Media, $this> */
    public function videos(): MorphMany
    {
        return $this->media()->where('kind', 'video');
    }

    /**
     * Note vocali registrate in cantiere: spesso sono l'unica cosa che il tecnico
     * riesce davvero a lasciare mentre ha le mani occupate.
     *
     * @return MorphMany<Media, $this>
     */
    public function voiceNotes(): MorphMany
    {
        return $this->media()->where('kind', 'audio');
    }
}
