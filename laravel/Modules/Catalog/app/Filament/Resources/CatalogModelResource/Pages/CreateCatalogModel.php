<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;

use Modules\Catalog\Filament\Resources\CatalogModelResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateCatalogModel extends XotBaseCreateRecord
{
    protected static string $resource = CatalogModelResource::class;
}
