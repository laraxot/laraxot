<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Document\Models\Media;
use Modules\Quotation\Models\Quotation;

/**
 * Attaches an already-stored file (docx, xlsx, csv, OpenOffice, pdf, image, ...)
 * to a quotation as a polymorphic document media record. The file must already
 * live on the given disk; only the media metadata row is created here.
 */
final readonly class AddQuotationAttachmentAction
{
    public function __construct(
        private CreateMediaFromStoredFileAction $createMediaFromStoredFile,
    ) {}

    public function execute(
        Quotation $quotation,
        string $path,
        string $disk = 'local',
        ?int $uploadedByUserId = null,
        ?string $title = null,
    ): Media {
        $cleanTitle = $title !== null ? trim($title) : null;

        return $this->createMediaFromStoredFile->execute(
            mediable: $quotation,
            kind: 'document',
            disk: $disk,
            path: $path,
            uploadedByUserId: $uploadedByUserId,
            title: $cleanTitle !== '' ? $cleanTitle : null,
        );
    }
}
