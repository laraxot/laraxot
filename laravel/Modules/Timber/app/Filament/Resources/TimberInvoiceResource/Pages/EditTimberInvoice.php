<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Modules\Timber\Actions\RecalculateTimberInvoiceTotalsAction;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Timber\Models\TimberInvoice;

final class EditTimberInvoice extends XotBaseEditRecord
{
    protected static string $resource = TimberInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'issue' => TimberInvoiceResource::issueAction(),
            'delete' => DeleteAction::make(),
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
