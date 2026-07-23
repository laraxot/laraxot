<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;

final class EditCustomerSystem extends EditRecord
{
    protected static string $resource = CustomerSystemResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
