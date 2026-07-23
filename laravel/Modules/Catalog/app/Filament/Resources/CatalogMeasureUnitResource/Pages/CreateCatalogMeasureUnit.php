<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource;

final class CreateCatalogMeasureUnit extends CreateRecord
{
    protected static string $resource = CatalogMeasureUnitResource::class;
}
