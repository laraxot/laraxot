<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Signage\Filament\Resources\SignPriceListResource;

final class ListSignPriceLists extends ListRecords
{
    protected static string $resource = SignPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
