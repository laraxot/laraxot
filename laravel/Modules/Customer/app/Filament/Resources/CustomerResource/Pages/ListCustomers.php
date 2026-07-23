<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListCustomers extends XotBaseListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
