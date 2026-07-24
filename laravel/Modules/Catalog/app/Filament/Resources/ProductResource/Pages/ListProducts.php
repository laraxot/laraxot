<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListProducts extends XotBaseListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
