<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberTransporterResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberTransporter extends XotBaseViewRecord
{
    protected static string $resource = TimberTransporterResource::class;

    /** @return array<string, Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
