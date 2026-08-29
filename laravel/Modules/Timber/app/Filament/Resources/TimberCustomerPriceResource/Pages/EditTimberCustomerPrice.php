<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditTimberCustomerPrice extends XotBaseEditRecord
{
    protected static string $resource = TimberCustomerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
