<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;

final class ListTimberCustomerPrices extends XotBaseListRecords
{
    protected static string $resource = TimberCustomerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo prezzo cliente'),
        ];
    }
}
