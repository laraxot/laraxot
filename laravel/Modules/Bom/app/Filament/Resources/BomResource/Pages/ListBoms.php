<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Pages;

use Filament\Actions;
use Modules\Bom\Filament\Resources\BomResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListBoms extends XotBaseListRecords
{
    protected static string $resource = BomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
