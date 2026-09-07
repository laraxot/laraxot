<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Pages;

use Illuminate\Support\Facades\Auth;
use Modules\Compliance\Actions\AttachComplianceDocumentFileAction;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditComplianceDocument extends XotBaseEditRecord
{
    protected static string $resource = ComplianceDocumentResource::class;

    private ?string $filePath = null;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof ComplianceDocument && $this->record->media !== null) {
            $data['file_path'] = $this->record->media->path;
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->filePath = is_string($data['file_path'] ?? null) ? $data['file_path'] : null;
        unset($data['file_path']);

        return $data;
    }

    protected function afterSave(): void
    {
        if (! $this->record instanceof ComplianceDocument || $this->filePath === null) {
            return;
        }

        $userId = Auth::id();

        app(AttachComplianceDocumentFileAction::class)->execute(
            document: $this->record,
            path: $this->filePath,
            uploadedByUserId: is_int($userId) ? $userId : null,
        );
    }
}
