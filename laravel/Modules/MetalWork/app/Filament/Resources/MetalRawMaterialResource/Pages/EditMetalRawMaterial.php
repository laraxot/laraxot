<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\Pages;

use Filament\Actions;
use Modules\MetalWork\Filament\Resources\MetalRawMaterialResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditMetalRawMaterial extends XotBaseEditRecord
{
    protected static string $resource = MetalRawMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'delete' => Actions\DeleteAction::make(),
        ];
    }
}
