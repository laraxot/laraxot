<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Timber\Models\TimberDocument;

final class EditTimberDocument extends EditRecord
{
    protected static string $resource = TimberDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TimberDocumentResource::postToStockAction(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        if ($record instanceof TimberDocument) {
            $record->recalculateTotals();
        }
    }
}
