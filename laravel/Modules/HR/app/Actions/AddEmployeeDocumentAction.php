<?php

declare(strict_types=1);

namespace Modules\HR\Actions;

use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\HR\Models\Employee;
use Modules\HR\Models\EmployeeDocument;
use Spatie\QueueableAction\QueueableAction;

final class AddEmployeeDocumentAction
{
    use QueueableAction;

    public function __construct(
        private CreateMediaFromStoredFileAction $createMediaFromStoredFile,
    ) {}

    public function execute(
        Employee $employee,
        string $path,
        int|string|null $uploadedByUserId = null,
        ?string $title = null,
        ?string $kind = null,
    ): EmployeeDocument {
        $media = $this->createMediaFromStoredFile->execute(
            mediable: $employee,
            kind: $kind ?: 'document',
            disk: 'public',
            path: $path,
            uploadedByUserId: $uploadedByUserId,
            title: $title,
        );

        /** @var EmployeeDocument $link */
        $link = EmployeeDocument::query()->create([
            'employee_id' => $employee->id,
            'media_id' => $media->id,
            'title' => $title,
            'kind' => $kind,
        ]);

        return $link;
    }
}
