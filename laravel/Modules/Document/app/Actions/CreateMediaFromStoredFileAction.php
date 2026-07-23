<?php

declare(strict_types=1);

namespace Modules\Document\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;

final class CreateMediaFromStoredFileAction
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function execute(
        Model $mediable,
        string $kind,
        string $disk,
        string $path,
        ?int $uploadedByUserId = null,
        ?string $title = null,
        array $meta = [],
    ): Media {
        $mimeType = Storage::disk($disk)->mimeType($path);
        $size = Storage::disk($disk)->size($path);

        /** @var Media $media */
        $media = Media::query()->create([
            'mediable_type' => $mediable::class,
            'mediable_id' => $mediable->getKey(),
            'uploaded_by_user_id' => $uploadedByUserId,
            'kind' => $kind,
            'title' => $title,
            'disk' => $disk,
            'path' => $path,
            'mime_type' => $mimeType,
            'size_bytes' => $size,
            'meta' => $meta ?: null,
        ]);

        return $media;
    }
}

