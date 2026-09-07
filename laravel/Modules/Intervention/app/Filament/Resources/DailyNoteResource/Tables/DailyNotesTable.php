<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class DailyNotesTable extends XotBaseResourceTable
{
    protected static string $resource = DailyNoteResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'user.name' => TextColumn::make('user.name')

                ->searchable()
                ->sortable(),
            'note_date' => TextColumn::make('note_date')

                ->date()
                ->sortable(),
            'body' => TextColumn::make('body')

                ->limit(80)
                ->wrap()
                ->toggleable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, DeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
