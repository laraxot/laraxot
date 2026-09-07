<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Pages;

use Filament\Actions;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListPublicProcurementContracts extends XotBaseListRecords
{
    protected static string $resource = PublicProcurementContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
