<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberAgentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberAgent extends XotBaseViewRecord
{
    protected static string $resource = TimberAgentResource::class;

    /** @return array<string, Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
