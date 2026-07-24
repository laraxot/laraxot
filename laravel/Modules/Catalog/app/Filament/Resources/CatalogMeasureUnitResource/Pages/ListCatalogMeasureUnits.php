<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource\Pages;

use Filament\Actions;
use Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListCatalogMeasureUnits extends XotBaseListRecords
{
    protected static string $resource = CatalogMeasureUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
