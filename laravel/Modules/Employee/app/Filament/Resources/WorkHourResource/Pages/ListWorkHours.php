<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\WorkHourResource\Pages;

use Filament\Tables\Columns\Column;
use Modules\Employee\Filament\Resources\WorkHourResource;
use Modules\Employee\Filament\Resources\WorkHourResource\Tables\WorkHoursTable;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListWorkHours extends XotBaseListRecords
{
    protected static string $resource = WorkHourResource::class;

    /**
     * Definisce le colonne della tabella per la lista delle ore di lavoro.
     *
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return (new WorkHoursTable())->getTableColumns();
    }
}
