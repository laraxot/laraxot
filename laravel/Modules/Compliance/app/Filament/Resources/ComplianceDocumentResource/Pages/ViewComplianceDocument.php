<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Compliance\Models\ComplianceDocument;

final class ViewComplianceDocument extends ViewRecord
{
    protected static string $resource = ComplianceDocumentResource::class;

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
}
