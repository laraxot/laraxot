<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogFrequencyResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Catalog\Filament\Resources\CatalogFrequencyResource;

final class EditCatalogFrequency extends EditRecord
{
    protected static string $resource = CatalogFrequencyResource::class;
}
