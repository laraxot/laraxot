<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Resources\JobManagerResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Job\Models\JobManager;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class JobManagersTable extends XotBaseResourceTable
{
    /**
     * @var class-string<JobManager>
     */
    protected static string $model = JobManager::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->searchable()->sortable()->wrap(),
            'queue' => TextColumn::make('queue')->searchable()->sortable()->badge(),
            'failed' => IconColumn::make('failed')->boolean()->sortable(),
            'attempt' => TextColumn::make('attempt')->numeric()->sortable(),
            'progress' => TextColumn::make('progress')->numeric()->sortable()->suffix('%'),
            'started_at' => TextColumn::make('started_at')->dateTime()->sortable(),
            'finished_at' => TextColumn::make('finished_at')->dateTime()->sortable(),
        ];
    }

    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
