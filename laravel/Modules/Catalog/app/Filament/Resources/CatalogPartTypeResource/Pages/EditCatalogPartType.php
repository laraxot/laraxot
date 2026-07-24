<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Pages;

use Modules\Catalog\Filament\Resources\CatalogPartTypeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditCatalogPartType extends XotBaseEditRecord
{
    protected static string $resource = CatalogPartTypeResource::class;
}
