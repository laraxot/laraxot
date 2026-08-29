<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPriceListResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Timber\Filament\Resources\TimberPriceListResource;

final class ListTimberPriceLists extends ListRecords
{
    protected static string $resource = TimberPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuovo listino'),
        ];
    }
}
