<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Pages;

use Modules\Catalog\Filament\Resources\CatalogPartTypeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewCatalogPartType extends XotBaseViewRecord
{
    protected static string $resource = CatalogPartTypeResource::class;
}
