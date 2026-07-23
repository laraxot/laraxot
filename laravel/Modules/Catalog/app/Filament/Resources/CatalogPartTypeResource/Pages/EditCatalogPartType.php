<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Catalog\Filament\Resources\CatalogPartTypeResource;

final class EditCatalogPartType extends EditRecord
{
    protected static string $resource = CatalogPartTypeResource::class;
}
