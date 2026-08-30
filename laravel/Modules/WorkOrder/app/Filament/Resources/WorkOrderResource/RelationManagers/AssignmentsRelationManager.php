<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Carbon\CarbonImmutable;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;
use Modules\WorkOrder\Actions\AssignUserToWorkOrderAction;
use Modules\WorkOrder\Actions\UnassignUserFromWorkOrderAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderAssignment;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class AssignmentsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'assignments';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('workorder::work_orders.assignments');
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label(__('workorder::work_orders.fields.assigned_to'))
                ->options(fn () => User::query()
                    ->orderBy('name')
                    ->get()
                    ->mapWithKeys(fn (User $user): array => [$user->id => $user->name])
                    ->all())
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('role')
                ->label(__('workorder::work_orders.fields.role'))
                ->maxLength(255),
            DateTimePicker::make('assigned_at')
                ->label(__('workorder::work_orders.fields.assigned_at')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('workorder::work_orders.fields.assigned_to'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label(__('workorder::work_orders.fields.role'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label(__('workorder::work_orders.fields.assigned_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): WorkOrderAssignment {
                        $assignedAt = isset($data['assigned_at']) && $data['assigned_at']
                            ? CarbonImmutable::parse((string) $data['assigned_at'])
                            : null;

                        /** @var WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();

                        return app(AssignUserToWorkOrderAction::class)->execute(
                            workOrderId: (int) $workOrder->id,
                            userId: (int) $data['user_id'],
                            role: isset($data['role']) ? (string) $data['role'] : null,
                            assignedByUserId: (($authId = Auth::id()) !== null ? (int) $authId : null),
                            assignedAt: $assignedAt,
                        );
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->using(function (WorkOrderAssignment $record): void {
                        app(UnassignUserFromWorkOrderAction::class)->execute(
                            workOrderId: (int) $record->work_order_id,
                            userId: (int) $record->user_id,
                        );
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
