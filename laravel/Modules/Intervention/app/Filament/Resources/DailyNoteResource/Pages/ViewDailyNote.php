<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Intervention\Filament\Resources\DailyNoteResource;

final class ViewDailyNote extends ViewRecord
{
    protected static string $resource = DailyNoteResource::class;
}
