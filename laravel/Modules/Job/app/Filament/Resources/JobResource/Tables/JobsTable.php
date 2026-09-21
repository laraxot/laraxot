<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Resources\JobResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;
use Modules\Job\Models\Job;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class JobsTable extends XotBaseResourceTable
{
    /**
     * @var class-string<Job>
     */
    protected static string $model = Job::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'queue' => TextColumn::make('queue')->searchable()->sortable()->badge(),
            'attempts' => TextColumn::make('attempts')->numeric()->sortable(),
            'available_at' => TextColumn::make('available_at')->formatStateUsing(static fn (int $state): string => Carbon::createFromTimestamp($state)->format('Y-m-d H:i:s'))->sortable(),
            'reserved_at' => TextColumn::make('reserved_at')->formatStateUsing(static fn (int $state): string => Carbon::createFromTimestamp($state)->format('Y-m-d H:i:s'))->sortable(),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            'status' => SelectFilter::make('status')->options([
                'running' => 'Running',
                'waiting' => 'Waiting',
                'failed' => 'Failed',
            ]),
            'queue' => SelectFilter::make('queue')->options(Job::distinct()->pluck('queue', 'queue')->toArray(...)),
        ];
    }

    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }
}
