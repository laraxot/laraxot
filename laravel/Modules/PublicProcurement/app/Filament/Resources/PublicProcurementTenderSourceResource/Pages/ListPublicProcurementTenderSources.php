<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Pages;

use Filament\Actions;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListPublicProcurementTenderSources extends XotBaseListRecords
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;

    protected function getHeaderActions(): array
    {
        return ['create' => Actions\CreateAction::make()];
    }
}
