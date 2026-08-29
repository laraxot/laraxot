<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\RecalculateTimberInvoiceTotalsAction;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Timber\Models\TimberInvoice;

final class CreateTimberInvoice extends XotBaseCreateRecord
{
    protected static string $resource = TimberInvoiceResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        if ($record instanceof TimberInvoice) {
            app(RecalculateTimberInvoiceTotalsAction::class)->execute($record);
        }
    }
}
