<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateProduct extends XotBaseCreateRecord
{
    protected static string $resource = ProductResource::class;
}
