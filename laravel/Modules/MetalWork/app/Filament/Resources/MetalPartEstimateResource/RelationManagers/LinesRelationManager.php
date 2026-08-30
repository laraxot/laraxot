<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalPartEstimateResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * The frozen breakdown: what each work centre contributed to the price, and
 * which piece of geometry produced its quantity.
 */
final class LinesRelationManager extends RelationManager
{
    protected static string $relationship = 'lines';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return (string) __('metalwork::metalwork.quotation.lines');
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->columns([
                Tables\Columns\TextColumn::make('position')->label(__('metalwork::metalwork.fields.position')),
                Tables\Columns\TextColumn::make('work_center_code')
                    ->label(__('metalwork::metalwork.fields.work_center_code'))
                    ->formatStateUsing(fn (?string $state): string => (string) __('metalwork::metalwork.work_centers_list.'.(string) $state)),
                Tables\Columns\TextColumn::make('description')->label(__('metalwork::metalwork.fields.description'))->wrap(),
                Tables\Columns\TextColumn::make('quantity')->label(__('metalwork::metalwork.fields.quantity'))->numeric(4),
                Tables\Columns\TextColumn::make('unit_code')->label(__('metalwork::metalwork.fields.unit_code')),
                Tables\Columns\TextColumn::make('unit_cost')->label(__('metalwork::metalwork.fields.unit_cost'))->numeric(4)->suffix(' €'),
                Tables\Columns\TextColumn::make('line_cost')->label(__('metalwork::metalwork.fields.line_cost'))->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('markup_percent')->label(__('metalwork::metalwork.fields.markup_percent'))->numeric(2)->suffix('%'),
                Tables\Columns\TextColumn::make('line_price')->label(__('metalwork::metalwork.fields.line_price'))->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('driver_code')
                    ->label(__('metalwork::metalwork.fields.driver_code'))
                    ->placeholder('—')
                    ->formatStateUsing(fn (?string $state): ?string => $state === null ? null : (string) __('metalwork::metalwork.drivers.'.$state)),
            ]);
    }
}
