<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicProcurement\Database\Factories\PublicProcurementContactFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

final class PublicProcurementContact extends XotBaseModel
{
    use HasXotFactory;
    use SoftDeletes;

    protected $table = 'public_procurement_contacts';

    /** @var array<int, string> */
    protected $guarded = [];

    protected static function newFactory(): PublicProcurementContactFactory
    {
        return PublicProcurementContactFactory::new();
    }

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
