<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Filament\Actions\CreateAction;
use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListAbsenceRequests extends XotBaseListRecords
{
    protected static string $resource = AbsenceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
