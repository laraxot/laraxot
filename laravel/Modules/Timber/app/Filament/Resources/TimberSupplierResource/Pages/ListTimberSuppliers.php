<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberSupplierResource;

final class ListTimberSuppliers extends XotBaseListRecords
{
    protected static string $resource = TimberSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
