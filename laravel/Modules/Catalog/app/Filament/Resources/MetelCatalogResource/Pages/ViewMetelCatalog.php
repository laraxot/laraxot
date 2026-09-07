<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;

use Modules\Catalog\Filament\Resources\MetelCatalogResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewMetelCatalog extends XotBaseViewRecord
{
    protected static string $resource = MetelCatalogResource::class;
}
