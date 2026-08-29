<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Timber\Filament\Resources\TimberCustomerResource;

final class ListTimberCustomers extends ListRecords
{
    protected static string $resource = TimberCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
