<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class ProgressReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'progressReports';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'SAL';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('N.')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Stato')->badge()->sortable(),
                Tables\Columns\TextColumn::make('period_from')->label('Da')->date(),
                Tables\Columns\TextColumn::make('period_to')->label('A')->date(),
                Tables\Columns\TextColumn::make('work_amount')->label('Lavori')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('retention_amount')->label('Ritenuta')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('certificate_amount')->label('Certificato')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('progress_percentage')->label('%')->suffix('%')->sortable(),
            ]);
    }
}
