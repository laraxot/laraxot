<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Filament\Actions;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListDailyNotes extends XotBaseListRecords
{
    protected static string $resource = DailyNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
