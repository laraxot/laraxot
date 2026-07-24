<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Models\XotBaseModel;

final class PublicProcurementEconomicFrame extends XotBaseModel
{
    protected $table = 'public_procurement_economic_frames';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'works_amount' => 'decimal:2',
            'security_amount' => 'decimal:2',
            'available_sums' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }
}
