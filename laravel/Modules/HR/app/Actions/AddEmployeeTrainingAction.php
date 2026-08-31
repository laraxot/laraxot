<?php

declare(strict_types=1);

namespace Modules\HR\Actions;

use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\HR\Models\Employee;
use Modules\HR\Models\EmployeeTraining;
use Spatie\QueueableAction\QueueableAction;

final class AddEmployeeTrainingAction
{
    use QueueableAction;

    public function __construct(
        private CreateMediaFromStoredFileAction $createMediaFromStoredFile,
    ) {}

    public function execute(
        Employee $employee,
        string $name,
        ?string $completedAt = null,
        ?string $expiresAt = null,
        ?string $notes = null,
        ?string $title = null,
        ?string $attachmentPath = null,
        int|string|null $uploadedByUserId = null,
    ): EmployeeTraining {
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

        /** @var EmployeeTraining $training */
        $training = EmployeeTraining::query()->create([
            'employee_id' => $employee->id,
            'media_id' => $mediaId,
            'title' => $title,
            'name' => $name,
            'completed_at' => $completedAt,
            'expires_at' => $expiresAt,
            'notes' => $notes,
        ]);

        return $training;
    }
}
