<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditCustomer extends XotBaseEditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
}
