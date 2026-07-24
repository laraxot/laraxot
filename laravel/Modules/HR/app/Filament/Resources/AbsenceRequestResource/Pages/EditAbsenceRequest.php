<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditAbsenceRequest extends XotBaseEditRecord
{
    protected static string $resource = AbsenceRequestResource::class;
}
