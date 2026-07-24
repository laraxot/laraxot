<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Document\Models\Media;
use Modules\Intervention\Models\Intervention;
use Spatie\QueueableAction\QueueableAction;

final class SyncInterventionNotePhotosAction
{
    use QueueableAction;

    /**
     * @param  array<int, string>  $paths
     * @return array<int, Media>
     */
    public function execute(Intervention $intervention, array $paths, ?int $uploadedByUserId = null): array
    {
        $normalizedPaths = collect($paths)
            ->map(static fn (mixed $path): string => trim((string) $path))
            ->filter(static fn (string $path): bool => $path !== '')
            ->unique()
            ->values();

        /** @var Collection<int, Media> $existingMedia */
        $existingMedia = $intervention->media()
            ->where('kind', 'photo')
            ->where('meta->scope', 'intervention_note')
            ->get()
            ->keyBy('path');
        /** @var array<string, Media> $existingMediaByPath */
        $existingMediaByPath = $existingMedia->all();

        foreach ($existingMedia as $media) {
            $storedPath = (string) $media->path;
            if ($normalizedPaths->contains($storedPath)) {
                continue;
            }

            if ($media->disk !== null && $media->path !== null && Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }

            $media->delete();
        }

        foreach ($normalizedPaths as $path) {
            if (isset($existingMediaByPath[$path])) {
                continue;
            }

            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            app(CreateMediaFromStoredFileAction::class)->execute(
                mediable: $intervention,
                kind: 'photo',
                disk: 'public',
                path: $path,
                uploadedByUserId: $uploadedByUserId,
                title: null,
                meta: ['scope' => 'intervention_note'],
            );
        }

        /** @var array<int, Media> $photos */
        $photos = $intervention->media()
            ->where('kind', 'photo')
            ->where('meta->scope', 'intervention_note')
            ->orderBy('id')
            ->get()
            ->all();

        return $photos;
    }
}
