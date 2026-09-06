<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;
use Modules\Wts\Concerns\WtsAuditable;
use Modules\Wts\Database\Factories\WtsNonConformityReportFactory;

/**
 * @property int $id
 * @property int|null $wts_inbound_lot_id
 * @property int|null $wts_inbound_record_id
 * @property string|null $form_number
 * @property string $note
 * @property string|null $recipient_email
 * @property string|null $reported_by_user_id
 * @property CarbonInterface $reported_at
 * @property CarbonInterface|null $email_sent_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsNonConformityReport extends BaseModel
{
    use SoftDeletes;
    use WtsAuditable;

    protected $table = 'wts_non_conformity_reports';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'email_sent_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WtsInboundLot, $this> */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(WtsInboundLot::class, 'wts_inbound_lot_id');
    }

    /** @return BelongsTo<WtsInboundRecord, $this> */
    public function inboundRecord(): BelongsTo
    {
        return $this->belongsTo(WtsInboundRecord::class, 'wts_inbound_record_id');
    }

    /** @return BelongsTo<User, $this> */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    protected static function newFactory(): WtsNonConformityReportFactory
    {
        return WtsNonConformityReportFactory::new();
    }
}
