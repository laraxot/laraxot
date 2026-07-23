<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Catalog\Filament\Resources\CatalogModelResource;

final class ViewCatalogModel extends ViewRecord
{
    protected static string $resource = CatalogModelResource::class;
}
