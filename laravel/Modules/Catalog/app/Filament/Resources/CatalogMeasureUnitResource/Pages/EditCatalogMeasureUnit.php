<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource\Pages;

use Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditCatalogMeasureUnit extends XotBaseEditRecord
{
    protected static string $resource = CatalogMeasureUnitResource::class;
}
