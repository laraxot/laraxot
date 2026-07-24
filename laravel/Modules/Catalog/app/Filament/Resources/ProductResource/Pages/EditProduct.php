<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditProduct extends XotBaseEditRecord
{
    protected static string $resource = ProductResource::class;
}
