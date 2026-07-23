<?php

declare(strict_types=1);

namespace Modules\Signature\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Signature\Models\Signature;
use RuntimeException;

final class StoreSignatureForModelAction
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(
        string $dataUrl,
        string $signableType,
        int $signableId,
        int $userId,
        ?CarbonImmutable $signedAt = null,
        array $payload = [],
    ): Signature {
        $path = $this->storePngDataUrl($dataUrl, directory: 'media/signatures');

        $media = Media::query()->create([
            'mediable_type' => $signableType,
            'mediable_id' => $signableId,
            'uploaded_by_user_id' => $userId,
            'kind' => 'signature',
            'title' => null,
            'disk' => 'public',
            'path' => $path,
            'mime_type' => null,
            'size_bytes' => null,
            'meta' => null,
        ]);

        /** @var Signature $signature */
        $signature = Signature::query()->create([
            'signable_type' => $signableType,
            'signable_id' => $signableId,
            'user_id' => $userId,
            'media_id' => $media->id,
            'signed_at' => ($signedAt ?? CarbonImmutable::now()),
            'payload' => $payload ?: null,
        ]);

        return $signature;
    }

    private function storePngDataUrl(string $dataUrl, string $directory): string
    {
        if (! str_starts_with($dataUrl, 'data:image/png;base64,')) {
            throw new RuntimeException('Invalid signature image data URL.');
        }

        $base64 = substr($dataUrl, strlen('data:image/png;base64,'));
        $binary = base64_decode($base64, true);

        if ($binary === false) {
            throw new RuntimeException('Invalid base64 signature image data.');
        }

        $fileName = 'signature-' . now()->format('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.png';
        $path = trim($directory, '/') . '/' . $fileName;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}

