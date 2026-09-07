<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewAbsenceRequest extends XotBaseViewRecord
{
    protected static string $resource = AbsenceRequestResource::class;
}
