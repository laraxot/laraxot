<?php

declare(strict_types=1);

namespace Modules\Quotation\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Quotation\Database\Factories\SiteSurveyFactory;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\Traits\HasXotFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Sopralluogo in loco: rilievo con foto, video e note audio da cui nasce il preventivo.
 *
 * Allegati gestiti via Spatie MediaLibrary (collections: photos, videos,
 * voice_notes, documents) — non tramite il morph custom di Documents.
 *
 * @property int $id
 * @property string|null $code
 * @property int $customer_id
 * @property int|null $customer_site_id
 * @property int|null $work_order_id
 * @property int|null $quotation_id
 * @property int|null $performed_by_user_id
 * @property string|null $title
 * @property string|null $notes
 * @property string $status_code
 * @property Carbon|null $scheduled_at
 * @property Carbon|null $performed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class SiteSurvey extends BaseModel implements HasMedia
{
    use HasXotFactory;
    use InteractsWithMedia;
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

    protected static function newFactory(): SiteSurveyFactory
    {
        return SiteSurveyFactory::new();
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

    /** @return Collection<int, Media> */
    public function photos(): Collection
    {
        return $this->getMedia('photos');
    }

    /** @return Collection<int, Media> */
    public function videos(): Collection
    {
        return $this->getMedia('videos');
    }

    /**
     * Note vocali registrate in cantiere: spesso sono l'unica cosa che il tecnico
     * riesce davvero a lasciare mentre ha le mani occupate.
     *
     * @return Collection<int, Media>
     */
    public function voiceNotes(): Collection
    {
        return $this->getMedia('voice_notes');
    }
}
