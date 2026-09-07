<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewProduct extends XotBaseViewRecord
{
    protected static string $resource = ProductResource::class;
}
