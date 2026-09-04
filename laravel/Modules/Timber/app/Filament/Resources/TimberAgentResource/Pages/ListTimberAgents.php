<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\Timber\Filament\Resources\TimberAgentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberAgents extends XotBaseListRecords
{
    protected static string $resource = TimberAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'full_name' => Tables\Columns\TextColumn::make('full_name')
                ->searchable()
                ->sortable(),
            'zone' => Tables\Columns\TextColumn::make('zone')
                ->toggleable(),
            'commission_percent' => Tables\Columns\TextColumn::make('commission_percent')
                ->suffix('%'),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->boolean(),
            'updated_at' => Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }
}
