<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Catalog\Filament\Resources\MetelCatalogResource;

final class EditMetelCatalog extends EditRecord
{
    protected static string $resource = MetelCatalogResource::class;
}
