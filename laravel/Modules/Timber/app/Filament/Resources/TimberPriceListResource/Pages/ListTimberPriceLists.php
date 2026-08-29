<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPriceListResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberPriceListResource;

final class ListTimberPriceLists extends XotBaseListRecords
{
    protected static string $resource = TimberPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo listino'),
        ];
    }
}
