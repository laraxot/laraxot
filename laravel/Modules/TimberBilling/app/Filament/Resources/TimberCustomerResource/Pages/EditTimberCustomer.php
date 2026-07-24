<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\TimberBilling\Filament\Resources\TimberCustomerResource;

final class EditTimberCustomer extends EditRecord
{
    protected static string $resource = TimberCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
