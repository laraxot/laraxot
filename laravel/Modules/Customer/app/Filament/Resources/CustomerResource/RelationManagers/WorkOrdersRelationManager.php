<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class WorkOrdersRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'workOrders';

    /** @var class-string<WorkOrderResource> */
    protected static string $resource = WorkOrderResource::class;

    public function getFormSchema(): array
    {
        return [];
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('work_orders.work_orders');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('work_orders.fields.code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('work_orders.fields.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->label(__('work_orders.fields.status'))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('site.name')
                    ->label(__('work_orders.fields.site'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('work_orders.fields.scheduled_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('work_orders.fields.description'))
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('scheduled_at', 'desc')
            ->actions([
                ViewAction::make()
                    ->url(fn (WorkOrder $record): string => WorkOrderResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
