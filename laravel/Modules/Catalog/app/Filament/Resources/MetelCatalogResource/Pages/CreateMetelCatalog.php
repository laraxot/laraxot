<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Catalog\Filament\Resources\MetelCatalogResource;

final class CreateMetelCatalog extends CreateRecord
{
    protected static string $resource = MetelCatalogResource::class;
}
