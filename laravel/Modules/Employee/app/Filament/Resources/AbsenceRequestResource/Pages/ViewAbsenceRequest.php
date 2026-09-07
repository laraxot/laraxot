<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages;

use Modules\Employee\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewAbsenceRequest extends XotBaseViewRecord
{
    protected static string $resource = AbsenceRequestResource::class;
}
