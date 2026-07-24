<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Document\Models\Media;
use Modules\User\Models\User;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string $public_id
 * @property int|null $media_id
 * @property int|null $created_by_user_id
 * @property string $document_type
 * @property string $status
 * @property string $confidence
 * @property array<string, mixed>|null $extracted_payload
 * @property string|null $raw_text
 * @property Carbon|null $expires_at
 * @property Carbon|null $applied_at
 * @property Carbon|null $deleted_at
 */
final class PublicProcurementAiExtraction extends XotBaseModel
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
