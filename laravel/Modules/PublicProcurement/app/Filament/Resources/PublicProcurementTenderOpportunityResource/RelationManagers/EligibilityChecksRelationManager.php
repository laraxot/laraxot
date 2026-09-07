<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class EligibilityChecksRelationManager extends XotBaseRelationManager
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
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('score')->sortable(),
                Tables\Columns\TextColumn::make('summary')->limit(80),
                Tables\Columns\TextColumn::make('evaluated_at')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc');
    }
}
