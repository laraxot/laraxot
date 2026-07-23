<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Catalog\Filament\Resources\CatalogPartTypeResource;

final class CreateCatalogPartType extends CreateRecord
{
    protected static string $resource = CatalogPartTypeResource::class;
}
