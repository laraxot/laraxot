<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Pages;

use Filament\Actions;
use Modules\Billing\Filament\Resources\InvoiceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListInvoices extends XotBaseListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
