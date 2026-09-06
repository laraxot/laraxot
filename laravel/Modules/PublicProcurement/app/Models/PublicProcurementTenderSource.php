<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicProcurement\Database\Factories\PublicProcurementTenderSourceFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

final class PublicProcurementTenderSource extends XotBaseModel
{
    /**
 * @method static PublicProcurementTenderSourceFactory factory($count = null, $state = [])
 */
    use HasXotFactory;

    protected static function newFactory(): PublicProcurementTenderSourceFactory
    {
        return PublicProcurementTenderSourceFactory::new();
    }

    use SoftDeletes;

    protected $table = 'public_procurement_tender_sources';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'filters' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return HasMany<PublicProcurementTenderOpportunity, $this> */
    public function opportunities(): HasMany
    {
        return $this->hasMany(PublicProcurementTenderOpportunity::class, 'tender_source_id');
    }
}
