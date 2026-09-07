<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Pages;

use Filament\Actions;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListComplianceDocuments extends XotBaseListRecords
{
    protected static string $resource = ComplianceDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
