<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string|null $brand_code
 * @property string $name
 * @property string|null $manufacturer
 * @property string $source_type
 * @property string|null $version
 * @property Carbon|null $imported_at
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetelCatalog extends BaseModel
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
