<?php

declare(strict_types=1);

namespace Modules\Signature\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Signature\Models\Sketch;
use RuntimeException;
use Safe\Exceptions\UrlException;
use Spatie\QueueableAction\QueueableAction;

use function Safe\base64_decode;

final class StoreSketchForModelAction
{
    use QueueableAction;

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

        // Disk/path/mime_type are stored directly on the model instead of going through
        // Spatie MediaLibrary: Spatie Media has NOT-NULL columns incompatible with a raw
        // ::create() call (deliberate adaptation decision, keep it).
        /** @var Sketch $sketch */
        $sketch = Sketch::query()->create([
            'sketchable_type' => $sketchableType,
            'sketchable_id' => $sketchableId,
            'user_id' => $userId,
            'disk' => 'public',
            'path' => $path,
            'mime_type' => 'image/png',
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

        try {
            $binary = base64_decode($base64, true);
        } catch (UrlException $e) {
            throw new RuntimeException('Invalid base64 sketch image data.', previous: $e);
        }

        $fileName = 'sketch-'.now()->format('YmdHis').'-'.bin2hex(random_bytes(6)).'.png';
        $path = trim($directory, '/').'/'.$fileName;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
