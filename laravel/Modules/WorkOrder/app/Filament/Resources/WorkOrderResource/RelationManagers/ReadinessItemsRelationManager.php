<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Compliance\Actions\EvaluateWorkOrderReadinessAction;
use Modules\WorkOrder\Models\WorkOrder;

final class ReadinessItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'readinessItems';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('compliance.readiness_items');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->label(__('compliance.fields.label'))->searchable(),
                Tables\Columns\TextColumn::make('severity')->label(__('compliance.fields.severity'))->badge()->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('compliance.fields.status'))->badge()->sortable(),
                Tables\Columns\TextColumn::make('message')->label(__('compliance.fields.message'))->limit(80),
                Tables\Columns\TextColumn::make('due_on')->label(__('compliance.fields.due_on'))->date()->sortable(),
            ])
            ->headerActions([
                Action::make('evaluate')
                    ->label(__('compliance.actions.evaluate_readiness'))
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
