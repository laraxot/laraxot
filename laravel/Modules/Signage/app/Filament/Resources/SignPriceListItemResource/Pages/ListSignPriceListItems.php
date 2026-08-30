<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;

final class ListSignPriceListItems extends ListRecords
{
    protected static string $resource = SignPriceListItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
