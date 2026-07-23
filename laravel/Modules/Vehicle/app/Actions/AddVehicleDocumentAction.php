<?php

declare(strict_types=1);

namespace Modules\Vehicle\Actions;

use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\Vehicle\Models\Vehicle;
use Modules\Vehicle\Models\VehicleDocument;

final readonly class AddVehicleDocumentAction
{
    public function __construct(
        private CreateMediaFromStoredFileAction $createMediaFromStoredFile,
    ) {
    }

    public function execute(
        Vehicle $vehicle,
        string $path,
        ?int $uploadedByUserId = null,
        ?string $title = null,
    ): VehicleDocument {
        $media = $this->createMediaFromStoredFile->execute(
            mediable: $vehicle,
            kind: 'document',
            disk: 'public',
            path: $path,
            uploadedByUserId: $uploadedByUserId,
            title: $title,
        );

        /** @var VehicleDocument $link */
        $link = VehicleDocument::query()->create([
            'vehicle_id' => $vehicle->id,
            'media_id' => $media->id,
            'title' => $title,
        ]);

        return $link;
    }
}

