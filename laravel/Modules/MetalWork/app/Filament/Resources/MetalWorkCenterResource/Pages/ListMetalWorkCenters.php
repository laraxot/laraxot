<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalWorkCenterResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\MetalWork\Filament\Resources\MetalWorkCenterResource;

final class ListMetalWorkCenters extends ListRecords
{
    protected static string $resource = MetalWorkCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
