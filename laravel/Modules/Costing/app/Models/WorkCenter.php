<?php

declare(strict_types=1);

namespace Modules\Costing\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * Un centro di lavoro di un modulo di preventivazione: dove si esegue una riga di
 * distinta e a che margine si vende. Interrogarlo sempre da {@see scopeForContext()}
 * — la tabella e' condivisa e una lettura senza filtro mescolerebbe le officine di
 * due aziende diverse.
 *
 * @property int $id
 * @property string $context_code
 * @property string $code
 * @property string $name
 * @property bool $is_internal
 * @property numeric-string $hourly_rate
 * @property numeric-string|null $default_markup_percent
 * @property int $sort_order
 * @property bool $is_active
 */
final class WorkCenter extends BaseModel
{
    use HasXotFactory;

    protected $table = 'costing_work_centers';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
            'is_active' => 'boolean',
            'hourly_rate' => 'decimal:2',
            'default_markup_percent' => 'decimal:2',
        ];
    }

    /**
     * @param  Builder<WorkCenter>  $query
     * @return Builder<WorkCenter>
     */
    public function scopeForContext(Builder $query, string $contextCode): Builder
    {
        return $query->where('context_code', $contextCode);
    }

    /** Costo di `$minutes` minuti di questo centro. */
    public function costOfMinutes(float $minutes): float
    {
        return round($minutes / 60 * (float) $this->hourly_rate, 2);
    }
}
