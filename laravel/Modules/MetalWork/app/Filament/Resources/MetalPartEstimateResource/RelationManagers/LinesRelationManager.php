<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalPartEstimateResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * The frozen breakdown: what each work centre contributed to the price, and
 * which piece of geometry produced its quantity.
 */
final class LinesRelationManager extends XotBaseRelationManager
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
                Tables\Columns\TextColumn::make('position'),
                Tables\Columns\TextColumn::make('work_center_code')
                    ->formatStateUsing(fn (?string $state): string => (string) __('metalwork::metalwork.work_centers_list.'.(string) $state)),
                Tables\Columns\TextColumn::make('description')->wrap(),
                Tables\Columns\TextColumn::make('quantity')->numeric(4),
                Tables\Columns\TextColumn::make('unit_code'),
                Tables\Columns\TextColumn::make('unit_cost')->numeric(4)->suffix(' €'),
                Tables\Columns\TextColumn::make('line_cost')->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('markup_percent')->numeric(2)->suffix('%'),
                Tables\Columns\TextColumn::make('line_price')->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('driver_code')
                    ->placeholder('—')
                    ->formatStateUsing(fn (?string $state): ?string => $state === null ? null : (string) __('metalwork::metalwork.drivers.'.$state)),
            ]);
    }
}
