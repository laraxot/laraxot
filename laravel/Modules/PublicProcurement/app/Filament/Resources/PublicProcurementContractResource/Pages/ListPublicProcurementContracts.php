<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;

final class ListPublicProcurementContracts extends ListRecords
{
    protected static string $resource = PublicProcurementContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
