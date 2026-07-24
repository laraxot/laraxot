<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages;

use Filament\Tables\Columns\Column;
use Modules\Employee\Filament\Resources\AbsenceRequestResource;
use Modules\Employee\Filament\Resources\AbsenceRequestResource\Tables\AbsenceRequestsTable;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListAbsenceRequests extends XotBaseListRecords
{
    protected static string $resource = AbsenceRequestResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return (new AbsenceRequestsTable())->getTableColumns();
    }
}
