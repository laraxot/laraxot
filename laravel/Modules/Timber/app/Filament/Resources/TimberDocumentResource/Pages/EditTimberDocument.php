<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Timber\Models\TimberDocument;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditTimberDocument extends XotBaseEditRecord
{
    protected static string $resource = TimberDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'post_to_stock' => TimberDocumentResource::postToStockAction(),
            'delete' => DeleteAction::make(),
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
