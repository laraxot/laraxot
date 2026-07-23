<?php

declare(strict_types=1);

namespace Modules\Signature\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Signature\Models\Sketch;
use RuntimeException;

final class StoreSketchForModelAction
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(
        string $dataUrl,
        string $sketchableType,
        int $sketchableId,
        int $userId,
        array $payload = [],
    ): Sketch {
        $path = $this->storePngDataUrl($dataUrl, directory: 'media/sketches');

        $media = Media::query()->create([
            'mediable_type' => $sketchableType,
            'mediable_id' => $sketchableId,
            'uploaded_by_user_id' => $userId,
            'kind' => 'sketch',
            'title' => null,
            'disk' => 'public',
            'path' => $path,
            'mime_type' => null,
            'size_bytes' => null,
            'meta' => null,
        ]);

        /** @var Sketch $sketch */
        $sketch = Sketch::query()->create([
            'sketchable_type' => $sketchableType,
            'sketchable_id' => $sketchableId,
            'user_id' => $userId,
            'media_id' => $media->id,
            'payload' => $payload ?: null,
        ]);

        return $sketch;
    }

    private function storePngDataUrl(string $dataUrl, string $directory): string
    {
        if (! str_starts_with($dataUrl, 'data:image/png;base64,')) {
            throw new RuntimeException('Invalid sketch image data URL.');
        }

        $base64 = substr($dataUrl, strlen('data:image/png;base64,'));
        $binary = base64_decode($base64, true);

        if ($binary === false) {
            throw new RuntimeException('Invalid base64 sketch image data.');
        }

        $fileName = 'sketch-' . now()->format('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.png';
        $path = trim($directory, '/') . '/' . $fileName;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}

