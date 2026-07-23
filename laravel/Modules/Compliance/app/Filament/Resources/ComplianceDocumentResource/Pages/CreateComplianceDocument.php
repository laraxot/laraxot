<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Modules\Compliance\Actions\AttachComplianceDocumentFileAction;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Compliance\Models\ComplianceDocument;

final class CreateComplianceDocument extends CreateRecord
{
    protected static string $resource = ComplianceDocumentResource::class;

    private ?string $filePath = null;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->filePath = isset($data['file_path']) ? (string) $data['file_path'] : null;
        unset($data['file_path']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (! $this->record instanceof ComplianceDocument || $this->filePath === null) {
            return;
        }

        app(AttachComplianceDocumentFileAction::class)->execute(
            document: $this->record,
            path: $this->filePath,
            uploadedByUserId: Auth::id(),
        );
    }
}
