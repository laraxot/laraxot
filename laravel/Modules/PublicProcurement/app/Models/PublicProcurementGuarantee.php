<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Document\Models\Media;
use Modules\PublicProcurement\Database\Factories\PublicProcurementGuaranteeFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

final class PublicProcurementGuarantee extends XotBaseModel
{
    /**
 * @method static PublicProcurementGuaranteeFactory factory($count = null, $state = [])
 */
    use HasXotFactory;

    protected static function newFactory(): PublicProcurementGuaranteeFactory
    {
        return PublicProcurementGuaranteeFactory::new();
    }

    use SoftDeletes;

    protected $table = 'public_procurement_guarantees';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'issued_on' => 'date',
            'expires_on' => 'date',
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
