<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Catalog\Filament\Resources\CatalogModelResource;

final class EditCatalogModel extends EditRecord
{
    protected static string $resource = CatalogModelResource::class;
}
