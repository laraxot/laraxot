<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberDocumentResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberDocumentResource;

final class ListTimberDocuments extends ListRecords
{
    protected static string $resource = TimberDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuovo documento'),
        ];
    }
}
