<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;

final class ListSignCatalogItems extends ListRecords
{
    protected static string $resource = SignCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
