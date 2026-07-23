<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberInvoiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Actions\RecalculateTimberInvoiceTotalsAction;
use Modules\TimberBilling\Filament\Resources\TimberInvoiceResource;
use Modules\TimberBilling\Models\TimberInvoice;

final class CreateTimberInvoice extends CreateRecord
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
