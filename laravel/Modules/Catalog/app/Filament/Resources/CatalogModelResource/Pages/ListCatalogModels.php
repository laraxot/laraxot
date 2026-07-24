<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;

use Filament\Actions;
use Modules\Catalog\Filament\Resources\CatalogModelResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListCatalogModels extends XotBaseListRecords
{
    protected static string $resource = CatalogModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
