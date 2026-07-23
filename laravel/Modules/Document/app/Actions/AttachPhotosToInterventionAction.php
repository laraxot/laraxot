<?php

declare(strict_types=1);

namespace Modules\Document\Actions;

use Modules\Document\Models\Media;
use Modules\Intervention\Models\Intervention;

final class AttachPhotosToInterventionAction
{
    /**
     * @param  array<int, string>  $paths
     * @return array<int, Media>
     */
    public function execute(Intervention $intervention, array $paths, ?int $uploadedByUserId = null): array
    {
        $created = [];

        foreach ($paths as $path) {
            $created[] = app(CreateMediaFromStoredFileAction::class)->execute(
                mediable: $intervention,
                kind: 'photo',
                disk: 'public',
                path: $path,
                uploadedByUserId: $uploadedByUserId,
                title: null,
                meta: [],
            );
        }

        return $created;
    }
}

