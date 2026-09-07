<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\PlatformSettingResource\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class PlatformSettingsTable extends XotBaseResourceTable
{
    protected static string $resource = PlatformSettingResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->sortable(),
            'key' => TextColumn::make('key')
                ->searchable()
                ->sortable(),
            'value_preview' => TextColumn::make('value_preview')
                ->label(__('platform.settings.fields.value'))
                ->getStateUsing(static fn ($record): string => PlatformSettingResource::toStringValue($record->value_json))
                ->limit(50),
            'updated_by_user_id' => TextColumn::make('updatedBy.name')
                ->placeholder('—'),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ];
    }

    /**
     * @return array<int, ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'key';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }
}
