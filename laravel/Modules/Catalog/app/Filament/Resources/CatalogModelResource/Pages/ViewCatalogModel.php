<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Catalog\Filament\Resources\CatalogModelResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewCatalogModel extends XotBaseViewRecord
{
    protected static string $resource = CatalogModelResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
