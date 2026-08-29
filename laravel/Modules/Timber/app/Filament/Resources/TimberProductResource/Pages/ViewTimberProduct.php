<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberProduct extends XotBaseViewRecord
{
    protected static string $resource = TimberProductResource::class;

    /** @return array<string, Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
