<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Compliance\Actions\EvaluateWorkOrderReadinessAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class ReadinessItemsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'readinessItems';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('compliance.readiness_items');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('severity')->badge()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('message')->limit(80),
                Tables\Columns\TextColumn::make('due_on')->date()->sortable(),
            ])
            ->headerActions([
                Action::make('evaluate')

                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function (): void {
                        /** @var WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();
                        app(EvaluateWorkOrderReadinessAction::class)->execute($workOrder);
                    }),
            ]);
    }
}
