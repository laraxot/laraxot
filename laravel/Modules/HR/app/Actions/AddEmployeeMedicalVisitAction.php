<?php

declare(strict_types=1);

namespace Modules\HR\Actions;

use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\HR\Models\Employee;
use Modules\HR\Models\EmployeeMedicalVisit;
use Spatie\QueueableAction\QueueableAction;

final class AddEmployeeMedicalVisitAction
{
    use QueueableAction;

    public function __construct(
        private CreateMediaFromStoredFileAction $createMediaFromStoredFile,
    ) {}

    public function execute(
        Employee $employee,
        string $visitedAt,
        ?string $expiresAt = null,
        ?string $notes = null,
        ?string $title = null,
        ?string $attachmentPath = null,
        int|string|null $uploadedByUserId = null,
    ): EmployeeMedicalVisit {
        $mediaId = null;

        if ($attachmentPath) {
            $media = $this->createMediaFromStoredFile->execute(
                mediable: $employee,
                kind: 'document',
                disk: 'public',
                path: $attachmentPath,
                uploadedByUserId: $uploadedByUserId,
                title: $title,
            );
            $mediaId = $media->id;
        }

        /** @var EmployeeMedicalVisit $visit */
        $visit = EmployeeMedicalVisit::query()->create([
            'employee_id' => $employee->id,
            'media_id' => $mediaId,
            'title' => $title,
            'visited_at' => $visitedAt,
            'expires_at' => $expiresAt,
            'notes' => $notes,
        ]);

        return $visit;
    }
}
