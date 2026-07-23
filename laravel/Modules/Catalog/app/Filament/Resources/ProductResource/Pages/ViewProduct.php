<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Catalog\Filament\Resources\ProductResource;

final class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;
}
