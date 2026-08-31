<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Spatie\QueueableAction\QueueableAction;

/**
 * Attaches the photos and the customer's finger signature captured alongside a
 * voice report. Photos are appended (never synced/deleted) as they document the
 * work just done; the signature is stored polymorphically on the intervention.
 *
 * @phpstan-type AttachmentResult array{photos: int, signed: bool}
 */
final class SaveInterventionReportAttachmentsAction
{
    use QueueableAction;

    /**
     * @param  list<string>  $photoPaths  Paths already stored on the public disk.
     * @return array{photos: int, signed: bool}
     */
    public function execute(
        Intervention $intervention,
        array $photoPaths,
        ?string $signatureDataUrl,
        int|string $userId,
    ): array {
        $stored = 0;

        foreach ($photoPaths as $rawPath) {
            $path = trim($rawPath);
            if ($path === '' || ! Storage::disk('public')->exists($path)) {
                continue;
            }

            app(CreateMediaFromStoredFileAction::class)->execute(
                mediable: $intervention,
                kind: 'photo',
                disk: 'public',
                path: $path,
                uploadedByUserId: $userId,
                title: null,
                meta: ['scope' => 'voice_report'],
            );

            $stored++;
        }

        $signed = false;
        if ($signatureDataUrl !== null && trim($signatureDataUrl) !== '') {
            app(StoreSignatureForModelAction::class)->execute(
                dataUrl: $signatureDataUrl,
                signableType: Intervention::class,
                signableId: (int) $intervention->id,
                userId: $userId,
            );

            $signed = true;
        }

        return ['photos' => $stored, 'signed' => $signed];
    }
}
