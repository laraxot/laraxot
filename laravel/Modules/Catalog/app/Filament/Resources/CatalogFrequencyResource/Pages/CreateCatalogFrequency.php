<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogFrequencyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Catalog\Filament\Resources\CatalogFrequencyResource;

final class CreateCatalogFrequency extends CreateRecord
{
    protected static string $resource = CatalogFrequencyResource::class;
}
