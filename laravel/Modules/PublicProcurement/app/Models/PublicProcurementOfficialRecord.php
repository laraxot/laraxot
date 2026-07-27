<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Document\Models\Media;
use Modules\PublicProcurement\Database\Factories\PublicProcurementOfficialRecordFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static PublicProcurementOfficialRecordFactory factory($count = null, $state = [])
 */
final class PublicProcurementOfficialRecord extends XotBaseModel
{
    /** @phpstan-use HasXotFactory<PublicProcurementOfficialRecordFactory> */
    use HasXotFactory;

    use SoftDeletes;

    protected $table = 'public_procurement_official_records';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }

    /** @return BelongsTo<Media, $this> */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
