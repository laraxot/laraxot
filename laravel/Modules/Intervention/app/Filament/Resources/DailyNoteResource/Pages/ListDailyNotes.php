<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Intervention\Filament\Resources\DailyNoteResource;

final class ListDailyNotes extends ListRecords
{
    protected static string $resource = DailyNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
