<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Subcontractor extends BaseModel
{
    use SoftDeletes;

    protected $table = 'subcontractors';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    /** @return HasMany<ComplianceDocument, $this> */
    public function complianceDocuments(): HasMany
    {
        return $this->hasMany(ComplianceDocument::class);
    }
}
