<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource;

final class ListTimberSuppliers extends ListRecords
{
    protected static string $resource = TimberSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
