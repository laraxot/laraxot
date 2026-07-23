<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\WorkHourResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Employee\Enums\WorkHourStatusEnum;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class WorkHoursTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable()->searchable(),
            'employee.name' => TextColumn::make('employee.name'),
            'employee.full_name' => TextColumn::make('employee.full_name'),
            'employee.email' => TextColumn::make('employee.email'),
            'type' => TextColumn::make('type')
                ->badge()
                ->colors([
                    'primary' => 'clock_in',
                    'success' => 'clock_out',
                    'warning' => 'break_start',
                    'info' => 'break_end',
                ])
                ->formatStateUsing(fn (WorkHourTypeEnum $state): string => $state->getLabel()),
            'timestamp' => TextColumn::make('timestamp')->dateTime('d/m/Y H:i')->sortable(),
            'location_name' => TextColumn::make('location_name')->searchable()->limit(30),
            'status' => TextColumn::make('status')
                ->badge()
                ->colors([
                    'warning' => 'pending',
                    'success' => 'approved',
                    'danger' => 'rejected',
                    'secondary' => 'cancelled',
                ])
                ->formatStateUsing(fn (WorkHourStatusEnum $state): string => $state->getLabel()),
            'approved_by' => TextColumn::make('approvedBy.name')->sortable()->searchable(),
            'approved_at' => TextColumn::make('approved_at')->dateTime('d/m/Y H:i')->sortable(),
            'created_at' => TextColumn::make('created_at')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
