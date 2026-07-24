<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;

use Modules\Catalog\Filament\Resources\MetelCatalogResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditMetelCatalog extends XotBaseEditRecord
{
    protected static string $resource = MetelCatalogResource::class;
}
