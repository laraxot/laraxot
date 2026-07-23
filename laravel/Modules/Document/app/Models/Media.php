<?php

declare(strict_types=1);

namespace Modules\Document\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

final class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return MorphTo<Model, $this> */
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<User, $this> */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    protected static function booted(): void
    {
        static::saving(static function (self $media): void {
            if (! $media->disk || ! $media->path) {
                return;
            }

            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk($media->disk);

            if (! $disk->exists($media->path)) {
                return;
            }

            if (! $media->mime_type) {
                $mime = $disk->mimeType($media->path);
                $media->mime_type = is_string($mime) ? $mime : null;
            }

            if ($media->size_bytes === null) {
                $size = $disk->size($media->path);
                $media->size_bytes = max(0, (int) $size);
            }
        });
    }
}

