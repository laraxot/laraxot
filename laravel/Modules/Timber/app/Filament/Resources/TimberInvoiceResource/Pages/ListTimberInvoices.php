<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;

final class ListTimberInvoices extends XotBaseListRecords
{
    protected static string $resource = TimberInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo documento'),
        ];
    }
}
