<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogFrequencyResource\Pages;

use Modules\Catalog\Filament\Resources\CatalogFrequencyResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditCatalogFrequency extends XotBaseEditRecord
{
    protected static string $resource = CatalogFrequencyResource::class;
}
