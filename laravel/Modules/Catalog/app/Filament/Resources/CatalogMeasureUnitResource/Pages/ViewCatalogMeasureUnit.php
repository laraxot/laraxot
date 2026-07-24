<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewCatalogMeasureUnit extends XotBaseViewRecord
{
    protected static string $resource = CatalogMeasureUnitResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
