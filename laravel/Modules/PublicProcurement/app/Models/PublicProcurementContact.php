<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicProcurement\Database\Factories\PublicProcurementContactFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @method static PublicProcurementContactFactory factory($count = null, $state = [])
 */
final class PublicProcurementContact extends XotBaseModel
{
    /** @phpstan-use HasXotFactory<PublicProcurementContactFactory> */
    use HasXotFactory;

    use SoftDeletes;

    protected $table = 'public_procurement_contacts';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }
}
