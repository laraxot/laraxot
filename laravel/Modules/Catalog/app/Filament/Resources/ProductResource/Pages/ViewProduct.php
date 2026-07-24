<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewProduct extends XotBaseViewRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
