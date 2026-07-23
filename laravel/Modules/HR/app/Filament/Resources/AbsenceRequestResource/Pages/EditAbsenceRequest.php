<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\HR\Filament\Resources\AbsenceRequestResource;

final class EditAbsenceRequest extends EditRecord
{
    protected static string $resource = AbsenceRequestResource::class;
}

