<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Document\Models\Media;

final class PublicProcurementAiExtraction extends Model
{
    use SoftDeletes;

    protected $table = 'public_procurement_ai_extractions';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'confidence' => 'decimal:2',
            'extracted_payload' => 'array',
            'expires_at' => 'datetime',
            'applied_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Media, $this> */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
