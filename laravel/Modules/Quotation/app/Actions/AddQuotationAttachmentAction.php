<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Quotation\Models\Quotation;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueueableAction\QueueableAction;

/**
 * Attaches an already-stored file (docx, xlsx, csv, OpenOffice, pdf, image, ...)
 * to a quotation via Spatie MediaLibrary ('documents' collection). The file must
 * already live on the given disk; Spatie moves/copies it into its own storage.
 */
final class AddQuotationAttachmentAction
{
    use QueueableAction;

    public function execute(
        Quotation $quotation,
        string $path,
        string $disk = 'local',
        int|string|null $uploadedByUserId = null,
        ?string $title = null,
    ): Media {
        $cleanTitle = $title !== null ? trim($title) : '';
        $name = $cleanTitle !== '' ? $cleanTitle : pathinfo($path, PATHINFO_FILENAME);

        $adder = $quotation->addMediaFromDisk($path, $disk)->usingName($name);

        if ($uploadedByUserId !== null) {
            $adder = $adder->withCustomProperties(['uploaded_by_user_id' => $uploadedByUserId]);
        }

        return $adder->toMediaCollection('documents');
    }
}
