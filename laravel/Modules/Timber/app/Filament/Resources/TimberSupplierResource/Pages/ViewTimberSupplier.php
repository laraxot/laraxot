<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Modules\Timber\Filament\Resources\TimberSupplierResource;

final class ViewTimberSupplier extends XotBaseViewRecord
{
    protected static string $resource = TimberSupplierResource::class;

    /** @return array<string, \Filament\Schemas\Components\Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
