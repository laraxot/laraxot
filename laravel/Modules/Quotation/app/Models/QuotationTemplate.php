<?php

declare(strict_types=1);

namespace Modules\Quotation\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * @property int $id
 * @property string $title
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class QuotationTemplate extends BaseModel
{
    use HasXotFactory;
    use SoftDeletes;

    protected $table = 'quotation_templates';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    /** @return HasMany<QuotationTemplateItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(QuotationTemplateItem::class, 'quotation_template_id');
    }
}
