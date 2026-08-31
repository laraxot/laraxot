<?php

declare(strict_types=1);

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Database\Factories\MediaFactory;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property string $mediable_type
 * @property int $mediable_id
 * @property string|null $uploaded_by_user_id
 * @property string $kind
 * @property string|null $title
 * @property string $disk
 * @property string $path
 * @property string|null $mime_type
 * @property int|null $size_bytes
 * @property array<string, mixed>|null $meta
 *
 * @method static \Modules\Document\Database\Factories\MediaFactory factory($count = null, $state = [])
 */
final class Media extends BaseModel
{

    use SoftDeletes;

    protected $table = 'document_media';

    /** @var array<int, string> */
    protected $guarded = [];

    protected static function newFactory(): MediaFactory
    {
        return MediaFactory::new();
    }

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
        self::saving(static function (self $media): void {
            $diskName = $media->disk;
            $path = $media->path;
            if (! is_string($diskName) || $diskName === '' || ! is_string($path) || $path === '') {
                return;
            }

            /** @var FilesystemAdapter $disk */
            $disk = Storage::disk($diskName);

            if (! $disk->exists($path)) {
                return;
            }

            if ($media->mime_type === null || $media->mime_type === '') {
                $mime = $disk->mimeType($path);
                $media->mime_type = is_string($mime) ? $mime : null;
            }

            if ($media->size_bytes === null) {
                $size = $disk->size($path);
                $media->size_bytes = max(0, (int) $size);
            }
        });
    }
}
