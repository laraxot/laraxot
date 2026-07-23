<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class MetelCatalog extends Model
{
    protected $table = 'metel_catalogs';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'imported_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<MetelCatalogItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(MetelCatalogItem::class);
    }
}
