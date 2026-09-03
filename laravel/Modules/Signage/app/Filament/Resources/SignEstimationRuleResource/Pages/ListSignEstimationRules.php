<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignEstimationRuleResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\Signage\Filament\Resources\SignEstimationRuleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSignEstimationRules extends XotBaseListRecords
{
    protected static string $resource = SignEstimationRuleResource::class;

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
            'role_code' => Tables\Columns\TextColumn::make('role_code')
                ->formatStateUsing(fn (string $state): string => __('signage::signage.roles.'.$state))
                ->sortable(),
            'strategy_code' => Tables\Columns\TextColumn::make('strategy_code')
                ->formatStateUsing(fn (string $state): string => __('signage::signage.strategies.'.$state)),
            'unit_code' => Tables\Columns\TextColumn::make('unit_code'),
            'factor' => Tables\Columns\TextColumn::make('factor')->numeric(),
            'base_value' => Tables\Columns\TextColumn::make('base_value')->numeric(),
            'min_per_piece' => Tables\Columns\TextColumn::make('min_per_piece')->numeric()->placeholder('—'),
            'is_active' => Tables\Columns\IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
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
