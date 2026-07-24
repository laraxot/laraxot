<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Pages;

use Filament\Actions;
use Modules\Catalog\Filament\Resources\CatalogPartTypeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListCatalogPartTypes extends XotBaseListRecords
{
    protected static string $resource = CatalogPartTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
