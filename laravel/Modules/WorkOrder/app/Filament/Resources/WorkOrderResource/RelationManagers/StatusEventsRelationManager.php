<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Carbon\CarbonImmutable;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusAction;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Models\WorkOrderStatusEvent;

final class StatusEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'statusEvents';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('work_orders.statuses');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('to_status_id')
                ->label(__('work_orders.fields.to_status'))
                ->options(fn () => WorkOrderStatus::query()->orderBy('sort_order')->pluck('name', 'id')->all())
                ->searchable()
                ->preload(),
            DateTimePicker::make('changed_at')
                ->label(__('work_orders.fields.changed_at')),
            Textarea::make('note')
                ->label(__('work_orders.fields.notes'))
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromStatus.name')
                    ->label(__('work_orders.fields.from_status'))
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('toStatus.name')
                    ->label(__('work_orders.fields.to_status'))
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('changed_at')
                    ->label(__('work_orders.fields.changed_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label(__('work_orders.fields.notes'))
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): WorkOrderStatusEvent {
                        $changedAt = isset($data['changed_at']) && $data['changed_at']
                            ? CarbonImmutable::parse((string) $data['changed_at'])
                            : null;

                        /** @var \Modules\WorkOrder\Models\WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();

                        return app(ChangeWorkOrderStatusAction::class)->execute(
                            workOrder: $workOrder,
                            toStatusId: (int) $data['to_status_id'],
                            changedByUserId: Auth::id(),
                            note: $data['note'] ?? null,
                            changedAt: $changedAt,
                        );
                    }),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}

