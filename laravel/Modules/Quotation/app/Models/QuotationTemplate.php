<?php

declare(strict_types=1);

namespace Modules\Quotation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class QuotationTemplate extends Model
{
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

