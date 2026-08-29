<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberSupplierResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberSupplier extends XotBaseViewRecord
{
    protected static string $resource = TimberSupplierResource::class;

    /** @return array<string, Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
