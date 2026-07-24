<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;

use Filament\Actions;
use Modules\Catalog\Filament\Resources\MetelCatalogResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListMetelCatalogs extends XotBaseListRecords
{
    protected static string $resource = MetelCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
