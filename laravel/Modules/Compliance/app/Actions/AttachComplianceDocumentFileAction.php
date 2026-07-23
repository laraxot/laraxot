<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Document\Models\Media;

final readonly class AttachComplianceDocumentFileAction
{
    public function __construct(
        private CreateMediaFromStoredFileAction $createMediaFromStoredFile,
    ) {}

    public function execute(
        ComplianceDocument $document,
        string $path,
        ?int $uploadedByUserId = null,
    ): ?Media {
        $path = trim($path);
        if ($path === '' || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        if ($document->media instanceof Media && $document->media->path === $path) {
            return $document->media;
        }

        $media = $this->createMediaFromStoredFile->execute(
            mediable: $document,
            kind: 'document',
            disk: 'public',
            path: $path,
            uploadedByUserId: $uploadedByUserId,
            title: $document->title,
            meta: ['compliance_type' => $document->type],
        );

        $document->forceFill([
            'media_id' => $media->id,
            'uploaded_by_user_id' => $uploadedByUserId,
            'status' => $document->status === 'missing' ? 'valid' : $document->status,
        ])->save();

        return $media;
    }
}
