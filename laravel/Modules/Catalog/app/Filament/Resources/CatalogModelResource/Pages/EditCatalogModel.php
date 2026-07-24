<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;

use Modules\Catalog\Filament\Resources\CatalogModelResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditCatalogModel extends XotBaseEditRecord
{
    protected static string $resource = CatalogModelResource::class;
}
