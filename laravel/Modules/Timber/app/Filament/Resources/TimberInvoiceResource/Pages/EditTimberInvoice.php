<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Timber\Actions\RecalculateTimberInvoiceTotalsAction;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Timber\Models\TimberInvoice;

final class EditTimberInvoice extends EditRecord
{
    protected static string $resource = TimberInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TimberInvoiceResource::issueAction(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        if ($record instanceof TimberInvoice) {
            app(RecalculateTimberInvoiceTotalsAction::class)->execute($record);
        }
    }
}
