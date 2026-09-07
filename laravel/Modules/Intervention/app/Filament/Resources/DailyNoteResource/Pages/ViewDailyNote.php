<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewDailyNote extends XotBaseViewRecord
{
    protected static string $resource = DailyNoteResource::class;
}
