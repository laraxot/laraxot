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
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Models\WorkOrderStatusEvent;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class StatusEventsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'statusEvents';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('workorder::work_orders.statuses');
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('to_status_id')
                ->options(fn () => WorkOrderStatus::query()->orderBy('sort_order')->pluck('name', 'id')->all())
                ->searchable()
                ->preload(),
            DateTimePicker::make('changed_at'),
            Textarea::make('note')
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromStatus.name')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('toStatus.name')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('changed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
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

                        /** @var WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();

                        return app(ChangeWorkOrderStatusAction::class)->execute(
                            workOrder: $workOrder,
                            toStatusId: (int) $data['to_status_id'],
                            changedByUserId: (($authId = Auth::id()) !== null ? (int) $authId : null),
                            note: isset($data['note']) ? (string) $data['note'] : null,
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
