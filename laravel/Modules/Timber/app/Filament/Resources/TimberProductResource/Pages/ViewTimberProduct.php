<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Modules\Timber\Filament\Resources\TimberProductResource;

final class ViewTimberProduct extends XotBaseViewRecord
{
    protected static string $resource = TimberProductResource::class;

    /** @return array<string, \Filament\Schemas\Components\Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
