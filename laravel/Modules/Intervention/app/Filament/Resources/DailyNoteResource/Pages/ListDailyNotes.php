<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListDailyNotes extends XotBaseListRecords
{
    protected static string $resource = DailyNoteResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'user.name' => Tables\Columns\TextColumn::make('user.name')
                ->label(__('interventions.fields.user'))
                ->searchable()
                ->sortable(),
            'note_date' => Tables\Columns\TextColumn::make('note_date')
                ->label(__('interventions.fields.note_date'))
                ->date()
                ->sortable(),
            'body' => Tables\Columns\TextColumn::make('body')
                ->label(__('interventions.fields.body'))
                ->limit(80)
                ->wrap()
                ->toggleable(),
            'updated_at' => Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make(),
        ];
    }
}
