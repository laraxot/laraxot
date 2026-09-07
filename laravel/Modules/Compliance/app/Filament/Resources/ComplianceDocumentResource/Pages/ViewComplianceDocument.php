<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Pages;

use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewComplianceDocument extends XotBaseViewRecord
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
