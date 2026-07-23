<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberInvoiceResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberInvoiceResource;

final class ListTimberInvoices extends ListRecords
{
    protected static string $resource = TimberInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuovo documento'),
        ];
    }
}
