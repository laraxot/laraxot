<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Component;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewDailyNote extends XotBaseViewRecord
{
    protected static string $resource = DailyNoteResource::class;

    /**
     * @return array<string, Component|Entry>
     */
    protected function getInfolistSchema(): array
    {
        return DailyNoteResource::getInfolistSchema();
    }
}
