<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Catalog\Filament\Resources\CatalogModelResource;

final class CreateCatalogModel extends CreateRecord
{
    protected static string $resource = CatalogModelResource::class;
}
