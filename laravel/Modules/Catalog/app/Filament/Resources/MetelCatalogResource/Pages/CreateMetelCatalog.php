<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;

use Modules\Catalog\Filament\Resources\MetelCatalogResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateMetelCatalog extends XotBaseCreateRecord
{
    protected static string $resource = MetelCatalogResource::class;
}
