<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;

final class ListPublicProcurementTenderSources extends ListRecords
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
