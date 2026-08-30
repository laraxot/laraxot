<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\MetalWork\Filament\Resources\MetalRawMaterialResource;

final class ListMetalRawMaterials extends ListRecords
{
    protected static string $resource = MetalRawMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
