<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class EligibilityChecksRelationManager extends RelationManager
{
    protected static string $relationship = 'eligibilityChecks';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('public_procurement.eligibility_checks');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->label(__('public_procurement.fields.status'))->badge()->sortable(),
                Tables\Columns\TextColumn::make('score')->label(__('public_procurement.fields.score'))->sortable(),
                Tables\Columns\TextColumn::make('summary')->label(__('public_procurement.fields.summary'))->limit(80),
                Tables\Columns\TextColumn::make('evaluated_at')->label(__('public_procurement.fields.evaluated_at'))->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc');
    }
}
