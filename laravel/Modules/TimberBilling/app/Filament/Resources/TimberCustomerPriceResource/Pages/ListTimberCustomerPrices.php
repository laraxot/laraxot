<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberCustomerPriceResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberCustomerPriceResource;

final class ListTimberCustomerPrices extends ListRecords
{
    protected static string $resource = TimberCustomerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuovo prezzo cliente'),
        ];
    }
}
