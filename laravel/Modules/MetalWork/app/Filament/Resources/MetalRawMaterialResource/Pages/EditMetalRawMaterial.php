<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\MetalWork\Filament\Resources\MetalRawMaterialResource;

final class EditMetalRawMaterial extends EditRecord
{
    protected static string $resource = MetalRawMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
