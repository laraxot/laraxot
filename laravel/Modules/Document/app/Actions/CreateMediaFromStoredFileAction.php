<?php

declare(strict_types=1);

namespace Modules\Document\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Spatie\QueueableAction\QueueableAction;

final class CreateMediaFromStoredFileAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $meta
     */
    public function execute(
        Model $mediable,
        string $kind,
        string $disk,
        string $path,
        int|string|null $uploadedByUserId = null,
        ?string $title = null,
        array $meta = [],
    ): Media {
        $diskInstance = Storage::disk($disk);
        $mimeType = $diskInstance instanceof FilesystemAdapter ? $diskInstance->mimeType($path) : null;
        $size = $diskInstance->size($path);

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
            'meta' => $meta === [] ? null : $meta,
        ]);

        return $media;
    }
}
