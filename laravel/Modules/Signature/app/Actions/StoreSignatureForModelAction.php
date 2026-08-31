<?php

declare(strict_types=1);

namespace Modules\Signature\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Modules\Signature\Models\Signature;
use RuntimeException;
use Safe\Exceptions\UrlException;
use Spatie\QueueableAction\QueueableAction;

use function Safe\base64_decode;

final class StoreSignatureForModelAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(
        string $dataUrl,
        string $signableType,
        int|string $signableId,
        int|string $userId,
        ?CarbonImmutable $signedAt = null,
        array $payload = [],
    ): Signature {
        $path = $this->storePngDataUrl($dataUrl, directory: 'media/signatures');

        // Disk/path/mime_type are stored directly on the model instead of going through
        // Spatie MediaLibrary: Spatie Media has NOT-NULL columns incompatible with a raw
        // ::create() call (deliberate adaptation decision, keep it).
        /** @var Signature $signature */
        $signature = Signature::query()->create([
            'signable_type' => $signableType,
            'signable_id' => $signableId,
            'user_id' => $userId,
            'disk' => 'public',
            'path' => $path,
            'mime_type' => 'image/png',
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

        try {
            $binary = base64_decode($base64, true);
        } catch (UrlException $e) {
            throw new RuntimeException('Invalid base64 signature image data.', previous: $e);
        }

        $fileName = 'signature-'.now()->format('YmdHis').'-'.bin2hex(random_bytes(6)).'.png';
        $path = trim($directory, '/').'/'.$fileName;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
