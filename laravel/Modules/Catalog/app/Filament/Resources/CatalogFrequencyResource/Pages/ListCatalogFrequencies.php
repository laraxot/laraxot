<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogFrequencyResource\Pages;

use Filament\Actions;
use Modules\Catalog\Filament\Resources\CatalogFrequencyResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListCatalogFrequencies extends XotBaseListRecords
{
    protected static string $resource = CatalogFrequencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
