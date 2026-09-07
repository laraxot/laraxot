<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignEstimationRuleResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Signage\Filament\Resources\SignEstimationRuleResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class SignEstimationRulesTable extends XotBaseResourceTable
{
    protected static string $resource = SignEstimationRuleResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'role_code' => TextColumn::make('role_code')
                ->formatStateUsing(fn (string $state): string => __('signage::signage.roles.'.$state))
                ->sortable(),
            'strategy_code' => TextColumn::make('strategy_code')
                ->formatStateUsing(fn (string $state): string => __('signage::signage.strategies.'.$state)),
            'unit_code' => TextColumn::make('unit_code'),
            'factor' => TextColumn::make('factor')->numeric(),
            'base_value' => TextColumn::make('base_value')->numeric(),
            'min_per_piece' => TextColumn::make('min_per_piece')->numeric()->placeholder('—'),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, EditAction|DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
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

    public function getTableRecordTitleAttribute(): string
    {
        return 'role_code';
    }
}
