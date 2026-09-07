<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Pages;

use Filament\Actions;
use Modules\Compliance\Filament\Resources\EquipmentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListEquipment extends XotBaseListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return ['create' => Actions\CreateAction::make()];
    }
}
