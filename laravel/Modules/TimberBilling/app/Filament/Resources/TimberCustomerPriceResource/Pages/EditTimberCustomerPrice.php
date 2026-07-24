<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberCustomerPriceResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\TimberBilling\Filament\Resources\TimberCustomerPriceResource;

final class EditTimberCustomerPrice extends EditRecord
{
    protected static string $resource = TimberCustomerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
